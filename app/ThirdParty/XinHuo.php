<?php
namespace App\ThirdParty;

//应用了三方的websocket 库，链接地址：https://github.com/Textalk/websocket-php
use WebSocket\Client;

class XinHuo
{
    public function GetAnswer(string $message)
    {
        $addr = "wss://spark-api.xf-yun.com/v2.1/chat";
        //密钥信息，在开放平台-控制台中获取：https://console.xfyun.cn/services/cbm
        $Appid = "40d9b0b7";
        $Apikey = "169d8b4b1fff18a0049d53b89be4f57d";
        // $XCurTime =time();
        $ApiSecret = "NzEwMzdkMWZiNDJiNWMzMDYwZGExMzJk";
        // $XCheckSum ="";

        // $data = $this->getBody("你是谁？");
        $authUrl = $this->assembleAuthUrl("GET", $addr, $Apikey, $ApiSecret);
        //创建ws连接对象
        $client = new Client($authUrl);
        $answer = "";

        // 连接到 WebSocket 服务器
        if ($client) {
            // 发送数据到 WebSocket 服务器
            $data = $this->getBody($Appid, $message);
            $client->send($data);

            // 从 WebSocket 服务器接收数据
            
            while (true) {
                $response = $client->receive();
                $resp = json_decode($response, true);
                $code = $resp["header"]["code"];
                if (0 == $code) {
                    $status = $resp["header"]["status"];
                    if ($status != 2) {
                        $content = $resp['payload']['choices']['text'][0]['content'];
                        $answer .= $content;
                    } else {
                        $content = $resp['payload']['choices']['text'][0]['content'];
                        $answer .= $content;
                        break;
                    }
                } else {
                    die("服务返回报错" . $response);
                    break;
                }
            }
        } else {
            die("无法连接到 WebSocket 服务器");
        }
        return $answer;
    }


    //构造参数体
    public function getBody($appid, $question)
    {
        $header = array(
            "app_id" => $appid,
            "uid" => "12345",
        );

        $parameter = array(
            "chat" => array(
                "domain" => "generalv2",
                "temperature" => 0.5,
                "max_tokens" => 1024,
            ),
        );

        $payload = array(
            "message" => array(
                "text" => array(
                    array("role" => "user", "content" => $question),
                ),
            ),
        );

        $json_string = json_encode(array(
            "header" => $header,
            "parameter" => $parameter,
            "payload" => $payload,
        ));

        return $json_string;

    }
    //鉴权方法
    public function assembleAuthUrl($method, $addr, $apiKey, $apiSecret)
    {
        if ($apiKey == "" && $apiSecret == "") { // 不鉴权
            return $addr;
        }

        $ul = parse_url($addr); // 解析地址
        if ($ul === false) { // 地址不对，也不鉴权
            return $addr;
        }

        // // $date = date(DATE_RFC1123); // 获取当前时间并格式化为RFC1123格式的字符串
        $timestamp = time();
        $rfc1123_format = gmdate("D, d M Y H:i:s \G\M\T", $timestamp);
        // $rfc1123_format = "Mon, 31 Jul 2023 08:24:03 GMT";

        // 参与签名的字段 host, date, request-line
        $signString = array("host: " . $ul["host"], "date: " . $rfc1123_format, $method . " " . $ul["path"] . " HTTP/1.1");

        // 对签名字符串进行排序，确保顺序一致
        // ksort($signString);

        // 将签名字符串拼接成一个字符串
        $sgin = implode("\n", $signString);

        // 对签名字符串进行HMAC-SHA256加密，得到签名结果
        $sha = hash_hmac('sha256', $sgin, $apiSecret, true);
        $signature_sha_base64 = base64_encode($sha);

        // 将API密钥、算法、头部信息和签名结果拼接成一个授权URL
        $authUrl = "api_key=\"$apiKey\", algorithm=\"hmac-sha256\", headers=\"host date request-line\", signature=\"$signature_sha_base64\"";

        // 对授权URL进行Base64编码，并添加到原始地址后面作为查询参数
        $authAddr = $addr . '?' . http_build_query(array(
            'host' => $ul['host'],
            'date' => $rfc1123_format,
            'authorization' => base64_encode($authUrl),
        ));

        return $authAddr;
    }
}


