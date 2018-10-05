<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace Jose;

use Base64Url\Base64Url;
use Jose\Algorithm\ContentEncryption\ContentEncryptionInterface;
use Jose\Algorithm\JWAManagerInterface;
use Jose\Algorithm\KeyEncryption\DirectEncryptionInterface;
use Jose\Algorithm\KeyEncryption\KeyAgreementInterface;
use Jose\Algorithm\KeyEncryption\KeyAgreementWrappingInterface;
use Jose\Algorithm\KeyEncryption\KeyEncryptionInterface;
use Jose\Behaviour\HasCompressionManager;
use Jose\Behaviour\HasJWAManager;
use Jose\Behaviour\HasKeyChecker;
use Jose\Behaviour\HasPayloadConverter;
use Jose\Compression\CompressionManagerInterface;
use Jose\Object\EncryptionInstructionInterface;
use Jose\Payload\PayloadConverterManagerInterface;
use Jose\Util\Converter;

/**
 */
final class Encrypter implements EncrypterInterface
{
    use HasKeyChecker;
    use HasJWAManager;
    use HasPayloadConverter;
    use HasCompressionManager;

    /**
     * Encrypter constructor.
     *
     * @param \Jose\Algorithm\JWAManagerInterface            $jwa_manager
     * @param \Jose\Payload\PayloadConverterManagerInterface $payload_converter_manager
     * @param \Jose\Compression\CompressionManagerInterface  $compression_manager
     */
    public function __construct(
        JWAManagerInterface $jwa_manager,
        PayloadConverterManagerInterface $payload_converter_manager,
        CompressionManagerInterface $compression_manager)
    {
        $this->setJWAManager($jwa_manager);
        $this->setPayloadConverter($payload_converter_manager);
        $this->setCompressionManager($compression_manager);
    }

    /**
     * @param array|\Jose\Object\JWKInterface|\Jose\Object\JWKSetInterface|\Jose\Object\JWTInterface|string $input
     * @param array                                                                                         $instructions
     * @param array                                                                                         $shared_protected_header
     * @param array                                                                                         $shared_unprotected_header
     * @param string                                                                                        $serialization
     * @param null                                                                                          $aad
     *
     * @return string
     */
    public function encrypt($input, array $instructions, $serialization, array $shared_protected_header = [], array $shared_unprotected_header = [], $aad = null)
    {
        $additional_header = [];
        $this->checkSerializationMode($serialization);
        $input = $this->getPayloadConverter()->convertPayloadToString($additional_header, $input);
        $this->checkInstructions($instructions, $serialization);
        if (!empty($shared_unprotected_header) && JSONSerializationModes::JSON_COMPACT_SERIALIZATION === $serialization) {
            throw new \InvalidArgumentException('Cannot create Compact Json Serialization representation: shared unprotected header cannot be kept');
        }
        if (!empty($aad) && JSONSerializationModes::JSON_COMPACT_SERIALIZATION === $serialization) {
            throw new \InvalidArgumentException('Cannot create Compact Json Serialization representation: AAD cannot be kept');
        }

        $protected_header = array_merge($shared_protected_header, $additional_header);

        // We check if key management mode is OK
        $key_management_mode = $this->getKeyManagementMode($instructions, $protected_header, $shared_unprotected_header);

        // We get the content encryption algorithm
        $content_encryption_algorithm = $this->getContentEncryptionAlgorithm($instructions, $protected_header, $shared_unprotected_header);

        // CEK
        $cek = $this->determineCEK($key_management_mode, $instructions, $protected_header, $shared_unprotected_header, $content_encryption_algorithm->getCEKSize());

        $recipients = ['recipients' => []];
        foreach ($instructions as $instruction) {
            $recipients['recipients'][] = $this->computeRecipient($instruction, $protected_header, $shared_unprotected_header, $cek, $content_encryption_algorithm->getCEKSize(), $serialization);
        }

        // We prepare the payload and compress it if required
        $compression_method = $this->findCompressionMethod($instructions, $protected_header, $shared_unprotected_header);
        $this->compressPayload($input, $compression_method);

        // We compute the initialization vector
        $iv = null;
        if (null !== $iv_size = $content_encryption_algorithm->getIVSize()) {
            $iv = $this->createIV($iv_size);
        }

        // JWT Shared protected header
        $jwt_shared_protected_header = Base64Url::encode(json_encode($protected_header));

        // We encrypt the payload and get the tag
        $tag = null;
        $ciphertext = $content_encryption_algorithm->encryptContent($input, $cek, $iv, $aad, $jwt_shared_protected_header, $tag);

        // JWT Ciphertext
        $jwt_ciphertext = Base64Url::encode($ciphertext);

        // JWT AAD
        $jwt_aad = null === $aad ? null : Base64Url::encode($aad);

        // JWT Tag
        $jwt_tag = null === $tag ? null : Base64Url::encode($tag);

        // JWT IV
        $jwt_iv = null === $iv ? '' : Base64Url::encode($iv);

        $values = [
            'ciphertext'  => $jwt_ciphertext,
            'protected'   => $jwt_shared_protected_header,
            'unprotected' => $shared_unprotected_header,
            'iv'          => $jwt_iv,
            'tag'         => $jwt_tag,
            'aad'         => $jwt_aad,
        ];
        foreach ($values as $key => $value) {
            if (!empty($value)) {
                $recipients[$key] = $value;
            }
        }

        return Converter::convert($recipients, $serialization);
    }

