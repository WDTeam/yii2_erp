<?php
return [
    'adminEmail' => 'admin@1jiajie.com',
    'supportEmail' => 'support@1jiajie.com',
    'user.passwordResetTokenExpire' => 3600,
    /**
     * 配置财务数字显示
     *  add by zhanghang 2015-09-22
     */
    'maskMoneyOptions' => [
        'prefix' => '¥ ', // ¥ 在HTML，“¥”的命名实体是“&yen;”，x字符代码是“&#165;”和“&#xA5;”;
        'suffix' => '',
        'affixesStay' => true,
        'thousands' => ',',
        'decimal' => '.',
        'precision' => 2, 
        'allowZero' => false,
        'allowNegative' => false,
    ],
];
