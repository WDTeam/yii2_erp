<?php
/**
 * Created by PhpStorm.
 * User: lsq
 * Date: 15-10-16
 * Time: 下午7:57
 */
require_once dirname(__FILE__)."/common.php";
class zhidahao_refund_class
{
    public function __construct(){}

    public function refund($param)
    {
        //初始化参数
        $params['refund_url'] = $param['refund_url'];
        if( !empty($param['order_id']) ){
            $params['order_id'] = $param['order_id'];
        }
        if( !empty($param['order_no']) ){
            $params['order_no'] = $param['order_no'];
        }

        $result = zhidahao::refundOrder($params);
        if($result['result'] == '1'){
            return ['status'=>1,'msg'=>'退款成功','result'=>$result];
        }else{
            return ['status'=>0,'msg'=>'退款失败','result'=>$result];
        }

    }

}