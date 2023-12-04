<?php
namespace App\ThirdParty;
class Weixin {

    private $appId     = 'wx15fd0f9b451abfbd';
    private $appSecret = '10f5a49dcf62f17b617df58be7d90f4f';

    private $client;

    public function __construct() {
        $this->client = \Config\Services::curlrequest();
    }

    public function getSignPackage() {
        $jsapiTicket = $this->getJsApiTicket();

        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url      = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $timestamp = time();
        $nonceStr  = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "appId"     => $this->appId,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "rawString" => $string,
        );
        return $signPackage;
    }

    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str   = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    private function getJsApiTicket() {
        // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
        $data = json_decode($this->get_php_file("jsapi_ticket.php"),true);
        if (!isset($data['expire_time'])) {
            $data['expire_time'] = 0;
        }

        if ($data['expire_time'] < time()) {
            $accessToken = $this->getAccessToken();
            // 如果是企业号用以下 URL 获取 ticket
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";

            $res    = $this->client->request('get', $url);
            $res    = $res->getBody();
            $res    = json_decode($res, true);
            $ticket = $res['ticket'];
            if ($ticket) {
                $data['expire_time']  = time() + 7000;
                $data['jsapi_ticket'] = $ticket;
                $this->set_php_file("jsapi_ticket.php", json_encode($data));
            }
        } else {
            $ticket = $data['jsapi_ticket'];
        }

        return $ticket;
    }

    private function getAccessToken() {
        // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
        $data = json_decode($this->get_php_file("access_token.php"),true);
        if (!isset($data['expire_time'])) {
            $data['expire_time'] = 0;
        }

        if ($data['expire_time'] < time()) {
            // 如果是企业号用以下URL获取access_token
            $url          = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
            $res          = $this->client->request('get', $url);
            $res          = $res->getBody();
            $res          = json_decode($res, true);
            $access_token = $res['access_token'];
            if ($access_token) {
                $data['expire_time']  = time() + 7000;
                $data['access_token'] = $access_token;
                $this->set_php_file("access_token.php", json_encode($data));
            }
        } else {
            $access_token = $data['access_token'];
        }
        return $access_token;
    }

    private function get_php_file($filename) {
        return trim(file_get_contents(FCPATH . $filename));
    }
    private function set_php_file($filename, $content) {
        $fp = fopen(FCPATH . $filename, "w");
        fwrite($fp, $content);
        fclose($fp);
    }
}
