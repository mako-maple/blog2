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

/**
 * JSON Serialization Modes.
 */
final class JSONSerializationModes
{
    const JSON_SERIALIZATION = 'JSON Serialization';
    const JSON_COMPACT_SERIALIZATION = 'JSON Compact Serialization';
    const JSON_FLATTENED_SERIALIZATION = 'JSON Flattened Serialization';

    public static function getSupportedSerializationModes()
    {
        return [
            self::JSON_COMPACT_SERIALIZATION,
            self::JSON_FLATTENED_SERIALIZATION,
            self::JSON_SERIALIZATION,
        ];
    }
}
