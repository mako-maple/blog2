<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace Jose\Payload;

use Jose\Object\JWKSet;
use Jose\Object\JWKSetInterface;

/**
 * Trait used to convert payload.
 */
final class JWKSetConverter implements PayloadConverterInterface
{
    /**
     * {@inheritdoc}
     */
    public function isPayloadToStringSupported(array $header, $payload)
    {
        return $payload instanceof JWKSetInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function isStringToPayloadSupported(array $header, $content)
    {
        return array_key_exists('cty', $header) && $header['cty'] === 'jwkset+json';
    }

    /**
     * {@inheritdoc}
     */
    public function convertPayloadToString(array &$header, $payload)
    {
        $header['cty'] = 'jwkset+json';

        return json_encode($payload);
    }

    /**
     * {@inheritdoc}
     */
    public function convertStringToPayload(array $header, $content)
    {
        $jwk = json_decode($content, true);
        if (!is_array($jwk) || !array_key_exists('keys', $jwk)) {
            throw new \InvalidArgumentException('The content type claims content is a JWKSet, but cannot be converted into JWKSet');
        }

        return new JWKSet($jwk);
    }
}
