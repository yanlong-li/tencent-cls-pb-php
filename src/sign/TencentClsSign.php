<?php

namespace yanlongli\TencentClsSign;

class TencentClsSign
{
    public static function signature($secretId, $secretKey, $method = 'GET', $path = '/', $params = array(), $headers = array(), $expire = 120)
    {
        $filter_headers = array();
        foreach ($headers as $k => $v) {
            $lower_key = strtolower($k);
            if ($lower_key == 'content-type' || $lower_key == 'content-md5' || $lower_key == 'host' || $lower_key[0] == 'x') {
                $filter_headers[$lower_key] = $v;
            }
        }
        $filter_params = array();
        foreach ($params as $k => $v) {
            $filter_params[strtolower($k)] = $v;
        }
        ksort($filter_params);
        ksort($filter_headers);
        $filter_headers = array_map('strtolower', $filter_headers);
        $uri_headers = http_build_query($filter_headers);
        $httpString = strtolower($method) . "\n" . urldecode($path) .
            "\n" . http_build_query($filter_params) . "\n" . $uri_headers . "\n";
        $signTime = (string)(time() - 60) . ';' . (string)(time() + $expire);
        $stringToSign = "sha1\n" . $signTime . "\n" . sha1($httpString) . "\n";
        $signKey = hash_hmac('sha1', $signTime, $secretKey);
        $signature = hash_hmac('sha1', $stringToSign, $signKey);
        return "q-sign-algorithm=sha1&q-ak=$secretId" .
            "&q-sign-time=$signTime&q-key-time=$signTime&q-header-list=" .
            join(";", array_keys($filter_headers)) . "&q-url-param-list=" .
            join(";", array_keys($filter_params)) . "&q-signature=$signature";
    }
}
