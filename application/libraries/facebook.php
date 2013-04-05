<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class facebook {

    function get_facebook_cookie() {
        $CI = & get_instance();
        $app_id = $CI->config->item('facebook_app_id');
        $application_secret = $CI->config->item('facebook_app_secret');
        if (isset($_COOKIE['fbsr_' . $app_id])) {
            list($encoded_sig, $payload) = explode('.', $_COOKIE['fbsr_' . $app_id], 2);

            $sig = base64_decode(strtr($encoded_sig, '-_', '+/'));
            $data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);

            if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
                return null;
            }
            $expected_sig = hash_hmac('sha256', $payload,
                            $application_secret, $raw = true);
            if ($sig !== $expected_sig) {
                return null;
            }
            $token_url = "https://graph.facebook.com/oauth/access_token?"
                    . "client_id=" . $app_id . "&client_secret=" . $application_secret . "&redirect_uri=" . "&code=" . $data['code'];

            $response = @file_get_contents($token_url);
            $params = null;
            parse_str($response, $params);
            $data['access_token'] = $params['access_token'];
            return $data;
        } else {
            return null;
        }
    }

    // get the user from the facebook cookie
    function getUser() {
        $cookie = $this->get_facebook_cookie();
        $user = @json_decode(file_get_contents('https://graph.facebook.com/me?access_token=' . $cookie['access_token']), true);
        return $user;
    }

    function getPicture() {
        $cookie = $this->get_facebook_cookie();
        $user = @json_decode(file_get_contents('https://graph.facebook.com/me/picture?access_token=' . $cookie['access_token']), true);
        return $user;
    }

    // get any user
    function get_any_user($user_id) {
        $cookie = $this->get_facebook_cookie();
        $user = @json_decode(file_get_contents('https://graph.facebook.com/' . $user_id . '?access_token=' . $cookie['access_token']), true);
        return $user;
    }

    function getFriendIds($include_self = TRUE) {
        $cookie = $this->get_facebook_cookie();
        $friends = @json_decode(file_get_contents(
                                'https://graph.facebook.com/me/friends?access_token=' .
                                $cookie['access_token']), true);
        $friend_ids = array();
        foreach ($friends['data'] as $friend) {
            $friend_ids[] = $friend['id'];
        }
        if ($include_self == TRUE) {
            $friend_ids[] = $cookie['uid'];
        }
        return $friend_ids;
    }

    function getFriends($include_self = TRUE) {
        $cookie = $this->get_facebook_cookie();
        $friends = @json_decode(file_get_contents('https://graph.facebook.com/me/friends?access_token=' . $cookie['access_token']), true);
        if ($include_self == TRUE) {
            $friends['data'][] = array('name' => 'You', 'id' => $cookie['uid']);
        }
        return $friends['data'];
    }

    function getFriendsFriends($friend, $include_self = TRUE) {
        $cookie = $this->get_facebook_cookie();
        $friends = @json_decode(file_get_contents('https://graph.facebook.com/' . $friend . '/friends?access_token=' . $cookie['access_token']), true);
        echo $cookie['access_token'];
        if ($include_self == TRUE) {
            $friends['data'][] = array('name' => 'You', 'id' => $cookie['uid']);
        }
        return $friends['data'];
    }

    function getFriendArray($include_self = TRUE) {
        $cookie = $this->get_facebook_cookie();
        $friendlist = @json_decode(file_get_contents(
                                'https://graph.facebook.com/me/friends?access_token=' .
                                $cookie['access_token']), true);
        $friends = array();
        foreach ($friendlist['data'] as $friend) {
            $friends[$friend['id']] = $friend['name'];
        }
        if ($include_self == TRUE) {
            $friends[$cookie['uid']] = 'You';
        }
        return $friends;
    }

}

?>