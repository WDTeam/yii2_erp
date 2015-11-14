<?php
/**
 * @author CoLee
 */
namespace console\controllers;

use yii\console\Controller;

class ImportDataController extends Controller
{
    public function actionIndex()
    {
        return true;
        $db = \Yii::$app->db;
        //导入 shop_manager
        $rows = $db->createCommand(
            "select * from imp_shop_manager"    
        )->queryAll();
        foreach ($rows as $row){
            $province_name = $row['province_name'];
            $row['province_id'] = $db->createCommand("
                select id from ejj_operation_area
                where area_name like '{$province_name}%' 
                AND level=1
            ")->queryScalar();
            $row['city_id'] = $db->createCommand("
                select id from ejj_operation_area
                where area_name like '{$row['city_name']}%'
                AND level=2
            ")->queryScalar();
            $row['county_id'] = $db->createCommand("
                select id from ejj_operation_area
                where area_name like '{$row['county_name']}%'
                AND level=2
            ")->queryScalar();
            unset($row['username']);
            unset($row['password']);
            unset($row['province_name']);
            unset($row['city_name']);
            unset($row['county_name']);
            $res = $db->createCommand()->insert('{{%shop_manager}}', $row)->execute();
            var_dump($res);
        }
    }
    
    public function actionShop()
    {
        return true;
        $db = \Yii::$app->db;
        // 导入 shop
        $rows = $db->createCommand(
            "select * from imp_shop"
        )->queryAll();
        foreach ($rows as $row){
            $province_name = $row['province_name'];
            $row['province_id'] = $db->createCommand("
                select id from ejj_operation_area
                where area_name like '{$province_name}%'
                AND level=1
                ")->queryScalar();
            $row['city_id'] = $db->createCommand("
                select id from ejj_operation_area
                where area_name like '{$row['city_name']}%'
                AND level=2
                ")->queryScalar();
            $row['county_id'] = $db->createCommand("
                select id from ejj_operation_area
                where area_name like '{$row['county_name']}%'
                AND level=2
                ")->queryScalar();
            unset($row['username']);
            unset($row['password']);
            unset($row['province_name']);
            unset($row['city_name']);
            unset($row['county_name']);
        
            if(empty($row['shop_manager_id'])){
                $row['shop_manager_id'] = $row['name'];
            }
            $row['shop_manager_id'] = $db->createCommand("
            select id from {{%shop_manager}} where name='{$row['shop_manager_id']}'
            ")->queryScalar();
        
            $res = $db->createCommand()->insert('{{%shop}}', $row)->execute();
            var_dump($res);
        }
    }
    
    public function actionUser()
    {
        return true;
        $db = \Yii::$app->db;
        $rows = $db->createCommand(
            "select * from imp_user where is_used=1"
        )->queryAll();
        foreach ($rows as $row){
            $row['id'] = $row['ID'];
            $row['email'] = empty($row['email'])?null:$row['email'];
            unset($row['ID']);
            unset($row['role']);
            unset($row['is_used']);
            $res = $db->createCommand()->insert('{{%system_user}}', $row)->execute();
            var_dump($res);
        }
    }
    
    public function actionIndexceshi()
    {
    	//导入到新表
    	$userinfoceshi= new \dbbase\models\operation\CouponUserinfoceshi;
    	 
    	$data=$userinfoceshi->find()
    	//->where(['coupon_userinfo_id'=>2])
    	->limit(20000)
    	->asArray()
    	->all();
    
    			foreach ($data as $key=>$newdata){
    			$newcoupon=new \dbbase\models\operation\coupon\CouponUserinfo;
    			//领取的优惠券
    			if($newdata['city_id']==0){
    			//全网优惠券
    			$newcoupon->couponrule_city_limit=1;
    			$newcoupon->couponrule_city_id=0;
    			}else{
    			//地区优惠券
    			$newcoupon->couponrule_city_limit=2;
    			$newcoupon->couponrule_city_id=$newdata['city_id'];
    			}
    			if($newdata['order_typeid']==0){
    			//全国通用优惠券
    			$newcoupon->couponrule_type=1;
					$newcoupon->couponrule_service_type_id=0;
    							$newcoupon->couponrule_commodity_id=0;
    			}else{
    			//不是全网优惠券 对应老数据就是商品优惠券，老数据没有，类别优惠券
    			$newcoupon->couponrule_type=3;
    			$newcoupon->couponrule_service_type_id=0;
    				$newcoupon->couponrule_commodity_id=$newdata['order_typeid'];
    			}
    
    			$newcoupon->coupon_userinfo_code=$newdata['coupon_userinfo_code']?$newdata['coupon_userinfo_code']:'0';
    			$newcoupon->coupon_userinfo_name=$newdata['coupon_userinfo_name']?$newdata['coupon_userinfo_name']:'优惠券';
    					$newcoupon->coupon_userinfo_gettime=$newdata['coupon_userinfo_gettime'];//领取时间默认为开始时间
    					$newcoupon->couponrule_use_start_time=$newdata['coupon_userinfo_gettime'];
    					$newcoupon->couponrule_use_end_time=$newdata['couponrule_use_end_time'];
    					$newcoupon->coupon_userinfo_price=$newdata['coupon_userinfo_price'];
    					$newcoupon->customer_tel=$newdata['customer_tel'];
    					///////////////////////////////////////////
    
    		$newcoupon->couponrule_order_min_price=0;
        		$newcoupon->customer_id=0;
    					$newcoupon->coupon_userinfo_id=0;
    					$newcoupon->coupon_userinfo_usetime=0;
    					$newcoupon->couponrule_classify=1;
    					$newcoupon->couponrule_category=1;
    					$newcoupon->couponrule_customer_type=1;
    					$newcoupon->couponrule_use_end_days=0;
    					$newcoupon->couponrule_promote_type=1;
    					$newcoupon->couponrule_price=50;
    					$newcoupon->order_code='0';
    					$newcoupon->is_disabled=0;
    					$newcoupon->system_user_id=0;
    					$newcoupon->system_user_name='老数据导入';
    					$newcoupon->is_used=0;
    							$newcoupon->created_at=time();
    							$newcoupon->updated_at=time();
    							$newcoupon->is_del=0;
    							$islcok=$newcoupon->save();
    							if($islcok){
    							$model = $userinfoceshi->findOne($newdata['id']);
    							if($model){
    								$model->delete();
    							}
    							
    }else{
    							//失败记录日志
    							file_put_contents('log.txt',json_encode($newcoupon)."\n",FILE_APPEND);
    							}
    							unset($newcoupon);
    							
    							echo  $key.'--';
    							
    							}
    							 
    							 
    							 
    							var_dump('11');exit;
   
    							 
    							 
    							}
}