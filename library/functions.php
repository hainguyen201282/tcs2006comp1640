<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if (!function_exists("get_data")) {
    function get_data($url)
    {
        $ch = curl_init();
        $timeout = 50;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // ignore HTTPS
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HEADER, false);

        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}
if (!function_exists("do_post_request")) {
    function do_post_request($url, $data)
    {

        $query = http_build_query($data);
//        print_r($query);exit;

        $ch = curl_init(); // Init cURL

        curl_setopt($ch, CURLOPT_URL, $url); // Post location
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 1 = Return data, 0 = No return
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // ignore HTTPS
        curl_setopt($ch, CURLOPT_POST, 1); // This is POST
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query); // Add the data to the request

        $response = curl_exec($ch); // Execute the request
        curl_close($ch); // Finish the request

        if ($response) {
            return $response;
        } else {
            return NULL;
        }
    }
}
if (!function_exists("do_post_request_json")) {
    function do_post_request_json($url, $data, $header = null, $options = [])
    {

        $query = json_encode($data);
//        $query = base64_encode($query);
        $headerInfo = $header;
//        print_r($headerInfo);exit;

        $ch = curl_init(); // Init cURL

        curl_setopt($ch, CURLOPT_URL, $url); // Post location
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 1 = Return data, 0 = No return
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // ignore HTTPS
        curl_setopt($ch, CURLOPT_POST, 1); // 1: post, else get
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query); // Add the data to the request
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $headerInfo);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($query))
        );

        $response = curl_exec($ch); // Execute the request
        curl_close($ch); // Finish the request

        if ($response) {
            return $response;
        } else {
            return NULL;
        }
    }
}

if (!function_exists("socket_io_message")) {
    function socket_io_message($message, $data) {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        $result = socket_connect($socket, $_SERVER['HTTP_HOST'], 3000);
        if(!$result) {
            die('cannot connect '.socket_strerror(socket_last_error()).PHP_EOL);
        }
        $bytes = socket_write($socket, json_encode(Array("msg" => $message, "data" => $data)));
        socket_close($socket);
    }
}

