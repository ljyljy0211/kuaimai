<?php

namespace YiHaiTao\KuaiMai;

use Hanson\Foundation\Foundation;

class KuaiMai extends Foundation
{
    public function __construct($config)
    {
        $config['debug'] = $config['debug'] ?? false;
        $config['sign_method'] = $config['sign_method'] ?? 'md5';
        parent::__construct($config);
    }

    public function request($method, $params)
    {
        $api = new Api($this->config['app_key'], $this->config['app_secret'], $this->config['access_token'], $this->config['base_url'], $this->config['sign_method']);
        return $api->request($method, $params);
    }
}
