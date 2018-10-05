<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace Jose\Util;

use Jose\JSONSerializationModes;

final class Converter
{
    /**
     * This function will try to convert JWS/JWE from a serialization mode into an other.
     * It always returns an array:.
     *
     * @param array|string $input The JWS/JWE to convert
     * @param string       $mode  Output mode
     *
     * @return string|string[]
     */
    public static function convert($input, $mode)
    {
        $prepared = self::getPreparedInput($input);
        switch ($mode) {
            case JSONSerializationModes::JSON_SERIALIZATION:
                return json_encode($prepared);
            case JSONSerializationModes::JSON_FLATTENED_SERIALIZATION:
                return self::fromSerializationToFlattenedSerialization($prepared);
            case JSONSerializationModes::JSON_COMPACT_SERIALIZATION:
                return self::fromSerializationToCompactSerialization($prepared);
            default:
                throw new \InvalidArgumentException(sprintf('The serialization method "%s" is not supported.', $mode));
        }
    }

    /**
     * This function will merge JWS or JWE. The result is always represents a JWS or JWE Json Serialization.
     * It accepts multiple arguments.
     *
     * @return array
     */
    public static function merge()
    {
        $inputs = [];

        //We convert all parameters into Json Serialization
        foreach (func_get_args() as $arg) {
            $inputs[] = json_decode(self::convert($arg, JSONSerializationModes::JSON_SERIALIZATION), true);
        }

        if (empty($inputs)) {
            throw new \InvalidArgumentException('Nothing to merge');
        }
        //We verify there is only JWS or JWE
        $type = null;
        foreach ($inputs as $input) {
            if (null === $type) {
                $type = self::getType($input);
            } else {
                $current = self::getType($input);
                if ($current !== $type) {
                    throw new \InvalidArgumentException('You cannot merge JWS and JWE');
                }
            }
        }
        switch ($type) {
            case 'JWS':
                return json_encode(self::mergeJWS($inputs));
            case 'JWE':
                return json_encode(self::mergeJWE($inputs));
            default:
                throw new \InvalidArgumentException('Unsupported input type');
        }
    }

    /**
     * @param array $inputs
     *
     * @return array
     */
    private static function mergeJWS($inputs)
    {
        $payload = null;
        //We verify if all common information are identical
        foreach ($inputs as $input) {
            if (null === $payload && array_key_exists('payload', $input)) {
                $payload = $input['payload'];
            } elseif (null !== $payload && array_key_exists('payload', $input)) {
                if ($payload !== $input['payload']) {
                    throw new \InvalidArgumentException('Unable to merge: a payload is not identical with other inputs');
                }
            }
        }
        //All good!
        $result = [];
        if (null !== $payload) {
            $result['payload'] = $payload;
        }
        $result['signatures'] = self::mergeSignatures($inputs);

        return $result;
    }

    /**
     * @param array $inputs
     *
     * @return array
     */
    private static function mergeSignatures($inputs)
    {
        $result = [];
        foreach ($inputs as $input) {
            foreach ($input['signatures'] as $recipient) {
                $temp = [];
                foreach (['header', 'protected', 'signature'] as $key) {
                    if (array_key_exists($key, $recipient)) {
                        $temp[$key] = $recipient[$key];
                    }
                }
                $result[] = $temp;
            }
        }

        return $result;
    }

    /**
     * @param array $inputs
     *
     * @return array
     */
    private static function mergeJWE($inputs)
    {
        foreach (['ciphertext', 'protected', 'unprotected', 'iv', 'aad', 'tag'] as $key) {
            $$key = null;
        }
        foreach ($inputs as $input) {
            foreach (['ciphertext', 'protected', 'unprotected', 'iv', 'aad', 'tag'] as $key) {
                if (null === $$key && array_key_exists($key, $input)) {
                    $$key = $input[$key];
                } elseif (null !== $$key && array_key_exists($key, $input)) {
                    if ($$key !== $input[$key]) {
                        throw new \InvalidArgumentException('Unable to merge: parameter "%s" is not identical with other inputs');
                    }
                }
            }
        }
        //All good!
        $result = [];
        foreach (['ciphertext', 'protected', 'unprotected', 'iv', 'aad', 'tag'] as $key) {
            if (null !== $$key) {
                $result[$key] = $$key;
            }
        }
        $result['recipients'] = self::mergeRecipients($inputs);

        return $result;
    }

