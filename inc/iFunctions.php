<?php

    function exec_background($cmd)
    {
        if (substr(php_uname(), 0, 7) == "Windows") {
            pclose(popen("start /B " . $cmd, "r"));
        } else {
            shell_exec($cmd);
        }
    }

    function tokenURLs($params)
    {
        $array["oauth_callback"] = "oob";
        $array["oauth_consumer_key"] = $params["consumer_key"];
        $array["oauth_nonce"] = md5(uniqid(mt_rand()));
        $array["oauth_signature_method"] = "HMAC-SHA1";
        $array["oauth_timestamp"] = time();
        $array["oauth_token"] = NULL;
        $array["oauth_verifier"] = NULL;
        $array["oauth_version"] = "1.0";
        $param["x_auth_mode"] = "client_auth";
        $param["x_auth_username"] = $params["username"];
        $param["x_auth_password"] = $params["password"];
        $param["interface"] = $params["185.106.22.".rand(131,137).""];
        $url = "https://api.twitter.com/oauth/access_token";
        $signature_array = $array;
        if (isset($param)) {
            foreach ($param as $key => $val) {
                $signature_array[$key] = $val;
            }
            ksort($signature_array);
        }
        $signature_base_string = "";
        foreach ($signature_array as $key => $val) {
            $signature_base_string .= $key . "=" . rawurlencode($val) . "&";
        }
        $signature_base_string = substr($signature_base_string, 0, -1);
        $signature_base_string = "POST&" . rawurlencode($url) . "&" . rawurlencode($signature_base_string);
        $signing_key = rawurlencode($params["consumer_secret"]) . "&";
        $array["oauth_signature"] = base64_encode(hash_hmac("sha1", $signature_base_string, $signing_key, TRUE));
        $http_header = "Authorization:OAuth ";
        foreach ($array as $key => $val) {
            $http_header .= $key . "=\"" . rawurlencode($val) . "\",";
        }
        $http_header = substr($http_header, 0, -1);

        if (isset($param)) {
            $url .= "?" . http_build_query($param);
        }
        return array("http_header" => $http_header, "url" => $url, "post" => $param);
    }

    function array_random($array)
    {
        return $array[array_rand($array)];
    }

    function randIP()
    {
        return "104.28.17." . rand(2, 254);
    }

    function random_string($length = 10)
    {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_"), 0, $length);
    }

    function array_random_mail()
    {
        $mails = array(
            "@mail.ru",
            "@yahoo.com",
            "@gmail.com",
            "@yandex.com",
            "@hotmail.com",
            "@outlook.com",
            "@windowslive.com"
        );
        return (random_string(20) . array_random($mails));

    }

    function fakeip()
    {
        return "104.28." . rand(1, 255) . "." . rand(1, 255);
        //return long2ip(mt_rand(0, 65537) * mt_rand(0, 65535));
    }

    function transliterateTurkishChars($inputText)
    {
        $search = array('ç', 'Ç', 'ğ', 'Ğ', 'ı', 'İ', 'ö', 'Ö', 'ş', 'Ş', 'ü', 'Ü');
        $replace = array('c', 'C', 'g', 'G', 'i', 'I', 'o', 'O', 's', 'S', 'u', 'U');
        $outputText = str_replace($search, $replace, $inputText);
        return $outputText;
    }

    function in_str($haystack, $needle)
    {
        $pos = strpos($haystack, $needle);
        if ($pos !== FALSE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function generateRandomString($size_of_random_string = 8)
    {

        $char_list_str = array_merge(range('a', 'z'), range('0', '9'));

        if ($size_of_random_string < 1) {
            return FALSE;
        }
        if ($size_of_random_string === 1) {
            return $char_list_str[array_rand($char_list_str, 1)];
        }

        $random_string = '';
        foreach (array_rand($char_list_str, $size_of_random_string) as $k) {
            $random_string .= $char_list_str[$k];
        }
        return $random_string;
    }

    function buildHttpQueryRaw($params)
    {
        $retval = '';
        foreach ((array)$params as $key => $value)
            $retval .= "{$key}={$value}&";
        $retval = substr($retval, 0, -1);
        return $retval;
    }

    function encode_rfc3986($string)
    {
        if (is_array($string)) {
            return array_map('encode_rfc3986', $string);
        } elseif (is_scalar($string))
            return str_ireplace(
                array('+', '%7E'), array(' ', '~'), rawurlencode($string)
            );
    }

    function random_str($length = 20)
    {
        $strs = array_merge(range("a", "z"), range("A", "Z"));
        $strs = array_merge($strs, range("0", "9"));
        $strs[] = "_";
        shuffle($strs);
        $strs = array_slice($strs, 0, $length);
        return implode("", $strs);
    }

    function random_number($length = 15)
    {
        $timestamp = time();
        $timestamp = str_split($timestamp);
        $numbers = array_merge(range("0", "9"), $timestamp);
        shuffle($numbers);
        $numbers = array_slice($numbers, 0, $length);
        return implode("", $numbers);
    }

    function remove_eol($content)
    {
        $r = array(
            "\r\n",
            "\r",
            "\n",
            "\t");
        for ($i = 2; $i < 100; $i++) {
            $r[] = str_repeat(' ', $i);
        }
        $content = str_replace($r, '', $content);
        $content = str_replace("> <", "><", $content);
        return $content;
    }

    function normalizeUrl($url = NULL)
    {
        $urlParts = parse_url($url);
        $scheme = strtolower($urlParts['scheme']);
        $host = strtolower($urlParts['host']);
        $port = isset($urlParts['port']) ? intval($urlParts['port']) : 0;
        $retval = strtolower($scheme) . '://' . strtolower($host);
        if (!empty($port) && (($scheme === 'http' && $port != 80) || ($scheme === 'https' && $port != 443)))
            $retval .= ":{$port}";
        $retval .= $urlParts['path'];
        if (!empty($urlParts['query'])) {
            $retval .= "?{$urlParts['query']}";
        }
        return $retval;
    }

    function gen_uuid()
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
