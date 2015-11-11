<?php

/**
 * 评价api接口
 * ==========================
 * 北京一家洁 版权所有 2015-2018 
 * ----------------------------
 * 这不是一个自由软件，未经授权不许任何使用和传播。
 * ==========================
 * @date: 2015-10-27
 * @author: peak pan 
 * @version:1.0
 */

namespace core\models\customer;

use Yii;
use core\models\order\Order;
use core\models\comment\CustomerCommentTag;
use core\models\comment\CustomerCommentLevel;
use core\models\order\OrderComplaint;


class CustomerComment extends \dbbase\models\customer\CustomerComment
{

    /**
     * 获取客户评价数量
     */
    public static function getCustomerCommentCount($customer_id)
    {
        $comment_count = self::find()->where(['customer_id' => $customer_id])->count();
        return $comment_count;
    }

    /**
     * 根据阿姨的id获取评价列表
     * @date: 2015-10-27
     * @author: peak pan
     * @return:
     * */
    public static function getCustomerCommentworkerlist($worker_id, $customer_comment_level, $newpage, $countpage = 40)
    {
        $current_page = intval($newpage)>0?intval($newpage):1;
        $newpage = ($current_page - 1) * $countpage;
        $comment_list = self::find()->where(['worker_id' => $worker_id, 'customer_comment_level' => $customer_comment_level])->offset($newpage)->limit($countpage)->asArray()->all();
        return $comment_list;
    }

    /**
     * 获取阿姨评论统计信息
     * @param $worker_id
     * @return array
     */
    public static function getWorkerCommentCount($worker_id){
        $commentLevel = CustomerCommentLevel::find()->where(['is_del'=>0])->select('customer_comment_level,customer_comment_level_name')->asArray()->all();
        $workerCommentResult = [];
        foreach ($commentLevel as $val) {
            $level_count = self::find()->where(['customer_comment_level'=>$val['customer_comment_level'],'worker_id'=>$worker_id])->count();
            $workerCommentResult[] = [
                'level' => $val['customer_comment_level'],
                'level_name'=>$val['customer_comment_level_name'],
                'level_count'=>$level_count
            ];
        }
        return $workerCommentResult;
    }
    
    /**
     * 根据阿姨的id,开始时间和结束时间获取评价列表
     * @date: 2015-10-27
     * @author: peak pan
     * @return:
     * */
    public static function getCommentworkercount($worker_id,$statime,$endtime)
    {
    	
    	$comment_list = self::find()
    	->andWhere(['worker_id' => $worker_id])
    	->andFilterWhere(['between', 'created_at',$statime,$endtime])
    	->count();
    	return $comment_list;
    }
    
    
    
    /**
     *  增加评价
     * $adminid= 系统评价是1  客户评价是0  财务评价对应的是财务的人员
     * @apiParam {int} order_id       '订单ID'
     * @apiParam {int}  worker_id      '阿姨id'
     * @apiParam {int}  customer_id    '用户id'
     * @apiParam {String} worker_tel  '阿姨电话'
     *
     * @apiParam {int}    operation_shop_district_id '商圈id'
     * @apiParam {int}   province_id    '省id'
     * @apiParam {int}   city_id        '市id'
     * @apiParam {int}   county_id      '区id'
     * @apiParam {String} customer_comment_phone    '用户电话'
     *
     * @date: 2015-10-27
     * @author: peak pan
     * @return:
     * */
    
    public static function autoaddUserSuggest($array)
    {
    	$array['customer_comment_content']='满意';
    	$array['customer_comment_level']=1;
    	$array['customer_comment_level_name']='满意';
    	$array['customer_comment_tag_ids']='1';
    	$array['customer_comment_tag_names']='系统确定满意';
    	$array['customer_comment_anonymous']=1;
    	$array['adminid']=1;
    	self::addUserSuggest($array);
    }
    
