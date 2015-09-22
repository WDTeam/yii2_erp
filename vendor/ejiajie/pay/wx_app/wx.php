<?php
define('WX_PATH',dirname(__FILE__));
if (!class_exists('WxPayApi', false)) { include(WX_PATH.'/WxPay.Api.php'); }
if (!class_exists('JsApiPay', false)) { include(WX_PATH.'/WxPay.JsApiPay.php'); }