if (!function_exists("decrypt")) {
    function decrypt($input, $key_seed)
    {

        $input = @base64_decode($input);
        $key = @substr(md5($key_seed), 0, 24);
        $text = @mcrypt_decrypt(MCRYPT_TRIPLEDES, $key, $input, MCRYPT_MODE_ECB, '12345678');
        $block = @mcrypt_get_block_size('tripledes', 'ecb');

        $packing = @ord($text{strlen($text) - 1});
        if ($packing and ($packing < $block)) {
            for ($P = @strlen($text) - 1; $P >= @strlen($text) - $packing; $P--) {
                if (@ord($text{$P}) != $packing) {
                    $packing = 0;
                }
            }
        }
        $text = @substr($text, 0, strlen($text) - $packing);

        return $text;
    }
}
if (!function_exists("getIP")) {
    function getIP()
    {
        $ip = '';
        if (getenv("HTTP_CLIENT_IP"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if (getenv("HTTP_X_FORWARDED_FOR"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (getenv("REMOTE_ADDR"))
            $ip = getenv("REMOTE_ADDR");
        else
            $ip = "UNKNOWN";

        $ip = explode(',', $ip);
        return $ip[0];
    }
}
if (!function_exists("ascii_to_entities")) {
    function ascii_to_entities($str)
    {
        $count = 1;
        $out = '';
        $temp = array();

        for ($i = 0, $s = strlen($str); $i < $s; $i++) {
            $ordinal = ord($str[$i]);

            if ($ordinal < 128) {
                if (count($temp) == 1) {
                    $out .= '&#' . array_shift($temp) . ';';
                    $count = 1;
                }

                $out .= $str[$i];
            } else {
                if (count($temp) == 0) {
                    $count = ($ordinal < 224) ? 2 : 3;
                }

                $temp[] = $ordinal;

                if (count($temp) == $count) {
                    $number = ($count == 3) ? (($temp['0'] % 16) * 4096) +
                        (($temp['1'] % 64) * 64) +
                        ($temp['2'] % 64) : (($temp['0'] % 32) * 64) +
                        ($temp['1'] % 64);

                    $out .= '&#' . $number . ';';
                    $count = 1;
                    $temp = array();
                }
            }
        }

        return $out;
    }
}
if (!function_exists("htmlentities_UTF8")) {

    function htmlentities_UTF8($str)
    {
        return htmlentities($str, ENT_QUOTES, 'utf-8');
    }

}
if (!function_exists("htmlentities_decode_UTF8")) {

    function htmlentities_decode_UTF8($str)
    {
        return html_entity_decode($str, ENT_QUOTES, 'utf-8');
    }

}
if (!function_exists("JO_location")) {

    function JO_location($URL = false)
    {
        if ($URL)
            exit("<script> try { parent.location.replace ( '" . $URL . "'						); } catch ( exception ) { location.replace( '" . $URL . "'							); } </script>");
        else
            exit("<script> try { parent.location.replace ( '" . $_SERVER["HTTP_REFERER"] . "'	); } catch ( exception ) { location.replace( '" . $_SERVER["HTTP_REFERER"] . "'	); } </script>");
    }

}
if (!function_exists("objectToArray")) {

    function objectToArray($d)
    {
        if (is_object($d)) {
            // Gets the properties of the given object
            // with get_object_vars function
            $d = get_object_vars($d);
        }

        if (is_array($d)) {
            /*
             * Return array converted to object
             * Using __FUNCTION__ (Magic constant)
             * for recursive call
             */
            return array_map(__FUNCTION__, $d);
        } else {
            // Return array
            return $d;
        }
    }

}
if (!function_exists("convertUrl")) {

    function convertUrl($str)
    {
        $remove = "~ ` ! @ # $ % ^ & * ( ) _ + | \\ = ' \" ; : ? / > . , < ] [ { }";
        $from = "à á ạ ả ã â ầ ấ ậ ẩ ẫ ă ằ ắ ặ ẳ ẵ è é ẹ ẻ ẽ ê ề ế ệ ể ễ ì í ị ỉ ĩ ò ó ọ ỏ õ ô ồ ố ộ ổ ỗ ơ ờ ớ ợ ở ỡ ù ú ụ ủ ũ ư ừ ứ ự ử ữ ỳ ý ỵ ỷ ỹ đ ";
        $to = "a a a a a a a a a a a a a a a a a e e e e e e e e e e e i i i i i o o o o o o o o o o o o o o o o o u u u u u u u u u u u y y y y y d ";
        $from .= " À Á Ạ Ả Ã Â Ầ Ấ Ậ Ẩ Ẫ Ă Ằ Ắ Ặ Ẳ Ẵ È É Ẹ Ẻ Ẽ Ê Ề Ế Ệ Ể Ễ Ì Í Ị Ỉ Ĩ Ò Ó Ọ Ỏ Õ Ô Ồ Ố Ộ Ổ Ỗ Ơ Ờ Ớ Ợ Ở Ỡ Ù Ú Ụ Ủ Ũ Ư Ừ Ứ Ự Ử Ữ Ỳ Ý Ỵ Ỷ Ỹ Đ ";
        $to .= " a a a a a a a a a a a a a a a a a e e e e e e e e e e e i i i i i o o o o o o o o o o o o o o o o o u u u u u u u u u u u y y y y y d ";
        $remove = explode(" ", $remove);
        $from = explode(" ", $from);
        $to = explode(" ", $to);
        $str = str_replace($from, $to, $str);
        $str = str_replace($remove, "", $str);
        $str = strip_tags($str);
        $str = iconv("UTF-8", "ISO-8859-1//TRANSLIT//IGNORE", $str);
        //$str = iconv("ISO-8859-1","UTF-8",$str);
        $str = str_replace(" ", "-", $str);
        while (!(strpos($str, "--") === false)) {
            $str = str_replace("--", "-", $str);
        }

        $str = strtolower($str);
        return $str;
    }

}
if (!function_exists("check_url")) {
    function check_url($url)
    {
        $options = array(
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING => "",       // handle all encodings
            CURLOPT_USERAGENT => "http://", // who am i
            CURLOPT_AUTOREFERER => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT => 120,      // timeout on response
            CURLOPT_MAXREDIRS => 10,       // stop after 10 redirects
            CURLOPT_SSL_VERIFYPEER => false,    // don't verify ssl
        );
        $ch = curl_init($url);

        //proxy details
        curl_setopt($ch, CURLOPT_PROXY, '10.50.0.253:3128');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));

        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        $err = curl_errno($ch);
        $errmsg = curl_error($ch);
        $header = curl_getinfo($ch);
        curl_close($ch);
        $header['errno'] = $err;
        $header['errmsg'] = $errmsg;
        $header['content'] = $content;
        return $header;
    }
}
if (!function_exists("get_real_ip")) {
    function get_real_ip()
    {
        $ip = false;
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
            if ($ip) {
                array_unshift($ips, $ip);
                $ip = false;
            }
            for ($i = 0; $i < count($ips); $i++) {
                if (!preg_match("/^(10|172\.16|192\.168)\./i", $ips[$i])) {
                    if (version_compare(phpversion(), "5.0.0", ">=")) {
                        if (ip2long($ips[$i]) != false) {
                            $ip = $ips[$i];
                            break;
                        }
                    } else {
                        if (ip2long($ips[$i]) != -1) {
                            $ip = $ips[$i];
                            break;
                        }
                    }
                }
            }
        }
        return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
    }
}

if (!function_exists("maintain")) {
    function maintain($msg = "")
    {
        die('<!DOCTYPE html>
    <html lang="en">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Maintainance.</title>
    </head>
    <body style="background-color: #ccc;">
        <div style="position: fixed;z-index:9999;top:0;left:0;bottom:0;right:0;background:#ccc">
        <div style="
            padding: 10px;
            width: 500px;
            height: 350px;
            position: absolute;
            left: 50%;
            top: 50%;
            margin: -180px 0 0 -260px;
            background: #ccc;
            background: rgba(0,0,0,.5)
            ">
            <div style="font-family:tahoma;font-size:11px;line-height:16px;position:absolute">
                <p> Message:  ' . $msg . '</br>
                </p>
            </div>
        </div>
    </div>
    </body>
    </html>');
    }
}

if (!function_exists('sendMail')) {
    function sendMail($to = 'example@mail.com', $title = 'Title', $content = '')
    {
        require_once APPPATH . 'libraries/smtp/class.smtp.php';

        $mailConfirm = new Mail($to, $title, $content);
        return $mailConfirm;
    }
}
// random string
if (!function_exists('randString')) {
    function randString()
    {
        //Y: A full numeric representation of a year, 4 digits
        //m: Numeric representation of a month, with leading zeros
        //d: Day of the month, 2 digits with leading zeros
        //H: 24-hour format of an hour with leading zeros
        //i: Minutes with leading zeros 00 -59
        //s: Seconds, with leading zeros
        //u: Microseconds (example: 654321)
        $strFormat = 'YmdHis'; //bo u
        $date = new DateTime();
        $xxxx = "";
        for ($i = 0; $i < 8; $i++) {
            $xxxx .= rand(0, 9);
        }
        $requestID = $date->format($strFormat) . $xxxx;
        return $requestID;
    }
}
// random chuoi khong bi trung
if (!function_exists('rand_string_limit')) {
    function rand_string_limit($length)
    {
        $str = "";
        //$chars  = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $chars = "0123456789";
        $size = strlen($chars);
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[rand(0, $size - 1)];
        }
        return $str;
    }
}
// hide_some_char_from_string
if (!function_exists('hide_some_char_from_string')) {
    function hide_some_char_from_string($str, $numberShow)
    {

        $get_some_char_string = substr($str, 0, $numberShow);
        $count = strlen($str);
        $count_str_hidden = $count - $numberShow;
        $str_be_replace = "*";
        if ($count_str_hidden > 0) {
            for ($i = 0; $i < $count_str_hidden; $i++) {
                $str_be_replace .= "*";
            }
        }
        return $get_some_char_string . $str_be_replace;
    }
}

// get domain name
if (!function_exists('getDomainName')) {
    function getDomainName()
    {

        $domain = $_SERVER['SERVER_NAME']; // get domain name
        return $domain;
    }
}
// get domain name
if (!function_exists('getHostUrlName')) {
    function getHostUrlName()
    {

        $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        return $actual_link;
    }
}
// get/set cookie
if (!function_exists('getCookieValue')) {
    function getCookieValue($domain = NULL)
    {
        if (isset($_COOKIE['lastVisit'])) {
            $visit = $_COOKIE['lastVisit']; // cookie expire
        } else {
            //seconds * minutes * hours * days + current time
            $inOneMonths = 60 * 60 * 24 * 30 + time();
            $cookieValue = rand(1, 10000000) . time();
            setcookie('lastVisit', $cookieValue, $inOneMonths, $domain);
            $visit = $_COOKIE['lastVisit']; // first cookie
        }
        return $visit;
    }
}
if (!function_exists('getUnixTimeStamp')) {
    /*
     * get unix timestamp by date
     * */
    function getUnixTimeStamp($date)
    {
        if ($date) {
            $dtNow = new DateTime($date); //d-m-y
        } else {
            $dtNow = new DateTime(); // current date
        }
        // Set a non-default timezone if needed
        $dtNow->setTimezone(new DateTimeZone('Asia/Bangkok'));
        $beginOfDay = clone $dtNow;

        // Go to midnight.  ->modify('midnight') does not do this for some reason
        $beginOfDay->modify('today');
        $start = $beginOfDay->getTimestamp();

        $endOfDay = clone $beginOfDay;
        $endOfDay->modify('tomorrow');
        // adjust from the next day to the end of the day, per original question
        $endOfDay->modify('1 second ago');
        $end = $endOfDay->getTimestamp();
        $result = array(
            'time ' => $dtNow->format('Y-m-d H:i:s e'),
            'start' => $beginOfDay->format('Y-m-d H:i:s e'),
            'start_short' => $beginOfDay->format('Y-m-d'),
            'start_milliseconds' => $start,
            'end' => $endOfDay->format('Y-m-d H:i:s e'),
            'end_short' => $endOfDay->format('Y-m-d'),
            'end_milliseconds' => $end,
        );
        return $result;
    }
}

if (!function_exists("get_base_url")) {
    function get_base_url(){
        $actualLink = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        return $actualLink;
    }
}
if (!function_exists("get_full_url")) {
    function get_full_url(){
        $actualLink = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        return $actualLink;
    }
}