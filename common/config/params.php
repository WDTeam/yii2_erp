<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'alipay_web_config'=>[
        'partner' => '2088801136967007',    //合作身份者id，以2088开头的16位纯数字
        'key' => 'ptd4lbjltmwpx64g80qhil5eckr98opf',    //安全检验码，以数字和字母组成的32位字符
        'seller_email' => '47632990@qq.com',    //签约支付宝账号或卖家支付宝帐户
        'sign_type' => strtoupper('MD5'),   //签名方式 不需修改
        'input_charset' => strtolower('utf-8'), //字符编码格式 目前支持 gbk 或 utf-8
        'cacert' => getcwd().'\\cacert.pem',    //ca证书路径地址，用于curl中ssl校验 //请保证cacert.pem文件在当前文件夹目录中
        'transport' => 'http',  //访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
    ],
];
