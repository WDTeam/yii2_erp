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
        'notify_url' => 'http://'.$_SERVER['HTTP_HOST'].'/general-pay/alipay-web-notify',
        'return_url' => 'http://'.$_SERVER['HTTP_HOST'].'/general-pay/alipay-web-return',
    ],
    'alipay_app_config'=>[
        'partner' => '2088801136967007',   //合作身份者id，以2088开头的16位纯数字
        //'key' => 'ptd4lbjltmwpx64g80qhil5eckr98opf'，//安全检验码，以数字和字母组成的32位字符
        'seller_email' => '47632990@qq.com', //签约支付宝账号或卖家支付宝帐户
        'private_key_path' => '/alipay_app/rsa_private_key.pem',  //商户的私钥（后缀是.pen）文件相对路径
        'ali_public_key_path' => '/alipay_app/alipay_public_key.pem',   //支付宝公钥（后缀是.pen）文件相对路径
        'sign_type' => strtoupper('RSA'), //签名方式 不需修改
        'input_charset' => strtolower('utf-8'),   //字符编码格式 目前支持 gbk 或 utf-8
        'cacert' => 'alipay_app/cacert.pem',    //ca证书路径地址，用于curl中ssl校验 //请保证cacert.pem文件在当前文件夹目录中
        'transport' => 'http',    //访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
        'notify_url' => 'http://'.$_SERVER['HTTP_HOST'].'/general-pay/alipay-app-notify',
        'return_url' => 'http://'.$_SERVER['HTTP_HOST'].'/general-pay/alipay-app-return',
    ],
    'up_app_config'=>[
        'SDK_CVN2_ENC' => 0,   // cvn2加密 1：加密 0:不加密
        'SDK_DATE_ENC' => 0,   // 有效期加密 1:加密 0:不加密
        'SDK_PAN_ENC' => 0,   // 卡号加密 1：加密 0:不加密
        'SDK_SIGN_CERT_PATH' => 'D:/certs/PM_700000000000001_acp.pfx',   // 签名证书路径
        'SDK_SIGN_CERT_PWD' => '000000',   // 签名证书密码
        'SDK_ENCRYPT_CERT_PATH' => 'D:/certs/verify_sign_acp.cer',   // 密码加密证书（这条用不到的请随便配）
        'SDK_VERIFY_CERT_DIR' => 'D:/certs/',   // 验签证书路径（请配到文件夹，不要配到具体文件）
        'SDK_FRONT_TRANS_URL' => 'https://101.231.204.80:5000/gateway/api/frontTransReq.do',   // 前台请求地址
        'SDK_BACK_TRANS_URL' => 'https://101.231.204.80:5000/gateway/api/backTransReq.do',  // 后台请求地址
        'SDK_BATCH_TRANS_URL' => 'https://101.231.204.80:5000/gateway/api/batchTrans.do',   // 批量交易
        'SDK_SINGLE_QUERY_URL' => 'https://101.231.204.80:5000/gateway/api/queryTrans.do',  //单笔查询请求地址
        'SDK_FILE_QUERY_URL' => 'https://101.231.204.80:9080/', //文件传输请求地址
        'SDK_Card_Request_Url' => 'https://101.231.204.80:5000/gateway/api/cardTransReq.do',    //有卡交易地址
        'SDK_App_Request_Url' => 'https://101.231.204.80:5000/gateway/api/appTransReq.do',  //App交易地址
        'SDK_FRONT_NOTIFY_URL' => 'http://localhost:8085/upacp_sdk_php/demo/utf8/FrontReceive.php', // 前台通知地址 (商户自行配置通知地址)
        'SDK_BACK_NOTIFY_URL' => 'http://114.82.43.123/upacp_sdk_php/demo/utf8/BackReceive.php',    // 后台通知地址 (商户自行配置通知地址)
        'SDK_FILE_DOWN_PATH' => 'd:/file/', //文件下载目录
        'SDK_LOG_FILE_PATH' => 'd:/logs/',  //日志 目录
        'SDK_LOG_LEVEL' => 'INFO',  //日志级别
        'notify_url' => 'http://'.$_SERVER['HTTP_HOST'].'/general-pay/alipay_app_notify',
        'return_url' => 'http://'.$_SERVER['HTTP_HOST'].'/general-pay/alipay-app-return',
    ],
    'wx_app_config'=>[
        'partner' => '2088801136967007',   //合作身份者id，以2088开头的16位纯数字
        //'key' => 'ptd4lbjltmwpx64g80qhil5eckr98opf'，//安全检验码，以数字和字母组成的32位字符
        'seller_email' => '47632990@qq.com', //签约支付宝账号或卖家支付宝帐户
        'private_key_path' => '/alipay_app/rsa_private_key.pem',  //商户的私钥（后缀是.pen）文件相对路径
        'ali_public_key_path' => '/alipay_app/alipay_public_key.pem',   //支付宝公钥（后缀是.pen）文件相对路径
        'sign_type' => strtoupper('RSA'), //签名方式 不需修改
        'input_charset' => strtolower('utf-8'),   //字符编码格式 目前支持 gbk 或 utf-8
        'cacert' => 'alipay_app/cacert.pem',    //ca证书路径地址，用于curl中ssl校验 //请保证cacert.pem文件在当前文件夹目录中
        'transport' => 'http',    //访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
        'notify_url' => 'http://'.$_SERVER['HTTP_HOST'].'/general-pay/wx-app-notify',
    ],'wx_h5_config'=>[
        'appid' => 'wx7558e67c2d61eb8f',
        'mchid' => '10037310',
        'key' => '/dd2a3bbc7ce06c7fcb441afdbc9b457f',
        'appsecret' => '31618c8be9545189e662adb38f2c3ec5',
        'SSLCERT_PATH' => '../cert/apiclient_cert.pem',
        'SSLKEY_PATH' => '../cert/apiclient_key.pem',
        'CURL_PROXY_HOST' => '0.0.0.0',
        'CURL_PROXY_PORT' => 0,
        'REPORT_LEVENL' => 1,
        'notify_url' => 'http://'.$_SERVER['HTTP_HOST'].'/general-pay/wx-h5-notify',
    ],



];
