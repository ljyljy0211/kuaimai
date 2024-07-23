# kuaimai 快麦SDK

[https://open-doc.kuaimai.com](https://open-doc.kuaimai.com "快麦开放平台api文档")

## 安装

```shell
$ composer require yihaitao/kuaimai -vvv
```

## 使用

```php
<?php

use YiHaiTao\KuaiMai\KuaiMai;

$config = [
    'app_key' => 'your-key',
    'app_secret' => 'your-secret',
    'access_token' => 'your-access-token',
    'base_url' => 'https://gw.superboss.cc/router',
];

// 实例化快麦sdk
$km = new KuaiMai($config);
// 使用如下
$km->request('method', $params);

// 例子
$result = $km->request('erp.trade.create.new', $params);
print_r($result);
```

## License

MIT