    /**
     * @param array $inputs
     *
     * @return array
     */
    private static function mergeRecipients($inputs)
    {
        $result = [];
        foreach ($inputs as $input) {
            foreach ($input['recipients'] as $recipient) {
                $temp = [];
                foreach (['header', 'encrypted_key'] as $key) {
                    if (array_key_exists($key, $recipient)) {
                        $temp[$key] = $recipient[$key];
                    }
                }
                $result[] = $temp;
            }
        }

        return $result;
    }

    /**
     * @param array $input
     *
     * @return string|string[]
     */
    private static function fromSerializationToFlattenedSerialization($input)
    {
        if (array_key_exists('signatures', $input)) {
            return self::fromSerializationSignatureToFlattenedSerialization($input);
        }

        return self::fromSerializationRecipientToFlattenedSerialization($input);
    }

    /**
     * @param array $input
     *
     * @return string|string[]
     */
    private static function fromSerializationSignatureToFlattenedSerialization($input)
    {
        $signatures = [];
        foreach ($input['signatures'] as $signature) {
            $temp = [];
            if (!empty($input['payload'])) {
                $temp['payload'] = $input['payload'];
            }
            $temp['signature'] = $signature['signature'];

            foreach (['protected', 'header'] as $key) {
                if (array_key_exists($key, $signature)) {
                    $temp[$key] = $signature[$key];
                }
            }
            $signatures[] = json_encode($temp);
        }
        if (1 === count($signatures)) {
            $signatures = current($signatures);
        }

        return $signatures;
    }

    /**
     * @param array $input
     *
     * @return string|string[]
     */
    private static function fromSerializationRecipientToFlattenedSerialization($input)
    {
        $recipients = [];
        foreach ($input['recipients'] as $recipient) {
            $temp = [];
            foreach (['ciphertext', 'protected', 'unprotected', 'iv', 'aad', 'tag'] as $key) {
                if (array_key_exists($key, $input)) {
                    $temp[$key] = $input[$key];
                }
            }
            foreach (['header', 'encrypted_key'] as $key) {
                if (array_key_exists($key, $recipient)) {
                    $temp[$key] = $recipient[$key];
                }
            }
            $recipients[] = json_encode($temp);
        }
        if (1 === count($recipients)) {
            $recipients = current($recipients);
        }

        return $recipients;
    }

    /**
     * @param array $input
     *
     * @return string|string[]
     */
    private static function fromSerializationToCompactSerialization($input)
    {
        if (array_key_exists('signatures', $input)) {
            return self::fromSerializationSignatureToCompactSerialization($input);
        }

        return self::fromSerializationRecipientToCompactSerialization($input);
    }

    /**
     * @param array $input
     *
     * @return string|string[]
     */
    private static function fromSerializationSignatureToCompactSerialization($input)
    {
        $signatures = [];
        foreach ($input['signatures'] as $signature) {
            if (!array_key_exists('protected', $signature)) {
                throw new \InvalidArgumentException("Cannot convert into Compact Json Serialization: 'protected' parameter is missing");
            }
            if (array_key_exists('header', $signature)) {
                throw new \InvalidArgumentException("Cannot convert into Compact Json Serialization: 'header' parameter cannot be kept");
            }
            $temp = [
                $signature['protected'],
                isset($input['payload']) ? $input['payload'] : '',
                $signature['signature'],
            ];
            $signatures[] = implode('.', $temp);
        }
        if (1 === count($signatures)) {
            $signatures = current($signatures);
        }

        return $signatures;
    }

    /**
     * @param array $input
     *
     * @return string|string[]
     */
    private static function fromSerializationRecipientToCompactSerialization($input)
    {
        $recipients = [];
        foreach ($input['recipients'] as $recipient) {
            if (array_key_exists('header', $recipient)) {
                throw new \InvalidArgumentException("Cannot convert into Compact Json Serialization: 'header' parameter cannot be kept");
            }
            if (!array_key_exists('protected', $input)) {
                throw new \InvalidArgumentException("Cannot convert into Compact Json Serialization: 'protected' parameter is missing");
            }
            foreach (['unprotected', 'aad'] as $key) {
                if (array_key_exists($key, $input)) {
                    throw new \InvalidArgumentException(sprintf("Cannot convert into Compact Json Serialization: '%s' parameter cannot be kept", $key));
                }
            }
            $temp = [
                $input['protected'],
                array_key_exists('encrypted_key', $recipient) ? $recipient['encrypted_key'] : '',
                array_key_exists('iv', $input) ? $input['iv'] : '',
                $input['ciphertext'],
                array_key_exists('tag', $input) ? $input['tag'] : '',
            ];
            $recipients[] = implode('.', $temp);
        }
        if (1 === count($recipients)) {
            $recipients = current($recipients);
        }

        return $recipients;
    }

