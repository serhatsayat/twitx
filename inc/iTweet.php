<?php

    error_reporting();
    require("iCurl.php");


    class iTweet
    {
        Private $ROOT_PATH;

        public function __construct()
        {
            header("Content-Type: text/html; charset=utf-8");
            $this->ROOT_PATH = dirname(__FILE__);
        }

        public function getRandomConsumerKey()
        {
            $keys = array(
                
                "Twitter for iPad"          => array(
                    "consumer_key"    => "CjulERsDeqhhjSme66ECg",
                    "consumer_secret" => "IQWdVyqFxghAtURHGeGiWAsmCAGmdW3WmbEx6Hck",
                    "name"            => "Twitter for iPad",
                ),
               
            );
            shuffle($keys);
            return $this->toJson($keys[array_rand($keys)]);
        }

        public function importUsers($users)
        {

            foreach ($users as $key => $user) {
                list($username, $password) = explode(":", $user);
                $_api[$key] = $this->getRandomConsumerKey();
                $opts[$key] = array(
                    "api_url"         => "https://api.twitter.com/oauth/access_token",
                    "consumer_key"    => $_api[$key]->consumer_key,
                    "consumer_secret" => $_api[$key]->consumer_secret,
        
                    "method"          => "POST",
                    "header_out"      => TRUE,
                    //"proxy"           => "37.48.118.90:13151",
                    "header"          => TRUE,
                    "post"            => array(
                        "x_auth_mode"     => "client_auth",
                        "x_auth_username" => $username,
                        "x_auth_password" => $password
                    ),
                    "http_header"     => array(
                        "Timezone: Europe/Istanbul",
                        "X-Client-UUID: " . gen_uuid(),
                        "X-Twitter-Polling: true",
                        "Connection: " . "Keep-Alive",
                    )
                );

            }

            $return["ok"] = 0;
            $return["no"] = 0;
            multi_curl_api($opts, function ($que, $key) use (&$return, &$_api) {
                $api = $_api[$key];
                if ($que->http_code == 200 && isset($que->content)) {
                    parse_str($que->content, $data);
                    $data = array("username"           => $data["screen_name"],
                                  "consumer_key"       => $api->consumer_key,
                                  "consumer_secret"    => $api->consumer_secret,
                                  "consumer_name"      => $api->name,
                                  "oauth_token"        => $data["oauth_token"],
                                  "oauth_token_secret" => $data["oauth_token_secret"],
                    );
                    file_put_contents("data/{$data['username']}.json", json_encode($data));
                    $return["ok"]++;
                } else {
                    $return["no"]++;
                }
            }, 100);
            return $return;
        }

        public function addFavorite($tweet_id, $count)
        {
            foreach ($this->getUsers() as $key => $user) {
                $opts[$key] = array(
                    "api_url"             => "https://api.twitter.com/1.1/favorites/create.json",
                    "consumer_key"        => $user->consumer_key,
                    "consumer_secret"     => $user->consumer_secret,
                    "access_token"        => $user->oauth_token,
                    "access_token_secret" => $user->oauth_token_secret,
                    "method"              => "POST",
                    "header"              => TRUE,
                    "post"                => array(
                        "id" => $tweet_id
                    ),
                    "http_header"         => array(
                        "Timezone: Europe/Istanbul",
                        "X-Client-UUID: " . gen_uuid(),
                        "X-Twitter-Polling: true",
                        "Connection: " . "Keep-Alive",
                    )
                );
            }

            $inc = 0;
            $return["ok"] = 0;
            $return["no"] = 0;
            multi_curl_api($opts, function ($que, $key) use (&$return, &$count) {
                if (isset($que->id) && $que->http_code == 200) {
                    $return["ok"]++;
                }

                if ($return["ok"] >= $count) {
                    return FALSE;
                }
            }, 100);
            return $return;
        }

        public function addRetweet($tweet_id, $count)
        {
            foreach ($this->getUsers() as $key => $user) {
                $opts[$key] = array(
                    "api_url"             => "https://api.twitter.com/1.1/statuses/retweet/{$tweet_id}.json",
                    "consumer_key"        => $user->consumer_key,
                    "consumer_secret"     => $user->consumer_secret,
                    "access_token"        => $user->oauth_token,
                    "access_token_secret" => $user->oauth_token_secret,
                    "method"              => "POST",
                    "header"              => TRUE,
                    "post"                => array(
                        "id" => $tweet_id
                    ),
                    "http_header"         => array(
                        "Timezone: Europe/Istanbul",
                        "X-Client-UUID: " . gen_uuid(),
                        "X-Twitter-Polling: true",
                        "Connection: " . "Keep-Alive",
                    )
                );
            }

            $inc = 0;
            $return["ok"] = 0;
            $return["no"] = 0;
            multi_curl_api($opts, function ($que, $key) use (&$return, &$count) {
                if (isset($que->id) && $que->http_code == 200) {
                    $return["ok"]++;
                }

                if ($return["ok"] >= $count) {
                    return FALSE;
                }
            }, 100);
            return $return;
        }

        public function addFollower($username, $count)
        {
            foreach ($this->getUsers() as $key => $user) {
                $opts[$key] = array(
                    "api_url"             => "https://api.twitter.com/1.1/friendships/create.json",
                    "consumer_key"        => $user->consumer_key,
                    "consumer_secret"     => $user->consumer_secret,
                    "access_token"        => $user->oauth_token,
                    "access_token_secret" => $user->oauth_token_secret,
                    "method"              => "POST",
                    "header"              => TRUE,
                    "post"                => array(
                        "id" => $username
                    ),
                    "http_header"         => array(
                        "Timezone: Europe/Istanbul",
                        "X-Client-UUID: " . gen_uuid(),
                        "X-Twitter-Polling: true",
                        "Connection: " . "Keep-Alive",
                    )
                );
          }


            $inc = 0;
            $return["ok"] = 0;
            $return["no"] = 0;
            multi_curl_api($opts, function ($que, $key) use (&$return, &$count) {
                if (isset($que->id) && $que->http_code == 200) {
                    $return["ok"]++;
                }

                if ($return["ok"] >= $count) {
                    return FALSE;
                }
            }, 100);
            return $return;
        }
      

        public function checkUsers()
        {
            foreach ($this->getUsers() as $key => $user) {
                $opts[$key] = array(
                    "api_url"             => "https://api.twitter.com/1.1/account/verify_credentials.json",
                    "consumer_key"        => $user->consumer_key,
                    "consumer_secret"     => $user->consumer_secret,
                    "access_token"        => $user->oauth_token,
                    "access_token_secret" => $user->oauth_token_secret,
    
                    "method"              => "GET",
                    "header"              => TRUE,
                    "http_header"         => array( 
                        "Timezone: Europe/Istanbul",
                        "X-Client-UUID: " . gen_uuid(),
                        "X-Twitter-Polling: true",
                        "Connection: " . "Keep-Alive",
                    )
                );
                $users[$key] = $user->username;
            }

            $inc = 0;
            $return["ok"] = 0;
            $return["no"] = 0;
            multi_curl_api($opts, function ($que, $key) use (&$return, &$users) {
                if (isset($que->id) && $que->http_code == 200) {
                    $return["ok"]++;
                } else {
                    unlink("data/{$users[$key]}.json");
                    $return["no"]++;
                }
            }, 100);
            return $return;
        }

        public function getUsers()
        {
            $users = glob("data/*.json");
            shuffle($users);
            foreach ($users as $user_file) {
                $data[] = json_decode(file_get_contents($user_file));
            }
            return $data;
        }

     


        public
        function toJson($string)
        {
            return json_decode(json_encode($string));
        }
    }

    $iTweet = new iTweet();



