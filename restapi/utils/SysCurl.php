<?php
namespace api\utils;
use Yii;
/**
 * Curl 访问
 */
class SysCurl {

    public static $ch;
    public static $timeout = 5;
    public static $errno;
    public static $error;
    public static function init($url){
        //初始化
        self::$ch = curl_init();
        //设置选项，包括URL
        curl_setopt(self::$ch, CURLOPT_URL, $url);
        curl_setopt(self::$ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt(self::$ch, CURLOPT_TIMEOUT, self::$timeout);
        curl_setopt(self::$ch, CURLOPT_HEADER, 0);
    }
    public static function httpPost($url,$data,$isJson=true){
        self::init($url);
        curl_setopt(self::$ch, CURLOPT_HTTPHEADER , json_decode($data['HTTPHEADER'],true));
        //设置POST方式
        curl_setopt(self::$ch, CURLOPT_POST, 1);
        //设置POST数据
        curl_setopt(self::$ch, CURLOPT_POSTFIELDS, $data);
        //执行调用
        $output = curl_exec(self::$ch);
        //检查执行状态
        if(!$output){
            $ErrorCode = curl_errno(self::$ch);
            if($ErrorCode === 28){
                exit('Http TimeOut!');
            }else{
                exit('Http Error, '.$ErrorCode);
            }
            return false;
        }
        //释放curl句柄
        curl_close(self::$ch);
        //返回数据
        $result = $isJson ? json_decode($output,true) : $output;
        return $result;
    }
    public static function httpGet($url,$data,$isJson=true){
        $http_header = $data['HTTPHEADER'];
        unset($data['HTTPHEADER']);
        if(!empty($data)){
            $getVar = http_build_query($data);
            $url = stristr($url,'?') ? $url.'&'.$getVar : $url.'?'.$getVar;
        }
        self::init($url);
        curl_setopt(self::$ch, CURLOPT_HTTPHEADER , json_decode($http_header,true));
        //执行调用
        $output = curl_exec(self::$ch);
        //检查执行状态
        if(!$output){
            $ErrorCode = curl_errno(self::$ch);
            if($ErrorCode === 28){
                exit('Http TimeOut!');
            }else{
                exit('Http Error, '.$ErrorCode);
            }
            return false;
        }
        //释放curl句柄
        curl_close(self::$ch);
        //返回数据
        $result = $isJson ? json_decode($output,true) : $output;
        return $result;
    }
}