    /**
     * @param array $input
     *
     * @return string
     */
    public static function getType(array $input)
    {
        if (array_key_exists('signatures', $input)) {
            return 'JWS';
        } elseif (array_key_exists('ciphertext', $input)) {
            return 'JWE';
        } else {
            throw new \RuntimeException('Unsupported input');
        }
    }

    /**
     * @param $input
     *
     * @return array
     */
    private static function fromFlattenedSerializationRecipientToSerialization($input)
    {
        $recipient = [];
        foreach (['header', 'encrypted_key'] as $key) {
            if (array_key_exists($key, $input)) {
                $recipient[$key] = $input[$key];
            }
        }
        $recipients = [
            'ciphertext' => $input['ciphertext'],
            'recipients' => [$recipient],
        ];
        foreach (['ciphertext', 'protected', 'unprotected', 'iv', 'aad', 'tag'] as $key) {
            if (array_key_exists($key, $input)) {
                $recipients[$key] = $input[$key];
            }
        }

        return $recipients;
    }

    /**
     * @param $input
     *
     * @return array
     */
    private static function fromFlattenedSerializationSignatureToSerialization($input)
    {
        $signature = [
            'signature' => $input['signature'],
        ];
        foreach (['protected', 'header'] as $key) {
            if (array_key_exists($key, $input)) {
                $signature[$key] = $input[$key];
            }
        }

        $temp = [];
        if (!empty($input['payload'])) {
            $temp['payload'] = $input['payload'];
        }
        $temp['signatures'] = [$signature];

        return $temp;
    }

    /**
     * @param $input
     *
     * @return array
     */
    private static function fromCompactSerializationToSerialization($input)
    {
        $parts = explode('.', $input);
        switch (count($parts)) {
            case 3:
                return self::fromCompactSerializationSignatureToSerialization($parts);
            case 5:
                return self::fromCompactSerializationRecipientToSerialization($parts);
            default:
                throw new \InvalidArgumentException('Unsupported input');
        }
    }

    /**
     * @param array $parts
     *
     * @return array
     */
    private static function fromCompactSerializationRecipientToSerialization(array $parts)
    {
        $recipient = [];
        if (!empty($parts[1])) {
            $recipient['encrypted_key'] = $parts[1];
        }

        $recipients = [
            'recipients' => [$recipient],
        ];
        foreach ([3 => 'ciphertext', 0 => 'protected', 2 => 'iv', 4 => 'tag'] as $part => $key) {
            if (!empty($parts[$part])) {
                $recipients[$key] = $parts[$part];
            }
        }

        return $recipients;
    }

    /**
     * @param array $parts
     *
     * @return array
     */
    private static function fromCompactSerializationSignatureToSerialization(array $parts)
    {
        $temp = [];

        if (!empty($parts[1])) {
            $temp['payload'] = $parts[1];
        }
        $temp['signatures'] = [[
            'protected' => $parts[0],
            'signature' => $parts[2],
        ]];

        return $temp;
    }

    /**
     * @param string|array $input
     *
     * @return array
     */
    private static function getPreparedInput($input)
    {
        if (is_array($input)) {
            if (array_key_exists('signatures', $input) || array_key_exists('recipients', $input)) {
                return $input;
            }
            if (array_key_exists('signature', $input)) {
                return self::fromFlattenedSerializationSignatureToSerialization($input);
            }
            if (array_key_exists('ciphertext', $input)) {
                return self::fromFlattenedSerializationRecipientToSerialization($input);
            }
        } elseif (is_string($input)) {
            $json = json_decode($input, true);
            if (is_array($json)) {
                return self::getPreparedInput($json);
            }

            return self::fromCompactSerializationToSerialization($input);
        }
        throw new \InvalidArgumentException('Unsupported input');
    }
}
