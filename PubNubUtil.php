<?php

/**
  *  PubNubUtil 助手类
  *    (人的自欺能力 很强 有些东西是不能自欺的 如饿。)
  *     (好生活的感觉 是可以自欺的)
  *     (好 不就是相对的吗？)
        (人可以干自己觉得坏的事)
        (好生活与我们的自由有关系 不是如饿这么绝对)
  *     
        所有的方法皆是静态方法 static
        
        buildUrl - 建造 URL
        urlWrite - encodes the given string according to >> RFC 3986
        
        urlEncode - rawurlencode()
        
        writeValueAsString - 将格式化为书写字符串格式
        
        joinQuery - 将查询字符串拼接一起
        $keyValues[] = [];
        foreach($queryElements as $key => $val) {
            $keyValues[] = "$key=$val":
        }
        
        join('&', $keyValues);
        
        joinChannels - 将 urlencode($channel) 拼接
        
        joinItems - 数组数据拼接
        
        extendArray - 数组扩展，支持两个数组合并，或逗号(,)分隔的字符串
        
        spitItems - 字符串拆分
        
        uuid - 存在 com_create_guid 则调用，或则使用 sprintf() 输出长度为 5 * 8 的 16 进制字符串
        
        preparePamParams - 
        
        pamEncode - 
        
        signSha256 - 以 sha256 签名
        
        fetchPamPermissionsFrom - 
        
        isAssoc -  是否为关联数组(array_keys($arr) !== range(0, count($arr) - 1))
      
        stringEndsWith - 判断字符串的结尾(substr_compare($man, $subject, $offest, $length))
  *
  *     
  */
namespace PubNub;

use PubNub\Exceptions\PubNubBuildRequestException;


class PubNubUtil
{
    /**
     * WARNING: query elements should be already encoded inside Endpoint::customParams() method if required
     *
     * @param string $basePath generated by BasePathManager
     * @param string $path
     * @param string[string] $params query elements
     * @return string url
     */
    public static function buildUrl($basePath, $path, $params)
    {
        return $basePath . $path . "?" . static::joinQuery($params);
    }

    public static function urlWrite($value)
    {
        // urlWrite
        return static::urlEncode(static::writeValueAsString($value));
    }

    public static function urlEncode($value)
    {
        // rawurlencode
        return rawurlencode($value);
    }

    public static function writeValueAsString($value)
    {
        if (gettype($value) == 'string') {
            return "\"" . $value . "\"";
        } else {
        
            $res = json_encode($value);

            if (json_last_error()) {
                throw new PubNubBuildRequestException("Value serialization error: " . json_last_error_msg());
            }

            return $res;
        }
    }

    public static function joinQuery($queryElements)
    {
        $keyValues = [];

        foreach ($queryElements as $key => $val) {
            $keyValues[] = "$key=$val";
        }

        return join("&", $keyValues);
    }

    /**
     * @param array $channels
     * @return string
     */
    public static function joinChannels($channels)
    {
        if (count($channels) == 0) {
            return ",";
        } else {
            $encodedChannels = [];

            foreach ($channels as $ch) {
                $encodedChannels[] = urlencode($ch);
            }

            return join(",", $encodedChannels);
        }
    }

    public static function joinItems($items)
    {
        return join(",", $items);
    }


    /**
     * @param $existingItems
     * @param $newItems
     * @return array
     */
    public static function extendArray($existingItems, $newItems)
    {
        if (is_array($newItems)) {
            return array_merge($existingItems, $newItems);
        } else {
            return array_merge($existingItems, static::splitItems($newItems));
        }
    }

    /**
     * @param string $itemsString
     * @return array
     */
    public static function splitItems($itemsString)
    {
        if (strlen($itemsString) == 0) {
            return [];
        } else {
            return explode(",", $itemsString);
        }
    }

    public static function uuid()
    {
        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        } else {
            return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535),
                mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535),
                mt_rand(0, 65535));
        }
    }

    public static function preparePamParams($params)
    {
        ksort($params);

        $sortedParams = $params;
        $stringifiedArguments = "";
        $index = 0;

        foreach ($sortedParams as $key => $value) {
            if (is_bool($value)) {
                $value = $value ? "true" : "false";
            }

            // $index == count($sortedParams) - 1 就是数组中最后一个元素
            if ($index === count($sortedParams) - 1) {
                $stringifiedArguments .= ($key . "=" . self::pamEncode($value));
            } else {
                $stringifiedArguments .= ($key . "=" . self::pamEncode($value) . "&");
            }

            $index++;
        }

        return $stringifiedArguments;
    }

    public static function pamEncode($url)
    {
        // rawurlencode($string) 
        // encodes the given string according to RFC 3986
        // returns a string in which non-alphanumeric characters except -_.~
        // have been replaced with a percent()% sign followed by two hex digits.
        $encoded = self::urlEncode($url);

        /*
            string|array str_replace(mixed $search, mixed $replace, mixed $subject[, int $count])
        */
        if (!empty($encoded)) {
            $encoded = str_replace("*", "%2A", $encoded);
            $encoded = str_replace("!", "%21", $encoded);
            $encoded = str_replace("'", "%27", $encoded);
            $encoded = str_replace("(", "%28", $encoded);
            $encoded = str_replace(")", "%29", $encoded);
            $encoded = str_replace("[", "%5B", $encoded);
            $encoded = str_replace("]", "%5D", $encoded);
            $encoded = str_replace("~", "%7E", $encoded);
        }

        return $encoded;
    }

    public static function signSha256($secret, $signInput)
    {
        // strtr - Translate characters or replace substrings
        $result = strtr(base64_encode(hash_hmac(
            'sha256',
            utf8_encode($signInput),
            utf8_encode($secret),
            true
        )), '+/', '-_');

        return $result;
    }

    public static function fetchPamPermissionsFrom($jsonInput)
    {
        $r = null;
        $w = null;
        $m = null;
        $ttl = null;

        if (array_key_exists('r', $jsonInput)) {
            $r = $jsonInput['r'] === 1;
        }

        if (array_key_exists('w', $jsonInput)) {
            $w = $jsonInput['w'] === 1;
        }

        if (array_key_exists('m', $jsonInput)) {
            $m = $jsonInput['m'] === 1;
        }

        if (array_key_exists('ttl', $jsonInput)) {
            $ttl = (int) $jsonInput['ttl'];
        }

        return [$r, $w, $m, $ttl];
    }

    public static function isAssoc($arr)
    {
        if (!is_array($arr)) return false;

        return array_keys($arr) !== range(0, count($arr) -1 );
    }

    /**
     * Tests if given string ends with the specified suffix.
     *
     * @param string $string
     * @param string $suffix
     * @return bool
     */
    public static function stringEndsWith($string, $suffix)
    {
        $str_len = strlen($string);
        $suffix_len = strlen($suffix);
        if ($suffix_len > $str_len) return false;
        return substr_compare($string, $suffix, $str_len - $suffix_len, $suffix_len) === 0;
    }
}