    /**
     *  增加评价
     *  $adminid
     * @apiParam {int} adminid       '1 系统自动提交评论，2下单人提交评价'
     * @apiParam {int} order_id       '订单ID'
     * @apiParam {int}  worker_id      '阿姨id'
     * @apiParam {int}  customer_id    '用户id'
     * @apiParam {String} worker_tel  '阿姨电话'
     * 
     * @apiParam {int}    operation_shop_district_id '商圈id'
     * @apiParam {int}   province_id    '省id'
     * @apiParam {int}   city_id        '市id'
     * @apiParam {int}   county_id      '区id'
     * 
     * @apiParam {String} customer_comment_phone    '用户电话'
     * @apiParam {String} customer_comment_content  '评论内容'
     * @apiParam {int}    customer_comment_level       '评论等级'
     * @apiParam {String} customer_comment_level_name '评价等级名称'
     * 
     * @apiParam {String} customer_comment_tag_ids  '评价标签'
     * @apiParam {String} customer_comment_tag_names '评价标签名称'
     * @apiParam {int}    customer_comment_anonymous  是否匿名评价,0匿名,1非匿名'
     * 
     * @date: 2015-10-27
     * @author: peak pan
     * @return:
     * */
    public static function addUserSuggest($array)
    {

    	
        if (count($array) > 0) {
        	$dateinfo=OrderComplaint::ComplaintTypes();
        	$dateinfo_key=$dateinfo[1];
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $customerComment = new CustomerComment;
                $customerComment->order_id = $array['order_id'];
                $customerComment->customer_id = $array['customer_id'];
                $customerComment->worker_id = $array['worker_id'];
                $customerComment->worker_tel = $array['worker_tel'];
                $customerComment->operation_shop_district_id = $array['operation_shop_district_id']; //商圈id
                $customerComment->province_id = $array['province_id'];
                $customerComment->city_id = $array['city_id'];
                $customerComment->county_id = $array['county_id'];
                
                $customerComment->customer_comment_phone = $array['customer_comment_phone'];
                $customerComment->customer_comment_content = $array['customer_comment_content'];
                $customerComment->customer_comment_level = $array['customer_comment_level'];
                $customerComment->customer_comment_level_name = $array['customer_comment_level_name'];
                $customerComment->customer_comment_tag_ids = $array['customer_comment_tag_ids'];
                $customerComment->customer_comment_tag_names = $array['customer_comment_tag_names'];
                $customerComment->customer_comment_anonymous = $array['customer_comment_anonymous'];
                $customerComment->created_at = time();
                $customerComment->updated_at = time();
                $customerComment->is_del = 1;
                $customerComment->save();
                // var_dump($customerComment->errors);
              
                
                
                //增加使用次数
               $arraydate= explode(',',$array['customer_comment_tag_ids']);
               
              
                foreach ($arraydate as $ted){
                    
                   
                    
                	$dateinfo=CustomerCommentTag::findOne($ted);
                       
                       // var_dump($dateinfo);exit;
                        if(isset($dateinfo->customer_tag_count)){ 
                       $dateinfo->customer_tag_count=$dateinfo->customer_tag_count+1;
                	$dateinfo->save();     
                        }
     
                	unset($dateinfo);
                }

				//通知订单 ，改变订单状态
		if(!isset($array['adminid'])){$array['adminid']=0;}
                Order::customerAcceptDone($array['order_id'],$array['adminid']); 
                
                if ($array['customer_comment_level'] == '3') {
                    //如果是差评 通知投诉接口
                    $data['order_id'] = $array['order_id'];
                    $data['complaint_type'] = 1;
                    $data['complaint_status'] = 1;
                    $data['complaint_channel'] = 1;
                    $data['complaint_phone'] = $array['customer_comment_phone'];
                    $data['complaint_section'] = 1;
                    $data['complaint_assortment'] =array_search($array['customer_comment_level_name'],$dateinfo_key);
                    $data['complaint_level'] = 0;
                    $data['complaint_content'] = $array['customer_comment_content'];
                    $data['complaint_time'] = time();
                    $data['updated_at'] = time();
                    $data['created_at'] = time();
                    $data['is_softdel'] = 1;
                    //流水号
                    $data['order_code'] ='06'.time();
                    //提交给投诉接口
                    $OrderComplaintinfo=new OrderComplaint;
                    $OrderComplaintinfo->appModel($data);
                }
                $transaction->commit();
                return true; 
            } catch (\Exception $e) {
                $transaction->rollback();
                return false;
            }
        } else {
            return false;
        }
    }

    
    
    
    /**
    *  根据评价的order_id获取评价详情
    * @date: 2015-10-30
    * @author: peak pan
    * @return:
    **/
   
    public static function issetes($order_id)
    {

        $transaction = \Yii::$app->db->beginTransaction();
	     try {
            $condition['order_id']=$order_id;
            $date=self::find()->where($condition)->one();
            return $date;
            
       	} catch (\Exception $e) {
       		
            $transaction->rollback();
            return false;
            
	    }
    }

}