    /**
     * @param \Jose\Object\EncryptionInstructionInterface $instruction
     * @param                                             $protected_header
     * @param                                             $unprotected_header
     * @param string                                      $cek
     * @param int                                         $cek_size
     * @param string                                      $serialization
     *
     * @throws \Exception
     *
     * @return array
     */
    private function computeRecipient(EncryptionInstructionInterface $instruction, &$protected_header, $unprotected_header, $cek, $cek_size, $serialization)
    {
        if (!$this->checkKeyUsage($instruction->getRecipientKey(), 'encryption')) {
            throw new \InvalidArgumentException('Key cannot be used to encrypt');
        }

        $recipient_header = $instruction->getRecipientUnprotectedHeader();
        $complete_header = array_merge($protected_header, $unprotected_header, $recipient_header);

        $key_encryption_algorithm = $this->getKeyEncryptionAlgorithm($complete_header);

        if (!$this->checkKeyAlgorithm($instruction->getRecipientKey(), $key_encryption_algorithm->getAlgorithmName())) {
            throw new \InvalidArgumentException(sprintf('Key is only allowed for algorithm "%s".', $instruction->getRecipientKey()->get('alg')));
        }

        $jwt_cek = null;
        if ($key_encryption_algorithm instanceof KeyEncryptionInterface) {
            $jwt_cek = Base64Url::encode($key_encryption_algorithm->encryptKey($instruction->getRecipientKey(), $cek, $protected_header));
        } elseif ($key_encryption_algorithm instanceof KeyAgreementWrappingInterface) {
            if (null === $instruction->getSenderKey()) {
                throw new \RuntimeException('The sender key must be set using Key Agreement or Key Agreement with Wrapping algorithms.');
            }
            $additional_header_values = [];
            $jwt_cek = Base64Url::encode($key_encryption_algorithm->wrapAgreementKey($instruction->getSenderKey(), $instruction->getRecipientKey(), $cek, $cek_size, $complete_header, $additional_header_values));
            $this->updateHeader($additional_header_values, $protected_header, $recipient_header, $serialization);
        } elseif ($key_encryption_algorithm instanceof KeyAgreementInterface) {
            $additional_header_values = [];
            $jwt_cek = Base64Url::encode($key_encryption_algorithm->getAgreementKey($cek_size, $instruction->getSenderKey(), $instruction->getRecipientKey(), $complete_header, $additional_header_values));
            $this->updateHeader($additional_header_values, $protected_header, $recipient_header, $serialization);
        }

        $result = [];
        if (null !== $jwt_cek) {
            $result['encrypted_key'] = $jwt_cek;
        }
        if (!empty($recipient_header)) {
            $result['header'] = $recipient_header;
        }

        return $result;
    }

