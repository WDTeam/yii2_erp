<?PHP
include_once 'bfb_pay.cfg.php';
include_once 'bfb_sdk.php';
define('ROOT_PATH',dirname(__FILE__));

class bfbpay_class
{
    public function __construct(){
        $this->obj = new bfb_sdk();
    }
    /**
     * 请求数据
     * $param 请求数据
     */
    public function get($param){
        //构造要请求的参数数组，无需改动
        $parameter = array(
            'service_code' => sp_conf::BFB_PAY_INTERFACE_SERVICE_ID,        //服务编号
            'sp_no' => sp_conf::SP_NO,                                      //百度钱包商户号
            'sp_request_type' => 2,                                         //0代表免登陆收银台，1代表登陆版收银台，2代表统一收银台
            'order_create_time' => date("YmdHis"),                          //创建订单的时间
            'order_no' => 'e'.mt_rand(1000000,9999999),//$param['out_trade_no'],                           //订单ID,
            'goods_name' => $param['subject'],//iconv("UTF-8", "GBK", urldecode($param['subject'])),     //商品的名称
            'goods_desc' => $param['body'],//iconv("UTF-8", "GBK", urldecode($param['body'])),        //商品的描述信息
            'total_amount' => $param['general_pay_money'],   //总金额，以分为单位
            'currency' => sp_conf::BFB_INTERFACE_CURRENTCY,                         //币种，默认人民币
            'return_url' => $param['notify_url'],                                   //百度钱包主动通知商户支付结果的URL
            'pay_type' => 2,                                                        //支付方式，默认2
            'input_charset' => sp_conf::BFB_INTERFACE_ENCODING,                     //请求参数的字符编码
            'version' => sp_conf::BFB_INTERFACE_VERSION,                            //接口的版本号，必须2
            'sign_method' => sp_conf::SIGN_METHOD_MD5,                              //签名方法
            'key' => 'jukANpzsPnuLVudFwmGJfiyD3NcrEWvX'
        );
        return $parameter;
    }

    /**
     *  回掉数据验证
     */
    public function callback(){
        return $this->obj->check_bfb_pay_result_notify();
    }
    /**
     * 返回状态
     */
    public function notify(){
        $this->obj->notify_bfb();
    }
}
