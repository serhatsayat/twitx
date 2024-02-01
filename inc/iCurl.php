<?php

    require 'iFunctions.php';

    function curl($opt, $ch = NULL, $is_close = TRUE)
    {
        $headers = array();
        $header_function = function ($ch, $header) use (&$headers) {
            $_header = trim($header);
            $colonPos = strpos($_header, ':');
            if ($colonPos > 0) {
                $name = strtolower(substr($_header, 0, $colonPos));
                $val = preg_replace('/^\W+/', '', substr($_header, $colonPos));
                $headers[$name] = $val;
            }
            return strlen($header);
        };
        $ch = isset($ch) ? $ch : curl_init();
        curl_setopt_array($ch, curl_get_options($opt));
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, $header_function);
        $content = curl_exec($ch);
        $obj = new stdClass();
        if (isset($headers["content-type"]) && in_str($headers["content-type"], 'application/json')) {
            $obj = (object)json_decode($content, FALSE, 512, JSON_BIGINT_AS_STRING);
        }
        $obj->http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $obj->http_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        if (isset($opt["header_out"]) && $opt["header_out"]) {
            $obj->http_header_out = curl_getinfo($ch, CURLINFO_HEADER_OUT);
        }
        if (isset($opt["header"]) && $opt["header"]) {
            $obj->http_headers = $headers;
        }
        if ($is_close) {
            curl_close($ch);
        }
        $obj->content = $content;
        return $obj;
    }

    function curl_api($opt, $ch = NULL, $is_close = TRUE)
    {
        $headers = array();
        $header_function = function ($ch, $header) use (&$headers) {
            $_header = trim($header);
            $colonPos = strpos($_header, ':');
            if ($colonPos > 0) {
                $name = strtolower(substr($_header, 0, $colonPos));
                $val = preg_replace('/^\W+/', '', substr($_header, $colonPos));
                $headers[$name] = $val;
            }
            return strlen($header);
        };
        $_opt = curl_get_api_options($opt);
        $_opt[CURLOPT_HEADERFUNCTION] = $header_function;
        $ch = isset($ch) ? $ch : curl_init();
        curl_setopt_array($ch, $_opt);
        $content = curl_exec($ch);
        $obj = new stdClass();
        if (isset($opt["function"]) && in_array(strtolower($opt["function"]), array("getaccesstoken", "getauthenticateurl", "getauthorizeurl", "getrequesttoken"))) {
            $function = strtolower($opt["function"]);
            switch ($function) {
                case "getauthenticateurl":
                case "getauthorizeurl":
                case "getrequesttoken":
                case "getaccesstoken":
                    parse_str($content, $result);
                    $obj = (object)$result;
                    break;
                default :
                    break;
            }
            switch ($function) {
                case "getauthenticateurl":
                    parse_str($content, $result);
                    $result = (object)$result;
                    if (!isset($opt["post"]))
                        $opt["post"] = NULL;
                    $params = $opt["post"] + array("oauth_token" => @$result->oauth_token);
                    $_url = 'https://api.twitter.com/oauth/authenticate?' . http_build_query($params, '', '&');
                    break;
                case "getauthorizeurl":
                    parse_str($content, $result);
                    $result = (object)$result;
                    if (!isset($opt["post"]))
                        $opt["post"] = NULL;
                    $params = $opt["post"] + array("oauth_token" => @$result->oauth_token);
                    $_url = 'https://api.twitter.com/oauth/authorize?' . http_build_query($params, '', '&');
                    break;
                default :
                    break;
            }
            if (isset($_url)) {
                return $_url;
            }
        }
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        $obj = new stdClass();
        if (isset($headers["content-type"]) && in_str($headers["content-type"], 'application/json')) {
            $obj = (object)json_decode($content, FALSE, 512, JSON_BIGINT_AS_STRING);
        }
        $obj->http_code = $code;
        $obj->http_url = $url;
        if (isset($opt["header_out"]) && $opt["header_out"]) {
            $obj->http_header_out = curl_getinfo($ch, CURLINFO_HEADER_OUT);
        }
        if (isset($opt["header"]) && $opt["header"]) {
            $obj->http_headers = $headers;
        }
        $obj->content = $content;
        if ($is_close) {
            curl_close($ch);
        }
        return $obj;
    }

    function multi_curl($opts, $callback = NULL, $rolling_window = 5)
    {
        $mh = curl_multi_init();
        $rolling_window = (sizeof($opts) < $rolling_window ? sizeof($opts) : $rolling_window);
        $result = array();
        $keys = array();
        $_keys = array();
        $i = 0;
        $chans = array();
        $headers = array();
        $header_function = function ($ch, $header) use (&$headers) {
            $_header = trim($header);
            $colonPos = strpos($_header, ':');
            if ($colonPos > 0) {
                $name = strtolower(substr($_header, 0, $colonPos));
                $val = preg_replace('/^\W+/', '', substr($_header, $colonPos));
                $headers[(string)$ch][$name] = $val;
            }
            return strlen($header);
        };
        foreach ($opts as $key => $opt) {
            if ($i < $rolling_window) {
                $_opt = curl_get_options($opt);
                $_opt[CURLOPT_HEADERFUNCTION] = $header_function;
                $chan = curl_init();
                curl_setopt_array($chan, $_opt);
                curl_multi_add_handle($mh, $chan);
                $keys[(string)$chan] = $key;
                $chans[$key] = $chan;
            } else {
                $_keys[] = $key;
            }
            $i++;
        }
        unset($i);
        do {
            while (($execStatus = curl_multi_exec($mh, $running)) === CURLM_CALL_MULTI_PERFORM) ;
            if ($execStatus != CURLM_OK) {
                break;
            }
            $add = FALSE;
            while ($done = curl_multi_info_read($mh, $remains)) {
                $chan = $done["handle"];
                $key = (string)$done["handle"];
                $key = $keys[$key];
                $content = curl_multi_getcontent($chan);
                $code = curl_getinfo($chan, CURLINFO_HTTP_CODE);
                $url = curl_getinfo($chan, CURLINFO_EFFECTIVE_URL);
                $header = @$headers[(string)$chan];
                $opt = $opts[$key];
                $obj = new stdClass();
                $code = curl_getinfo($chan, CURLINFO_HTTP_CODE);
                $url = curl_getinfo($chan, CURLINFO_EFFECTIVE_URL);
                if (isset($header["content-type"]) && in_str($header["content-type"], 'application/json')) {
                    $obj = (object)json_decode($content, FALSE, 512, JSON_BIGINT_AS_STRING);
                }
                $obj->http_code = $code;
                $obj->http_url = $url;
                if (isset($opt["header_out"]) && $opt["header_out"]) {
                    $obj->http_header_out = curl_getinfo($chan, CURLINFO_HEADER_OUT);
                }
                if (isset($opt["header"]) && $opt["header"]) {
                    $obj->http_headers = $header;
                }
                $obj->content = $content;
                curl_multi_remove_handle($mh, $chan);
                curl_close($chan);
                unset($opts[$key], $chans[$key], $content, $headers[(string)$done["handle"]], $keys[(string)$done["handle"]]);
                if (isset($callback)) {
                    if (is_callable($callback)) {
                        try {
                            if (stristr($key, "%%%%&%%%+%%%")) {
                                list($key,) = explode("%%%%&%%%+%%%", $key);
                            }
                            $r = call_user_func($callback, $obj, $key, $opt);
                        } catch (Exception $ex) {
                            try {
                                $r = call_user_func($callback, $obj, $key);
                            } catch (Exception $ex) {
                                try {
                                    $r = call_user_func($callback, $obj);
                                } catch (Exception $ex) {
                                    $r = call_user_func($callback);
                                }
                            }
                        }
                        if (isset($r)) {
                            if (is_array($r) && isset($r["url"])) {
                                $_key = NULL;
                                if (isset($r["key"])) {
                                    $_key = $r["key"];
                                    $opts[$_key] = $r;
                                } else {
                                    $opts[] = $r;
                                    end($opts);
                                    $_key = key($opts);
                                    reset($opts);
                                }
                                if (isset($_r["add_end"]) && $_r["add_end"]) {
                                    $_keys[] = $_key;
                                } else {
                                    array_splice($_keys, 0, 0, $_key);
                                }
                            } elseif (is_array($r) && count($r) > 0) {
                                if (isset($r["close_key"])) {
                                    foreach ($chans as $key => $chan) {
                                        $_key = $r["close_key"] . "%%%%&%%%+%%%";
                                        if (stristr($key, $_key)) {
                                            curl_multi_remove_handle($mh, $chan);
                                            @curl_close($chan);
                                        }
                                    }
                                } else {
                                    foreach ($r as $_r) {
                                        if (is_array($_r) && isset($_r["url"])) {
                                            $_key = NULL;
                                            if (isset($_r["key"])) {
                                                $_key = $_r["key"] . "%%%%&%%%+%%%" . md5(uniqid(rand(), TRUE) . microtime(TRUE));
                                                $opts[$_key] = $_r;
                                            } else {
                                                $opts[] = $_r;
                                                end($opts);
                                                $_key = key($opts);
                                                reset($opts);
                                            }
                                            if (isset($_r["add_end"]) && $_r["add_end"]) {
                                                $_keys[] = $_key;
                                            } else {
                                                array_splice($_keys, 0, 0, $_key);
                                            }
                                        }
                                    }
                                }
                            } elseif (!is_array($r) && isset($r) && $r == FALSE) {
                                foreach ($chans as $key => $chan) {
                                    curl_multi_remove_handle($mh, $chan);
                                    @curl_close($chan);
                                }
                                curl_multi_close($mh);
                                unset($keys, $_keys, $opts, $headers, $mh, $chans);
                                return;
                            }
                        }
                    } else {
                        $result[$key] = $d;
                    }
                } else {
                    $result[$key] = $d;
                }
                $_key = array_shift($_keys);
                if (isset($_key)) {
                    $opt = $opts[$_key];
                    $opt = curl_get_options($opt);
                    $opt[CURLOPT_HEADERFUNCTION] = $header_function;
                    $ch = curl_init();
                    curl_setopt_array($ch, $opt);
                    curl_multi_add_handle($mh, $ch);
                    $keys[(string)$ch] = $_key;
                    $chans[$_key] = $ch;
                    $add = TRUE;
                }
            }
            if ($running)
                curl_multi_select($mh, 10);
        } while ($running || $add);
        curl_multi_close($mh);
        return $result;
    }

    function multi_curl_api($opts, $callback = NULL, $rolling_window = 5)
    {
        $mh = curl_multi_init();
        $rolling_window = (sizeof($opts) < $rolling_window ? sizeof($opts) : $rolling_window);
        $result = array();
        $keys = array();
        $_keys = array();
        $i = 0;
        $chans = array();
        $headers = array();
        $header_function = function ($ch, $header) use (&$headers) {
            $_header = trim($header);
            $colonPos = strpos($_header, ':');
            if ($colonPos > 0) {
                $name = substr($_header, 0, $colonPos);
                $val = preg_replace('/^\W+/', '', substr($_header, $colonPos));
                $headers[(string)$ch][$name] = $val;
            }
            return strlen($header);
        };
        foreach ($opts as $key => $opt) {
            if ($i < $rolling_window) {
                $_opt = curl_get_api_options($opt);
                $_opt[CURLOPT_HEADERFUNCTION] = $header_function;
                $chan = curl_init();
                curl_setopt_array($chan, $_opt);
                curl_multi_add_handle($mh, $chan);
                $keys[(string)$chan] = $key;
                $chans[$key] = $chan;
            } else {
                $_keys[] = $key;
            }
            $i++;
        }
        do {
            while (($execStatus = curl_multi_exec($mh, $running)) === CURLM_CALL_MULTI_PERFORM) ;
            if ($execStatus != CURLM_OK) {
                break;
            }
            $add = FALSE;
            while ($done = curl_multi_info_read($mh)) {
                $chan = $done["handle"];
                $key = (string)$done["handle"];
                $key = $keys[$key];
                $content = curl_multi_getcontent($chan);
                $obj = new stdClass();
                $_url = NULL;
                $opt = $opts[$key];
                $header = @$headers[(string)$chan];
                if (isset($opt["function"]) && in_array(strtolower($opt["function"]), array("getaccesstoken", "getauthenticateurl", "getauthorizeurl", "getrequesttoken"))) {
                    $function = strtolower($opt["function"]);
                    switch ($function) {
                        case "getauthenticateurl":
                        case "getauthorizeurl":
                        case "getrequesttoken":
                        case "getaccesstoken":
                            parse_str($content, $result);
                            $obj = (object)$result;
                            break;
                        default :
                            break;
                    }
                    switch ($function) {
                        case "getauthenticateurl":
                            parse_str($content, $result);
                            $result = (object)$result;
                            if (!isset($opt["post"]))
                                $opt["post"] = NULL;
                            $params = $opt["post"] + array("oauth_token" => @$result->oauth_token);
                            $_url = 'https://api.twitter.com/oauth/authenticate?' . http_build_query($params, '', '&');
                            break;
                        case "getauthorizeurl":
                            parse_str($content, $result);
                            $result = (object)$result;
                            if (!isset($opt["post"]))
                                $opt["post"] = NULL;
                            $params = $opt["post"] + array("oauth_token" => @$result->oauth_token);
                            $_url = 'https://api.twitter.com/oauth/authorize?' . http_build_query($params, '', '&');
                            break;
                        default :
                            break;
                    }
                }
                if (isset($_url)) {
                    $obj = $_url;
                } else {
                    $code = curl_getinfo($chan, CURLINFO_HTTP_CODE);
                    $url = curl_getinfo($chan, CURLINFO_EFFECTIVE_URL);
                    if (isset($header["content-type"]) && in_str($header["content-type"], 'application/json')) {
                        $obj = (object)json_decode($content, FALSE, 512, JSON_BIGINT_AS_STRING);
                    }
                    $obj->http_code = $code;
                    $obj->http_url = $url;
                    if (isset($opt["header_out"]) && $opt["header_out"]) {
                        $obj->http_header_out = curl_getinfo($chan, CURLINFO_HEADER_OUT);
                    }
                    if (isset($opt["header"]) && $opt["header"]) {
                        $obj->http_headers = $header;
                    }
                    $obj->content = $content;
                }
                curl_multi_remove_handle($mh, $chan);
                curl_close($chan);
                unset($opts[$key], $content, $chans[$key], $headers[(string)$done["handle"]], $keys[(string)$done["handle"]]);
                if (isset($callback)) {
                    if (is_callable($callback)) {
                        try {
                            if (stristr($key, "%%%%%%%%")) {
                                list($key,) = explode("%%%%%%%%", $key);
                            }
                            $r = call_user_func($callback, $obj, $key, $opt);
                        } catch (Exception $ex) {
                            try {
                                $r = call_user_func($callback, $obj, $key);
                            } catch (Exception $ex) {
                                try {
                                    $r = call_user_func($callback, $obj);
                                } catch (Exception $ex) {
                                    $r = call_user_func($callback);
                                }
                            }
                        }
                        if (isset($r)) {
                            if (is_array($r) && (isset($r["api_url"]) || isset($r["url"]) || isset($r["endpoint"]) || isset($r["function"]))) {
                                $_key = NULL;
                                if (isset($r["key"])) {
                                    $_key = $r["key"];
                                    $opts[$_key] = $r;
                                } else {
                                    $opts[] = $r;
                                    end($opts);
                                    $_key = key($opts);
                                    reset($opts);
                                }
                                if (isset($r["add_end"]) && $r["add_end"]) {
                                    $_keys[] = $_key;
                                } else {
                                    array_splice($_keys, 0, 0, $_key);
                                }
                            } elseif (is_array($r) && count($r) > 0) {
                                foreach ($r as $_r) {
                                    if (is_array($_r) && (isset($_r["api_url"]) || isset($_r["url"]) || isset($_r["endpoint"]) || isset($_r["function"]))) {
                                        if (isset($_r["key"])) {
                                            $_key = $_r["key"] . "%%%%%%%%" . md5(uniqid(rand(), TRUE) . microtime(TRUE));
                                            $opts[$_key] = $_r;
                                        } else {
                                            $opts[] = $_r;
                                            end($opts);
                                            $_key = key($opts);
                                            reset($opts);
                                        }
                                        if (isset($_r["add_end"]) && $_r["add_end"]) {
                                            $_keys[] = $_key;
                                        } else {
                                            array_splice($_keys, 0, 0, $_key);
                                        }
                                    }
                                }
                            } elseif (!is_array($r) && isset($r) && $r === FALSE) {
                                foreach ($chans as $chan) {
                                    curl_multi_remove_handle($mh, $chan);
                                    @curl_close($chan);
                                }
                                curl_multi_close($mh);
                                unset($keys, $_keys, $opts, $headers, $chans, $mh);
                                return;
                            }
                        }
                    } else {
                        $result[$key] = $obj;
                    }
                } else {
                    $result[$key] = $obj;
                }
                $_key = array_shift($_keys);
                if (isset($_key)) {
                    $opt = $opts[$_key];
                    $opt = curl_get_api_options($opt);
                    $opt[CURLOPT_HEADERFUNCTION] = $header_function;
                    $ch = curl_init();
                    curl_setopt_array($ch, $opt);
                    curl_multi_add_handle($mh, $ch);
                    $keys[(string)$ch] = $_key;
                    $chans[$_key] = $ch;
                    $add = TRUE;
                }
            }
            if ($running)
                curl_multi_select($mh, 10);
        } while ($execStatus === CURLM_CALL_MULTI_PERFORM || $running || $add);
        curl_multi_close($mh);
        return $result;
    }

    function curl_get_api_options($opt)
    {
        $type = isset($opt["type"]) ? $opt["type"] : "api";
        $_opt = NULL;
        if ($type == "web") {
            $_opt = curl_get_options($opt);
        } else {
            $timeout = isset($opt["timeout"]) ? $opt["timeout"] : 60;
            if (isset($_GET['oauth_verifier']) && !isset($opt["post"]['oauth_verifier'])) {
                $opt["post"]['oauth_verifier'] = $_GET['oauth_verifier'];
            }
            $method = isset($opt["method"]) ? $opt["method"] : "GET";
            if (isset($opt["function"]) && in_array(strtolower($opt["function"]), array("getaccesstoken", "getauthenticateurl", "getauthorizeurl", "getrequesttoken"))) {
                $function = strtolower($opt["function"]);
                $method = "POST";
                switch ($function) {
                    case "getaccesstoken":
                        $url = 'https://api.twitter.com/oauth/access_token';
                        break;
                    case "getrequesttoken":
                    case "getauthorizeurl":
                    case "getauthenticateurl":
                        $url = 'https://api.twitter.com/oauth/request_token';
                        break;
                    default:
                        break;
                }
            }
            if (isset($opt["api_url"]))
                $url = $opt["api_url"];
            elseif (isset($opt["endpoint"]))
                $url = "https://api.twitter.com/1.1" . (substr($opt["endpoint"], 0, 1) != "/" ? "/" : "") . $opt["endpoint"];
            $oauth_access_token = @$opt["access_token"];
            $oauth_access_token_secret = @$opt["access_token_secret"];
            $consumer_key = $opt["consumer_key"];
            $consumer_secret = $opt["consumer_secret"];
            $oauth = array(
                'oauth_consumer_key'     => $consumer_key,
                'oauth_nonce'            => md5(uniqid(rand(), TRUE)),
                'oauth_signature_method' => 'HMAC-SHA1',
                'oauth_timestamp'        => time(),
                'oauth_version'          => '1.0'
            );
            if ($oauth_access_token) {
                $oauth['oauth_token'] = $oauth_access_token;
            }
            if (isset($opt["post"]) && isset($opt["post"]["oauth_verifier"])) {
                $oauth["oauth_verifier"] = $opt["post"]["oauth_verifier"];
                unset($opt["post"]["oauth_verifier"]);
            }
            $params = @$opt["post"];
            $signing_params = array();
            $sign_params = array_merge($oauth, (array)$params);
            ksort($sign_params);
            $kv = array();
            foreach ($sign_params as $k => $v) {
                $_v = encode_rfc3986($v);
                $_k = encode_rfc3986($k);
                $signing_params[$k] = $_v;
                $kv[] = "{$k}={$_v}";
            }
            ksort($signing_params);
            $auth_params = array_intersect_key($oauth, $signing_params);
            $base = implode("&", encode_rfc3986(array(strtoupper($method), normalizeUrl($url), implode('&', $kv))));
            $key = implode("&", encode_rfc3986(array($consumer_secret, $oauth_access_token_secret)));
            $oauth_signature = base64_encode(hash_hmac('sha1', $base, $key, TRUE));
            $auth_params['oauth_signature'] = encode_rfc3986($oauth_signature);
            $header = array(
                "Accept: */*",
                "Accept-Language: tr;q=1",
                "Connection: Keep-Alive",
                "Keep-Alive: 300"
            );
            $urlParts = parse_url($url);
            $kv = array();
            foreach ($auth_params as $name => $value) {
                $kv[] = "{$name}=\"{$value}\"";
            }
            $header[] = 'Authorization: OAuth ' . implode(", ", $kv);
            if (isset($opt["http_header"])) {
                $header = array_merge($header, $opt["http_header"]);
            }
            if (isset($opt["post"]) && $method == "GET") {
                $url .= "?" . http_build_query($opt["post"], '', '&');
            }
            $user_agent = (isset($opt["user_agent"]) ? $opt["user_agent"] : "");
            $_opt = array(
                CURLOPT_URL            => $url,
                CURLOPT_USERAGENT      => $user_agent,
                CURLOPT_FOLLOWLOCATION => TRUE,
                CURLOPT_RETURNTRANSFER => TRUE,
                CURLOPT_SSL_VERIFYPEER => FALSE,
                CURLOPT_SSL_VERIFYHOST => FALSE,
                CURLOPT_ENCODING       => "",
                CURLOPT_TIMEOUT        => $timeout,
                CURLOPT_HTTPHEADER     => $header,
                CURLOPT_CUSTOMREQUEST  => $method,
                CURLOPT_COOKIESESSION  => FALSE
            );
            if (isset($opt["header_out"])) {
                $_opt[CURLINFO_HEADER_OUT] = $opt["header_out"];
            }
            if ($method == "POST" && isset($opt["post"])) {
                $_opt[CURLOPT_HTTPGET] = FALSE;
                $_opt[CURLOPT_PUT] = FALSE;
                $_opt[CURLOPT_POST] = TRUE;
                foreach ($opt["post"] as $k => $v) {
                    $post[] = encode_rfc3986($k) . "=" . encode_rfc3986($v);
                }
                $_opt[CURLOPT_POSTFIELDS] = implode('&', $post);
                $_opt[CURLOPT_HTTPHEADER] = array_merge($header, array("Expect: "));
            } else {
                $_opt[CURLOPT_POST] = 0;
                $_opt[CURLOPT_POSTFIELDS] = NULL;
                $_opt[CURLOPT_HTTPGET] = TRUE;
            }
            if (isset($opt["proxy"])) {
                $_opt[CURLOPT_PROXY] = $opt["proxy"];
            }
            if (isset($opt["proxy_ip"]) && isset($opt["proxy_port"])) {
                $_opt[CURLOPT_PROXY] = $opt["proxy_ip"];
                $_opt[CURLOPT_PROXYPORT] = $opt["proxy_port"];
            }
            if (isset($opt["proxy_type"])) {
                $_opt[CURLOPT_PROXYTYPE] = $opt["proxy_type"];
            }
            if (isset($opt["interface"])) {
                $_opt[CURLOPT_INTERFACE] = $opt["interface"];
            }
        }
        return $_opt;
    }

    function curl_get_options($opt)
    {
        $url = $opt["url"];
        $timeout = isset($opt["timeout"]) ? $opt["timeout"] : 30;
        $follow_location = isset($opt["follow_location"]) ? (bool)$opt["follow_location"] : TRUE;
        $fake_ip = fakeip();
        $http_header = array(
            "REMOTE_ADDR: " . $fake_ip,
            "REMOTE_ADDR" => $fake_ip,

        );
        if (isset($opt["http_header"])) {
            $http_header = array_merge($http_header, $opt["http_header"]);
        }
        $user_agents = array(
            "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:32.0) Gecko/20100101 Firefox/32.0",
            "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.120 Safari/537.36"
        );
        $user_agent = (isset($opt["user_agent"]) ? $opt["user_agent"] : $user_agents[array_rand($user_agents)]);
        $_opt = array(
            CURLOPT_URL            => $url,
            CURLOPT_USERAGENT      => $user_agent,
            CURLOPT_FOLLOWLOCATION => $follow_location,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_SSL_VERIFYHOST => FALSE,
            CURLOPT_ENCODING       => "",
            CURLOPT_TIMEOUT        => $timeout,
            CURLOPT_HTTPHEADER     => $http_header,
        );
        if (isset($opt["cookie"])) {
            $_opt[CURLOPT_COOKIE] = $opt["cookie"];
        }
        if (isset($opt["cookie_file"])) {
            $_opt[CURLOPT_COOKIEFILE] = $opt["cookie_file"];
        }
        if (isset($opt["cookie_jar"])) {
            $_opt[CURLOPT_COOKIEJAR] = $opt["cookie_jar"];
        }
        if (isset($opt["nobody"])) {
            $_opt[CURLOPT_NOBODY] = $opt["nobody"];
        }
        if (isset($opt["file"])) {
            $_opt[CURLOPT_FILE] = $opt["file"];
        }
        if (isset($opt["binary_transfer"])) {
            $_opt[CURLOPT_BINARYTRANSFER] = $opt["binary_transfer"];
        }
        if (isset($opt["header_out"])) {
            $_opt[CURLINFO_HEADER_OUT] = $opt["header_out"];
        }
        if (isset($opt["proxy"])) {
            $_opt[CURLOPT_PROXY] = $opt["proxy"];
        }
        if (isset($opt["proxy_ip"]) && isset($opt["proxy_port"])) {
            $_opt[CURLOPT_PROXY] = $opt["proxy_ip"];
            $_opt[CURLOPT_PROXYPORT] = $opt["proxy_port"];
        }
        if (isset($opt["proxy_type"])) {
            $_opt[CURLOPT_PROXYTYPE] = $opt["proxy_type"];
        }
        if (isset($opt["interface"])) {
            $_opt[CURLOPT_INTERFACE] = $opt["interface"];
        }
        if (isset($opt["custom_request"])) {
            $c = array(
                "GET"  => CURLOPT_HTTPGET,
                "POST" => CURLOPT_POST,
                "PUT"  => CURLOPT_PUT
            );
            $x = $opt["custom_request"];
            if (isset($c[$x])) {
                $c = $c[$x];
                $_opt[$c] = TRUE;
            }
            unset($x, $c);
        }
        if (isset($opt["post"])) {
            $_opt[CURLOPT_HTTPGET] = FALSE;
            $_opt[CURLOPT_PUT] = FALSE;
            $_opt[CURLOPT_POST] = TRUE;
            $_opt[CURLOPT_POSTFIELDS] = $opt["post"];
            $_opt[CURLOPT_HTTPHEADER] = array_merge($http_header, array("Expect: "));
        } else {
            $_opt[CURLOPT_POST] = 0;
            $_opt[CURLOPT_POSTFIELDS] = NULL;
            $_opt[CURLOPT_HTTPGET] = TRUE;
        }
        return $_opt;
    }

?>