    /**
     * @param array  $additional_header_values
     * @param array  $protected_header
     * @param array  $recipient_header
     * @param string $serialization
     */
    private function updateHeader(array $additional_header_values, array &$protected_header, array &$recipient_header, $serialization)
    {
        if (JSONSerializationModes::JSON_COMPACT_SERIALIZATION === $serialization) {
            $protected_header = array_merge($protected_header, $additional_header_values);
        } else {
            $recipient_header = array_merge($recipient_header, $additional_header_values);
        }
    }

    /**
     * @param \Jose\Object\EncryptionInstructionInterface[] $instructions
     * @param array                                         $protected_header
     * @param array                                         $unprotected_header
     *
     * @return string
     */
    private function getKeyManagementMode(array $instructions, $protected_header, $unprotected_header)
    {
        $mode = null;
        foreach ($instructions as $instruction) {
            $recipient_header = $instruction->getRecipientUnprotectedHeader();
            $complete_header = array_merge($protected_header, $unprotected_header, $recipient_header);

            $temp = $this->getKeyManagementMode2($complete_header);
            if (null === $mode) {
                $mode = $temp;
            } else {
                if (!$this->areKeyManagementModeAuthorized($mode, $temp)) {
                    throw new \RuntimeException('Foreign key management mode forbidden.');
                }
            }
        }

        return $mode;
    }

    /**
     * @param string $mode1
     * @param string $mode2
     *
     * @return bool
     */
    private function areKeyManagementModeAuthorized($mode1, $mode2)
    {
        if ($mode1 > $mode2) {
            $temp = $mode1;
            $mode1 = $mode2;
            $mode2 = $temp;
        }
        switch ($mode1.$mode2) {
            case 'encenc':
            case 'encwrap':
            case 'wrapwrap':
            case 'agreeagree':
            case 'dirdir':
                return true;
            default:
                return false;
        }
    }

    /**
     * @param array $complete_header
     *
     * @return string
     */
    private function getKeyManagementMode2($complete_header)
    {
        $key_encryption_algorithm = $this->getKeyEncryptionAlgorithm($complete_header);

        if ($key_encryption_algorithm instanceof KeyEncryptionInterface) {
            return 'enc';
        } elseif ($key_encryption_algorithm instanceof KeyAgreementWrappingInterface) {
            return 'wrap';
        } elseif ($key_encryption_algorithm instanceof KeyAgreementInterface) {
            return 'agree';
        } elseif ($key_encryption_algorithm instanceof DirectEncryptionInterface) {
            return 'dir';
        } else {
            throw new \RuntimeException('Unable to get key management mode.');
        }
    }

    /**
     * @param string                                        $key_management_mode
     * @param \Jose\Object\EncryptionInstructionInterface[] $instructions
     * @param array                                         $protected_header
     * @param array                                         $unprotected_header
     * @param int                                           $cek_size
     *
     * @return string
     */
    private function determineCEK($key_management_mode, array $instructions, $protected_header, $unprotected_header, $cek_size)
    {
        switch ($key_management_mode) {
            case 'enc':
            case 'wrap':
                return $this->createCEK($cek_size);
            case 'dir':
                return $this->getDirectKey($instructions, $protected_header, $unprotected_header);
            case 'agree':
                return $this->getAgreementKey($instructions, $protected_header, $unprotected_header, $cek_size);
            default:
                throw new \RuntimeException('Unable to get CEK (unsupported key management mode).');
        }
    }

