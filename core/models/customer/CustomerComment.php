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
use core\models\order\OrderComplaint;
class CustomerComment extends \common\models\customer\CustomerComment
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
    **/
    
    
    public static function getCustomerCommentworkerlist($worker_id,$customer_comment_level,$newpage,$countpage=40)
    {
    	if($newpage==0 && $newpage=='' && $newpage==null){
    		$newpage=1;
    	}else{
    		$newpage=$newpage*$countpage;
    	}
    	$comment_list = self::find()->andWhere(['worker_id' =>$worker_id,'customer_comment_level'=>$customer_comment_level])->limit($newpage,$countpage)->asArray()->all();
    	return $comment_list;
    }
    
    
    
    /**
	  * 增加评价
	  * @date: 2015-10-27
	  * @author: peak pan
	  * @return:
	  **/
    
    
    public static function addUserSuggest($array)
    {
       
    	if(count($array)>0){
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $customerComment = new CustomerComment;
            $customerComment->order_id = $array['order_id'];
            $customerComment->customer_id = $array['customer_id'];

            $customerComment->worker_id = $array['worker_id'];
            $customerComment->worker_tel = $array['worker_tel'];
            $customerComment->operation_shop_district_id = $array['operation_shop_district_id'];//商圈id
            $customerComment->province_id = $array['province_id'];
            $customerComment->city_id = $array['city_id'];
            $customerComment->county_id = $array['county_id'];
            $customerComment->customer_comment_phone = $array['customer_comment_phone'];;
            $customerComment->customer_comment_content = $array['customer_comment_content'];
            $customerComment->customer_comment_level = $array['customer_comment_level'];
            $customerComment->customer_comment_level_name = $array['customer_comment_level_name'];
            $customerComment->customer_comment_tag_ids = $array['customer_comment_tag_ids'];
            $customerComment->customer_comment_tag_names = $array['customer_comment_tag_names'];
            $customerComment->customer_comment_anonymous = $array['customer_comment_anonymous'];
            $customerComment->created_at =time();
            $customerComment->updated_at =time();
            $customerComment->is_del = 1;
            $customerComment->save();
           // var_dump($customerComment->errors);
            $transaction->commit();
            
            if($array['customer_comment_level']=='3'){
            	//如果是差评 通知投诉接口
            	$data['order_id']=$array['order_id'];
            	$data['worker_id']=$array['worker_id'];
            	$data['complaint_type']=1;
            	$data['complaint_status']=2;
            	$data['complaint_channel']=1;
            	$data['complaint_phone']=$array['worker_tel'];
            	$data['complaint_section']=0;
            	$data['complaint_level']=3;
            	$data['complaint_content']=$array['customer_comment_content'];
            	$data['complaint_time']=time();
            	//提交给投诉接口
            	OrderComplaint::appModel($data);
            }
            
            return $customerComment;
        		} catch (\Exception $e) {
            $transaction->rollback();
            return false;
	        }
			} else{
	        	return false;
	        } 
	    }


  
    public static function issetes($array)
    {
    	 
    		$transaction = \Yii::$app->db->beginTransaction();
    		try {
    			$customerComment = new CustomerComment;
    			$customerComment->order_id = $array['order_id'];
    			$customerComment->validate();//安全验证
    			$customer_info = $customerComment::findOne($customerComment);
    			if($customer_info){
    				return $customer_info;
    			}else{
    				return false;
    			}	
    			
    		} catch (\Exception $e) {
    			$transaction->rollback();
    			return false;
    		}
    }
    
    
    
    
    





}
