<?php

namespace Waska\LaravelUuid\Generator;

use Exception;

class Uuid
{
    /**
     * This static public function generates Uuid (Universal Unique Identifier) string.
     *
     * @param int $version. Uuid version to generate.
     * @param string $name. String which the uuid is generating for (for v3 and v5).
     * @param string $namespace. Valid uuid string (for v3 and v5).
     *
     * @return String
     */
    public static function get($version = null, $name = null, $namespace = null): String
    {
        $version = $version ?: config('waska.uuid.default_version');

        switch ($version) {
            case 3:
                return static::generateV3($namespace, $name);
            case 4:
                return static::generateV4();
            case 5:
                return static::generateV5($namespace, $name);
            default:
                throw new Exception("Uuid v" . $version . " is not supported yet.");
        }
    }

    /**
     * From: http://php.net/manual/en/function.uniqid.php#94959
     *
     * This static protected function generates named based Uuid (Universal Unique Identifier) v3 string.
     * Given the same namespace and name, the output is always the same.
     *
     * @param string $namespace. Valid uuid string.
     * @param string $name. String which the uuid is generating for.
     *
     * @return String
     */
    protected static function generateV3($namespace = null, $name)
    {
        $namespace = $namespace ?: config('waska.uuid.v3_default_namespace');

        return self::generateNameBased($namespace, $name, 'md5', 0x3000);
    }

    /**
     * From: http://php.net/manual/en/function.uniqid.php#94959
     *
     * This static protected function generates pseudo-random Uuid (Universal Unique Identifier) v4 string.
     *
     * @return String
     */
    protected static function generateV4(): String
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),

            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    /**
     * From: http://php.net/manual/en/function.uniqid.php#94959
     *
     * This static protected function generates named based Uuid (Universal Unique Identifier) v5 string.
     * Given the same namespace and name, the output is always the same.
     *
     * @param string $namespace. Valid uuid string.
     * @param string $name. String which the uuid is generating for.
     *
     * @return String
     */
    protected static function generateV5($namespace = null, $name)
    {
        $namespace = $namespace ?: config('waska.uuid.v5_default_namespace');

        return self::generateNameBased($namespace, $name, 'sha1', 0x5000);
    }

    /**
     * From: http://php.net/manual/en/function.uniqid.php#94959
     *
     * This static protected function checks if the given string is valid uuid.
     *
     * @param string $uuid. Uuid string to check.
     *
     * @return Boolean
     */
    protected static function isValid($uuid)
    {
        return preg_match('/^\{?[0-9a-f]{8}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?' .
            '[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i', $uuid) === 1;
    }

    protected static function generateNameBased($namespace, $name, $hash_function, $holder)
    {
        if (!self::isValid($namespace)) {
            throw new Exception("Invalid namespace. The string must be valid Uuid string.");
        }

        // Get hexadecimal components of namespace
        $nhex = str_replace(array('-', '{', '}'), '', $namespace);

        // Binary Value
        $nstr = '';

        // Convert Namespace UUID to bits
        for ($i = 0; $i < strlen($nhex); $i += 2) {
            $nstr .= chr(hexdec($nhex[$i] . $nhex[$i + 1]));
        }

        // Calculate hash value
        $hash = $hash_function($nstr . $name);

        return sprintf('%08s-%04s-%04x-%04x-%12s',

            // 32 bits for "time_low"
            substr($hash, 0, 8),

            // 16 bits for "time_mid"
            substr($hash, 8, 4),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 5
            (hexdec(substr($hash, 12, 4)) & 0x0fff) | $holder,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,

            // 48 bits for "node"
            substr($hash, 20, 12)
        );
    }
}