    /**
     * @param \Jose\Object\EncryptionInstructionInterface[] $instructions
     * @param array                                         $protected_header
     * @param array                                         $unprotected_header
     *
     * @return string
     */
    private function getDirectKey(array $instructions, $protected_header, $unprotected_header)
    {
        $cek = null;
        foreach ($instructions as $instruction) {
            $recipient_header = $instruction->getRecipientUnprotectedHeader();
            $complete_header = array_merge($protected_header, $unprotected_header, $recipient_header);

            $key_encryption_algorithm = $this->getKeyEncryptionAlgorithm($complete_header);
            if (!$key_encryption_algorithm instanceof DirectEncryptionInterface) {
                throw new \RuntimeException('The key encryption algorithm is not an instance of DirectEncryptionInterface');
            }

            $temp = $key_encryption_algorithm->getCEK($instruction->getRecipientKey(), $complete_header);
            if (null === $cek) {
                $cek = $temp;
            } else {
                if ($cek !== $temp) {
                    throw new \RuntimeException('Foreign CEK forbidden using direct key.');
                }
            }
        }

        return $cek;
    }

    /**
     * @param \Jose\Object\EncryptionInstructionInterface[] $instructions
     * @param array                                         $protected_header
     * @param array                                         $unprotected_header
     * @param int                                           $cek_size
     *
     * @return string
     */
    private function getAgreementKey(array $instructions, $protected_header, $unprotected_header, $cek_size)
    {
        $cek = null;
        foreach ($instructions as $instruction) {
            $recipient_header = $instruction->getRecipientUnprotectedHeader();
            $complete_header = array_merge($protected_header, $unprotected_header, $recipient_header);

            $key_encryption_algorithm = $this->getKeyEncryptionAlgorithm($complete_header);

            if (!$key_encryption_algorithm instanceof KeyAgreementInterface) {
                throw new \RuntimeException('The key encryption algorithm is not an instance of KeyAgreementInterface');
            }

            if (null === $instruction->getSenderKey()) {
                throw new \RuntimeException('The sender key must be set using Key Agreement or Key Agreement with Wrapping algorithms.');
            }
            $additional_header_values = [];
            $temp = $key_encryption_algorithm->getAgreementKey($cek_size, $instruction->getSenderKey(), $instruction->getRecipientKey(), $complete_header, $additional_header_values);
            if (null === $cek) {
                $cek = $temp;
            } else {
                if ($cek !== $temp) {
                    throw new \RuntimeException('Foreign CEK forbidden using direct key agreement.');
                }
            }
        }

        return $cek;
    }

    /**
     * @param string $payload
     * @param string $method
     */
    private function compressPayload(&$payload, $method = null)
    {
        if (null !== $method) {
            $compression_method = $this->getCompressionMethod($method);
            $payload = $compression_method->compress($payload);
            if (!is_string($payload)) {
                throw new \RuntimeException('Compression failed.');
            }
        }
    }

    /**
     * @param \Jose\Object\EncryptionInstructionInterface[] $instructions
     * @param array                                         $protected_header
     * @param array                                         $unprotected_header
     *
     * @return string
     */
    private function findCompressionMethod(array $instructions, $protected_header, $unprotected_header)
    {
        $method = null;
        $first = true;
        foreach ($instructions as $instruction) {
            $recipient_header = $instruction->getRecipientUnprotectedHeader();
            $complete_header = array_merge($protected_header, $unprotected_header, $recipient_header);
            if ($first) {
                if (array_key_exists('zip', $complete_header)) {
                    $method = $complete_header['zip'];
                }
                $first = null;
            } else {
                if (array_key_exists('zip', $complete_header) && $method !== $complete_header['zip']) {
                    throw new \RuntimeException('Foreign compression method forbidden');
                }
            }
        }

        return $method;
    }

    /**
     * @param string $method
     *
     * @return \Jose\Compression\CompressionInterface
     */
    private function getCompressionMethod($method)
    {
        $compression_method = $this->getCompressionManager()->getCompressionAlgorithm($method);
        if (null === $compression_method) {
            throw new \RuntimeException(sprintf('Compression method "%s" not supported', $method));
        }

        return $compression_method;
    }

    /**
     * @param string $serialization
     *
     * @throws \InvalidArgumentException
     */
    protected function checkSerializationMode($serialization)
    {
        if (!in_array($serialization, JSONSerializationModes::getSupportedSerializationModes())) {
            throw new \InvalidArgumentException(sprintf('The serialization method "%s" is not supported.', $serialization));
        }
    }

