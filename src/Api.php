<?php

namespace YiHaiTao\KuaiMai;

use Exception;
use Hanson\Foundation\AbstractAPI;

class Api extends AbstractAPI
{
    private $appKey;

    private $appSecret;

    private $accessToken;

    private $baseUrl;

    private $signMethod = 'md5';

    public function __construct($appKey, $appSecret, $accessToken, $baseUrl, $signMethod = 'md5')
    {
        $this->appKey = $appKey;
        $this->appSecret = $appSecret;
        $this->accessToken = $accessToken;
        $this->baseUrl = $baseUrl;
        $this->signMethod = $signMethod;
    }

    /*
     * 请求快麦api
     *
     * string $method 接口名,如erp.trade.create.new
     * array $params 业务参数
     * string $signMethod 加密方式 hmac，md5，hmac-sha256
     */
    public function request(string $method, array $params)
    {
        $http = $this->getHttp();
        $commonParams = $this->getCommonParams($method);
        $params = array_merge($params, $commonParams);
        $params['sign'] = $this->signature($params);
        $response = call_user_func_array([$http, 'POST'], [$this->baseUrl, $params]);

        return json_decode($response->getBody(), true);
    }

    /*
     * 封装公共请求参数
     * string $method 接口名
     */
    private function getCommonParams(string $method)
    {
        return [
            'method' => $method,
            'appKey' => $this->appKey,
            'timestamp' => date('Y-m-d H:i:s'),
            'format' => 'json',
            'session' => $this->accessToken,
            'version' => '1.0',
            'sign_method' => $this->signMethod,
        ];
    }

    /*
     * 签名
     * array $params 签名参数
     */
    private function signature(array $params)
    {
        ksort($params);
        $paramsStr = '';
        foreach ($params as $key => $value) {
            if ($key && strlen($value) > 0) {
                $paramsStr .= $key . $value;
            }
        }

        switch ($this->signMethod) {
            case 'md5':
                $paramsStr = $this->appSecret . $paramsStr . $this->appSecret;
                return strtoupper(md5($paramsStr));
            case 'hmac':
                return strtoupper(hash_hmac('md5', $paramsStr, $this->appSecret));
            case 'hmac-sha256':
                return strtoupper(hash_hmac('sha256', $paramsStr, $this->appSecret));
            default:
                throw new Exception('undefined sign method!');
        }
    }
}