    /**
     * @param \Jose\Object\EncryptionInstructionInterface[] $instructions
     * @param string                                        $serialization
     */
    private function checkInstructions(array $instructions, $serialization)
    {
        if (empty($instructions)) {
            throw new \InvalidArgumentException('No instruction.');
        }
        foreach ($instructions as $instruction) {
            if (!$instruction instanceof EncryptionInstructionInterface) {
                throw new \InvalidArgumentException('Bad instruction. Must implement EncryptionInstructionInterface.');
            }
            if (!empty($instruction->getRecipientUnprotectedHeader()) && JSONSerializationModes::JSON_COMPACT_SERIALIZATION === $serialization) {
                throw new \InvalidArgumentException('Cannot create Compact Json Serialization representation: recipient unprotected header cannot be kept');
            }
        }
    }

    /**
     * @param array $complete_header
     *
     * @return \Jose\Algorithm\KeyEncryption\DirectEncryptionInterface|\Jose\Algorithm\KeyEncryption\KeyEncryptionInterface|\Jose\Algorithm\KeyEncryption\KeyAgreementInterface|\Jose\Algorithm\KeyEncryption\KeyAgreementWrappingInterface
     */
    private function getKeyEncryptionAlgorithm($complete_header)
    {
        if (!array_key_exists('alg', $complete_header)) {
            throw new \InvalidArgumentException('Parameter "alg" is missing.');
        }
        $key_encryption_algorithm = $this->getJWAManager()->getAlgorithm($complete_header['alg']);
        foreach ([
                     '\Jose\Algorithm\KeyEncryption\DirectEncryptionInterface',
                     '\Jose\Algorithm\KeyEncryption\KeyEncryptionInterface',
                     '\Jose\Algorithm\KeyEncryption\KeyAgreementInterface',
                     '\Jose\Algorithm\KeyEncryption\KeyAgreementWrappingInterface',
                 ] as $class) {
            if ($key_encryption_algorithm instanceof $class) {
                return $key_encryption_algorithm;
            }
        }
        throw new \RuntimeException(sprintf('The key encryption algorithm "%s" is not supported or not a key encryption algorithm instance.', $complete_header['alg']));
    }

    /**
     * @param \Jose\Object\EncryptionInstructionInterface[] $instructions
     * @param array                                         $protected_header
     * @param array                                         $unprotected_header
     *
     * @return \Jose\Algorithm\ContentEncryption\ContentEncryptionInterface
     */
    private function getContentEncryptionAlgorithm(array $instructions, array $protected_header = [], array $unprotected_header = [])
    {
        $algorithm = null;
        foreach ($instructions as $instruction) {
            $recipient_header = $instruction->getRecipientUnprotectedHeader();
            $complete_header = array_merge($protected_header, $unprotected_header, $recipient_header);
            if (!array_key_exists('enc', $complete_header)) {
                throw new \InvalidArgumentException('Parameter "enc" is missing.');
            }
            if (null === $algorithm) {
                $algorithm = $complete_header['enc'];
            } else {
                if ($algorithm !== $complete_header['enc']) {
                    throw new \InvalidArgumentException('Foreign "enc" parameter forbidden.');
                }
            }
        }

        $content_encryption_algorithm = $this->getJWAManager()->getAlgorithm($algorithm);
        if (!$content_encryption_algorithm instanceof ContentEncryptionInterface) {
            throw new \RuntimeException(sprintf('The algorithm "%s" is not enabled or does not implement ContentEncryptionInterface.', $algorithm));
        }

        return $content_encryption_algorithm;
    }

    /**
     * @param int $size
     *
     * @return string
     */
    private function createCEK($size)
    {
        return $this->generateRandomString($size / 8);
    }

    /**
     * @param int $size
     *
     * @return string
     */
    private function createIV($size)
    {
        return $this->generateRandomString($size / 8);
    }

    /**
     * @param int $length
     *
     * @return string
     */
    private function generateRandomString($length)
    {
        if (function_exists('random_bytes')) {
            return random_bytes($length);
        } else {
            return openssl_random_pseudo_bytes($length);
        }
    }
}
