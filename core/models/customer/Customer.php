<?php
namespace core\models\customer;

use Yii;
use yii\base\Exception;
use yii\web\BadRequestHttpException;
use yii\helpers\ArrayHelper;

use dbbase\models\customer\GeneralRegion;
use dbbase\models\customer\CustomerExtSrc;
use core\models\worker\Worker;
use dbbase\models\customer\CustomerFeedback;
use dbbase\models\customer\CustomerExtBalanceRecord;

use core\models\customer\CustomerAddress;
use core\models\customer\CustomerWorker;
use core\models\customer\CustomerExtBalance;
use core\models\customer\CustomerExtScore;
use core\models\finance\FinanceOrderChannal;
use core\models\operation\OperationCity;
use core\models\operation\OperationOrderChannel;


class Customer extends \dbbase\models\customer\Customer
{

    /**
     * add customer while customer is not exist by phone
     * @param $phone
     * @param $channal_id
     * @return bool
     */
    public static function addCustomer($phone, $channal_id){
		$customer = self::find()->where(['customer_phone'=>$phone])->one();
		if($customer != NULL){
			return false;
		}else if(!preg_match('/^1([0-9]{9})/', $phone)){
			return false;
		}else{
			$transaction = \Yii::$app->db->beginTransaction();
			try{
				//customer basic info
				$customer = new Customer;
				$customer->customer_phone = $phone;
				$customer->created_at = time();
				$customer->updated_at = 0;
				$customer->is_del = 0;
				$customer->save();
			
//				customer balance
//				$customerExtBalance = new CustomerExtBalance;
//				$customerExtBalance->customer_id = $customer->id;
//				$customerExtBalance->customer_phone = $phone;
//				$customerExtBalance->customer_balance = 0;
//				$customerExtBalance->created_at = time();
//				$customerExtBalance->updated_at = 0;
//				$customerExtBalance->is_del = 0;
//				$customerExtBalance->save();

				//customer score
//				$customerExtScore = new CustomerExtScore;
//				$customerExtScore->customer_id = $customer->id;
//				$customerExtScore->customer_phone = $phone;
//				$customerExtScore->customer_score = 0;
//				$customerExtScore->created_at = time();
//				$customerExtScore->updated_at = 0;
//				$customerExtScore->is_del = 0;
//				$customerExtScore->save();

				$add_src_res = self::addSrcByChannalId($phone, $channal_id);
                if(!$add_src_res){
                    throw new Exception('新增客户注册来源失败');
                }
				
				$transaction->commit();
				return true;
			}catch(\Exception $e){

				$transaction->rollback();
				return false;
			}
		}
	}

    /**
     * get customer information by customer id
     * @param $customer_id
     * @return bool|null|static
     */
    public static function getCustomerById($customer_id)
    {
        $customer = Customer::findOne($customer_id);

        return $customer != NULL ? $customer : false;
    }

    /**
     * get customer basic information of which customer phone and customer balance contain by phone
     * @param $phone
     * @return array|bool
     */
    public static function getCustomerInfo($phone)
    {
        $customer = Customer::find()->where(array(
            'customer_phone'=>$phone,
            ))->one();
        if ($customer == NULL) {
            return false;
        }
        
        $customerBalance = CustomerExtBalance::find()->where(array(
            'customer_id'=>$customer->id,
            ))->one();
         print_r($customerBalance);
                exit;
        // if ($customerBalance == NULL) {
        //     $customer_balance = 0;
        // }
        // $customer_balance = $customerBalance->customer_balance;
        $customer_balance = $customerBalance == NULL ? 0 : $customerBalance->customer_balance;
        return array(
            'id'=>$customer->id,
            'customer_phone'=>$phone,
            'customer_balance'=>$customer_balance,
            );
    }

    /*****************************customer exists*********************************************/
    /**
     * @param $customer_id
     * @return bool
     */
    public static function customerExistById($customer_id){
        $count = self::find()->where(['id'=>$customer_id])->count();
        return $count > 0;
    }

    /**
     * @param $phone
     * @return bool
     */
    public static function customerExistByPhone($phone){
        $count = self::find()->where(['customer_phone'=>$phone])->count();
        return $count > 0;
    }
/*********************************phone and id***********************************************/

    /**
     * get customer phone by id
     * @param $customer_id
     * @return string
     */
    public static function getCustomerPhoneById($customer_id)
    {
        $customer = self::find()->select(['customer_phone'])->where(['id'=>$customer_id])->one();
        return $customer->customer_phone;
    }

    /**
     * get customer id by phone
     * @param $customer_phone
     * @return int
     */
    public static function getCustomerIdByPhone($customer_phone){
        $customer = self::find()->select(['id'])->where(['customer_phone'=>$customer_phone])->one();
        return $customer->id;
    }

    
	/******************************************basic**********************************************/
    /**
     * get customer vip types
     * @return array
     */
    public static function getVipTypes(){
		return [0=>'非会员', 1=>'会员'];
	}

    /**
     * get vip type name by type
     * @param $vip_type
     * @return string
     */
    public static function getVipTypeName($vip_type){
		$vip_type_name = '';
		switch ($vip_type)
		{
			case 0:
				$vip_type_name = '非会员';
			break;
			case 1:
				$vip_type_name = '会员';
			break;
			
			default:
				# code...
			break;
		}
		return $vip_type_name;
	}

    /**
     * get customer vip information by phone
     * @param $phone
     * @return array
     * @throws NotFoundHttpException
     */
    public static function getVipInfoByPhone($phone){
		$customer = self::find()->where(['customer_phone'=>$phone])->asArray()->one();
		if(empty($customer)) throw new NotFoundHttpException;
		
		$vip_types = self::getVipTypes();
		$vip_type = $customer['customer_is_vip'];
		$vip_type_name = $vip_types[$vip_type];
		return ['vip_type'=>$vip_type, 'vip_type_name'=>$vip_type_name];
	}

	/*******************************************balance******************************************/
    /**
     * get customer balance by phone
     * @param $phone
     * @return array
     */
    public static function getBalance($phone){
		$customer = self::find()->where(['customer_phone'=>$phone])->asArray()->one();
		if(empty($customer)){
			return ['response'=>'error', 'errcode'=>'1', 'errmsg'=>'客户不存在'];
		}

		$customer_ext_balance = CustomerExtBalance::find()->where(['customer_phone'=>$phone])->asArray()->one();
		if(empty($customer_ext_balance)) {
			return ['response'=>'success', 'errcode'=>'0', 'errmsg'=>'', 'balance'=>0];
		}
		return ['response'=>'success', 'errcode'=>'0', 'errmsg'=>'', 'balance'=>$customer_ext_balance['customer_balance']];
	}

    /**
     * get customer balance by customer id
     * @param $customer_id
     * @return array
     */
    public static function getBalanceById($customer_id){
        $customer = Customer::findOne($customer_id);
        if ($customer == NULL) {
			return ['response'=>'error', 'errcode'=>'1', 'errmsg'=>'客户不存在'];
		}
        $customerExtBalance = CustomerExtBalance::find()->where(['customer_id'=>$customer_id])->one();
        if ($customerExtBalance == NULL) {
			return ['response'=>'success', 'errcode'=>'0', 'errmsg'=>'', 'balance'=>0];
		}
        return ['response'=>'success', 'errcode'=>'0', 'errmsg'=>'', 'balance'=>$customerExtBalance['customer_balance']];
    }

    /**
     * add balance data, default 0 while balance is not provided
     * @param $customer_id
     * @param int $customer_balance
     * @return array
     */
    public static function addBalance($customer_id, $customer_balance = 0){
        $customer_phone = self::getCustomerPhoneById($customer_id);
        if($customer_phone == NULL){
            return ['response'=>'error', 'errcode'=>1, 'errmsg'=>'该客户不存在'];
        }
        $customerExtBalance = new CustomerExtBalance;
        $customerExtBalance->customer_id = $customer_id;
        $customerExtBalance->customer_phone = $customer_phone;
        $customerExtBalance->customer_balance = $customer_balance;
        $customerExtBalance->created_at = time();
        
        $customerExtBalance->updated_at = 0;
        $customerExtBalance->is_del = 0;
        if(!$customerExtBalance->validate()){
            return ['response'=>'error', 'errcode'=>2, 'errmsg'=>'插入数据失败'];
        }
        $customerExtBalance->save();
        return ['response'=>'success', 'errcode'=>0, 'errmsg'=>'', 'id'=>$customerExtBalance->id];
    }

    /**
     * change customer's balance, customer 's last balnce and current balance is availible
     * @param $customer_id
     * @param $operate_balance
     * @param $trans_no
     * @param $operate_type -1为余额退换０为充值１为支付
     * @return array
     */
    public static function operateBalance($customer_id, $operate_balance, $trans_no, $operate_type){
        $customer = self::findOne($customer_id);
        if($customer === NULL){
            return ['respone'=>'error', 'errcode'=>1, 'errmsg'=>'客户不存在'];
        }
        $customerExtBalance = CustomerExtBalance::find()->where(['customer_id'=>$customer_id])->one();
        if($customerExtBalance === NULL){
            $res = Customer::addBalance($customer_id, 0);
            if($res['response'] == 'error'){
                return $res;
            }
            $customerExtBalance = CustomerExtBalance::find()->where(['customer_id'=>$customer_id])->one(); 
        }
        $begin_balance = $customerExtBalance->customer_balance;
        
        $trans_prefix = '';
        switch($operate_type){
            case -1:
                $end_balance = $begin_balance + $operate_balance;
                $operate_type_name = '退款';
                $trans_prefix = '06';
                break;
            case 0:
                $end_balance = $begin_balance + $operate_balance;
                $operate_type_name = '充值';
                $trans_prefix = '10';
                break;
            case 1:
                $end_balance = $begin_balance - $operate_balance;
                $operate_type_name = '支付';
                $trans_prefix = '08';
                break;
            default:
                break;
        }
        
        //generating trans serial
        $trans_serial = $trans_prefix.date('yymmdd', time()).mt_rand(10000000, 99999999);
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $customerExtBalance->customer_balance = $end_balance;
            $customerExtBalance->updated_at = time();
            $customerExtBalance->save();
            $customerExtBalanceRecord = new CustomerExtBalanceRecord;
            $customerExtBalanceRecord->customer_id = $customer->id;
            $customerExtBalanceRecord->customer_phone = $customer->customer_phone;
            $customerExtBalanceRecord->customer_ext_balance_begin_balance = $begin_balance;
            $customerExtBalanceRecord->customer_ext_balance_end_balance = $end_balance;
            $customerExtBalanceRecord->customer_ext_balance_operate_balance = $operate_balance;
            $customerExtBalanceRecord->customer_ext_balance_operate_type = $operate_type;
            $customerExtBalanceRecord->customer_ext_balance_operate_type_name = $operate_type_name;
            $customerExtBalanceRecord->customer_ext_balance_trans_no = $trans_no;
            $customerExtBalanceRecord->customer_ext_balance_trans_serial = $trans_serial;
            $customerExtBalanceRecord->created_at = time();
            $customerExtBalanceRecord->updated_at = 0;
            $customerExtBalanceRecord->is_del = 0;
//            $customerExtBalanceRecord->admin_id = \Yii::$app->user->id;
//            $customerExtBalanceRecord->admin_name = \Yii::$app->user->identity->username;
            $customerExtBalanceRecord->save();
            $formated_date = date('Y年m月d日', time());
            $msg = "【余额变动】您于".$formated_date.$operate_type_name.$operate_balance."，当前余额".$end_balance."元。下载APP（http://t.cn/8schPc6）可以随时查看账户消费记录，如有疑问请联系客服：4006767636。";
            $string = Yii::$app->sms->send($customer->customer_phone, $msg, 1);
            $transaction->commit();
            
            return ['response'=>'success', 'errcode'=>0, 'errmsg'=>'', 
                    'customer_id'=>$customerExtBalanceRecord->customer_id, 
                    'begin_balance'=>$customerExtBalanceRecord->customer_ext_balance_begin_balance, 
                    'end_balance'=>$customerExtBalanceRecord->customer_ext_balance_end_balance, 
                    'operate_balance'=>$customerExtBalanceRecord->customer_ext_balance_operate_balance,
                    'trans_no'=>$customerExtBalanceRecord->customer_ext_balance_trans_no,
                    'trans_serial'=>$customerExtBalanceRecord->customer_ext_balance_trans_serial,];
        }catch(\Exception $e){
            $transaction->rollback();
            return ['response'=>'error', 'errcode'=>3, 'errmsg'=>'操作余额失败'];
        }
    }
    

	/******************************************score**********************************************/
    /**
     * get score by phone
     * @param $phone
     * @return array
     */
    public static function getScore($phone){
		$customer = self::find()->where(['customer_phone'=>$phone])->asArray()->one();
		if(empty($customer)) {
			return ['response'=>'error', 'errcode'=>'1', 'errmsg'=>'客户不存在'];
		}

		$customer_ext_score = CustomerExtScore::find()->where(['customer_phone'=>$phone])->asArray()->one();
		if(empty($customer_ext_score)) {
//            return ['response'=>'error', 'errcode'=>'2', 'errmsg'=>'数据错误'];
			return ['response'=>'success', 'errcode'=>'0', 'errmsg'=>'', 'score'=>0];
		}
		return ['response'=>'success', 'errcode'=>'0', 'errmsg'=>'', 'score'=>$customer_ext_score['customer_score']];
	}

    /**
     * get score by id
     * @param $customer_id
     * @return array
     */
    public static function getScoreById($customer_id){
		$customer = self::findOne($customer_id);
		if(empty($customer)) {
			return ['response'=>'error', 'errcode'=>'1', 'errmsg'=>'客户不存在'];
		}

		$customer_ext_score = CustomerExtScore::find()->where(['customer_id'=>$customer_id])->asArray()->one();
		if(empty($customer_ext_score)) {
//			return ['response'=>'error', 'errcode'=>'2', 'errmsg'=>'数据错误'];
            return ['response'=>'success', 'errcode'=>'0', 'errmsg'=>'', 'score'=>0];
		}
		return ['response'=>'success', 'errcode'=>'0', 'errmsg'=>'', 'score'=>$customer_ext_score['customer_score']];
	}

	/*******************************************src**********************************************/
    /**
     * get all customer srcs
     * @return array|\dbbase\models\customer\CustomerExtSrc[]
     */
    public static function getAllSrcs(){
		$all_srcs = CustomerExtSrc::find()->asArray()->all();
		return $all_srcs;
	}

    /**
     * get customer srcs
     * @param $customer_phone
     * @return array|\dbbase\models\customer\CustomerExtSrc[]
     */
    public static function getSrcs($customer_phone){
		$srcs = CustomerExtSrc::find()->where(['customer_phone'=>$customer_phone])->asArray()->all();
		return $srcs;
	}

    /**
     * get customer first src
     * @param $customer_phone
     * @return array|CustomerExtSrc|null
     */
    public static function getFirstSrc($customer_phone){
		$srcs = CustomerExtSrc::find()->where(['customer_phone'=>$customer_phone])->orderBy('created_at asc')->asArray()->one();
		return $srcs;
	}

    /**
     * add customer src by channal_id
     * @param $customer_phone
     * @param $channal_id
     * @return bool
     */
    public static function addSrcByChannalId($customer_phone, $channal_id){
		$customer = self::find()->where(['customer_phone'=>$customer_phone])->asArray()->one();
		if(empty($customer)) return false;

//		$channal_name = FinanceOrderChannel::getOrderChannelByName($channal_id);
        $channal_name = \core\models\operation\OperationOrderChannel::get_post_name($channal_id);
        if($channal_name == '未知') return false;
	
		$customerExtSrc = new CustomerExtSrc;
		$customerExtSrc->customer_id = $customer["id"];
		$customerExtSrc->customer_phone = $customer["customer_phone"];
		$customerExtSrc->finance_order_channal_id = $channal_id;
		$customerExtSrc->platform_name = "";
		$customerExtSrc->platform_ename = "";
		$customerExtSrc->channal_name = $channal_name;
		$customerExtSrc->channal_ename = "";
		$customerExtSrc->device_name = "";
		$customerExtSrc->device_no = "";
		$customerExtSrc->created_at = time();
		$customerExtSrc->updated_at = 0;
		$customerExtSrc->is_del = 0;
		if($customerExtSrc->validate()){
			$customerExtSrc->save();
			return true;
		}
		return false;
	}

    /**
     * add csutomer src by channal_name
     * @param $customer_phone
     * @param $channal_name
     * @return bool
     */
    public static function addSrcByChannalName($customer_phone, $channal_name){
		$customer = self::find()->where(['customer_phone'=>$customer_phone])->asArray()->one();
		if(empty($customer)) return false;

//      $channal_id = FinanceOrderChannel::getOrderChannelByid($channal_name);
        $channal_id = \core\models\operation\OperationOrderChannel::get_post_id($channal_name);
        if(empty($channal_id)) return false;
	
		$customerExtSrc = new CustomerExtSrc;
		$customerExtSrc->customer_id = $customer["id"];
		$customerExtSrc->customer_phone = $customer["customer_phone"];
		$customerExtSrc->finance_order_channal_id = $channal_id;
		$customerExtSrc->platform_name = "";
		$customerExtSrc->platform_ename = "";
		$customerExtSrc->channal_name = $channal_name;
		$customerExtSrc->channal_ename = "";
		$customerExtSrc->device_name = "";
		$customerExtSrc->device_no = "";
		$customerExtSrc->created_at = time();
		$customerExtSrc->updated_at = 0;
		$customerExtSrc->is_del = 0;
		if($customerExtSrc->validate()){
			$customerExtSrc->save();
			return true;
		}
		return false;
	}

    /*************************************customer city*************************************************/
    /**
     * @param $customer_id
     * @return mixed
     */
//    public static function getCityNameById($customer_id){
//        $customer_address = CustomerAddress::find()->select(['operation_city_name'])
//            ->where(['customer_id'=>$customer_id])
//            ->orderBy('created_at asc')
//            ->asArray()
//            ->one();
//        $operation_city_name = empty($customer_address['operation_city_name']) ? '' : $customer_address['operation_city_name'];
//        return $operation_city_name;
//    }

	/*************************************address*******************************************************/
    /**
     * get current address
     * @param $phone
     * @return array
     * @throws NotFoundHttpException
     */
    public static function getCurrentAddress($phone){
		$customer = self::find()->where(['customer_phone'=>$phone])->asArray()->one();
		if(empty($customer)) throw new NotFoundHttpException;
		
		$customer_address = CustomerAddress::find()->where(['customer_phone'=>$phone, 'customer_address_status'=>1])->asArray()->one();
		if(empty($customer_address)) throw new NotFoundHttpException;
		return [
			'operation'=>$customer_address,
			'operation_str'=>$customer_address['operation_province_name']
				.$customer_address['operation_city_name']
				.$customer_address['operation_area_name']
				.$customer_address['operation_address_detail']
				.' | '
				.$customer_address['customer_address_nickname']
				.' | '
				.$customer['customer_address_phone'],
		];
	}

    /**
     * get current address
     * @param $customer_id
     * @return bool
     */
    public static function getCustomerAddresses($customer_id){
        $customer = self::findOne($customer_id);
        $customerAddresses = $customer->hasMany('\dbbase\models\CustomerAddress', ['customer_id'=>'id'])->all();
        return $customerAddresses != NULL ? $customerAddresses : false;
    }

    /**
     * add customer address
     * @param $customer_id
     * @param $general_region_id
     * @param $detail
     * @param $nickname
     * @param $phone
     * @throws \Exception
     */
    public function addCustomerAddress($customer_id, $general_region_id, $detail, $nickname, $phone){
        
        $transaction = \Yii::$app->db->beginTransaction();

        try {
            // 所有的查询都在主服务器上执行
            $customerAddresses = CustomerAddress::find()->where(array(
                'customer_id'=>$customer_id,
                ))->all();
            foreach ($customerAddresses as $customerAddress) {
                $customerAddress->customer_address_status = 0;
                $customerAddress->validate();
                $customerAddress->save();
            }
            $customerAddress = new customerAddress;
            $customerAddress->customer_id = $customer_id;
            $customerAddress->general_region_id = $general_region_id;
            $customerAddress->customer_address_detail = $detail;
            $customerAddress->customer_address_status = 1;
            //根据地址获取经纬度信息
            $customerAddress->customer_address_longitude = '';
            $customerAddress->customer_address_latitude = '';
            $customerAddress->customer_address_nickname = $nickname;
            $customerAddress->customer_address_phone = $phone;
            $customerAddress->created_at = time();
            $customerAddress->updated_at = 0;
            $customerAddress->is_del = 0;
            $customerAddress->validate();
            $customerAddress->save();
            $transaction->commit();
        } catch(\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * update customer address
     * @param $customer_id
     * @param $general_region_id
     * @param $detail
     * @param $nickname
     * @param $phone
     * @throws \Exception
     */
    public function updateCustomerAddress($customer_id, $general_region_id, $detail, $nickname, $phone){
        
        $transaction = \Yii::$app->db->beginTransaction();

        try {
            // 所有的查询都在主服务器上执行
            $customerAddresses = CustomerAddress::find()->where(array(
                'customer_id'=>$customer_id,
                ))->all();
            foreach ($customerAddresses as $customerAddress) {
                $customerAddress->customer_address_status = 0;
                $customerAddress->validate();
                $customerAddress->save();
            }
            $customerAddress = CustomerAddress::find()->where(array(
                'customer_id'=>$customer_id,
                ))->one();
            $customerAddress->general_region_id = $general_region_id;
            $customerAddress->customer_address_detail = $detail;
            $customerAddress->customer_address_status = 1;
            //根据地址获取经纬度信息
            $customerAddress->customer_address_longitude = '';
            $customerAddress->customer_address_latitude = '';
            $customerAddress->customer_address_nickname = $nickname;
            $customerAddress->customer_address_phone = $phone;
            $customerAddress->created_at = time();
            $customerAddress->updated_at = 0;
            $customerAddress->is_del = 0;
            $customerAddress->validate();
            $customerAddress->save();
            $transaction->commit();
        } catch(\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }


	/*************************************worker*******************************************************/
    /**
     * add customer worker
     * @param $customer_id
     * @param $worker_id
     * @return array
     */
    public static function addWorker($customer_id, $worker_id){
        $customer_exist = self::customerExistById($customer_id);
        if(!$customer_exist) return ['response'=>'error', 'errcode'=>1, 'errmsg'=>'customer not exist'];
        $is_block_arr = self::isBlock($customer_id);
        if($is_block_arr['response'] == 'error'){
            return $is_block_arr;
        }else{
            $is_block = $is_block_arr['is_block'];
            if($is_block){
                return ['response'=>'error', 'errcode'=>'3', 'errmsg'=>'customer is in black-block'];
            }
        }

        //whether worker is exist not not in block, if yes , return error message
//        $worker_is_ok = wangzigen::func($worker_id);
        $worker_is_enable = Worker::checkWorkerIsEnabled($worker_id);
        if(!$worker_is_enable){
            return ['response'=>'error', 'errcode'=>4, 'errmsg'=>'worker is not enable'];
        }

        //get customer phone by id
        $customer_phone = self::getCustomerPhoneById($customer_id);

        $customerWorker = new CustomerWorker;
        $customerWorker->customer_id = $customer_id;
        $customerWorker->customer_phone = $customer_phone;
        $customerWorker->worker_id = $worker_id;
        $customerWorker->customer_worker_status = 1;
        $customerWorker->is_block = 0;
        $customerWorker->created_at = time();
        $customerWorker->updated_at = 0;
        $customerWorker->is_del = 0;
        if(!$customerWorker->validate()){
            return ['response'=>'error', 'errcode'=>5, 'errmsg'=>'validate failed'];
        }
        $customerWorker->save();
        return ['response'=>'success', 'errcode'=>0, 'errmsg'=>''];
    }

    /**
     * get customer's worker list
     * @param $customer_id
     * @return array
     */
    public static function getWorkersById($customer_id){
		$customer = self::findOne($customer_id);
		if($customer === NULL) {
			return ['response'=>'error', 'errcode'=>'1', 'errmsg'=>'客户不存在'];
		}

		$customer_workers = (new \yii\db\Query())
			->select(['w.*', 'cw.customer_id', 'cw.customer_phone'])
			->from(['cw'=>'{{%customer_worker}}'])
			->leftJoin(['w'=>'{{%worker}}'], 'w.id = cw.worker_id')
			->where(['cw.is_block'=>0])
			->andWhere(['cw.is_del'=>0])
			->all();

		return ['response'=>'success', 'errcode'=>'0', 'errmsg'=>'', 'customer_workers'=>$customer_workers];
	}

    /**
     * get customer's worker list by phone
     * @param $customer_phone
     * @return array
     */
    public static function getWorkersByPhone($customer_phone){
		$customer = self::find()->where(['customer_phone'=>$customer_phone])->one();
		if($customer === NULL) {
			return ['response'=>'error', 'errcode'=>'1', 'errmsg'=>'客户不存在'];
		}
		$customer_workers = CustomerWorker::find()->where([
			'customer_phone'=>$customer_phone,
			'is_block'=>0,
			'is_del'=>0
			])->asArray()->all();
		return ['response'=>'success', 'errcode'=>'0', 'errmsg'=>'', 'customer_workers'=>$customer_workers];
	}

    /**
     * get current worker
     * @param $phone
     * @return mixed
     * @throws NotFoundHttpException
     */
    public static function getCurrentWorker($phone){
		$customer = self::find()->where(['customer_phone'=>$phone])->asArray()->one();
		if(empty($customer)) throw new NotFoundHttpException;

		$customer_worker = CustomerWorker::find()->where([
			'customer_phone'=>$phone, 
			'customer_worker_status'=>1, 
			'customer_worker_status'=>1, 
			'is_del'=>0
			])->asArray()->one();
		if(empty($customer_worker)) throw new NotFoundHttpException;

		$worker = Worker::findOne($customer_worker['id'])->asArray();
		if(empty($worker)) throw new NotFoundHttpException;
		return $worker;
	}

    /**
     * get customer 's worker list
     * @param $customer_id
     * @return bool
     */
    public function getCustomerWorkers($customer_id)
    {
        $customer = self::findOne($customer_id);
        $customerWorkers = $customer->hasMany('\dbbase\models\customerWorker', ['customer_id'=>'id'])->all();
        return $customerWorkers != NULL ? $customerWorkers : false;
    }

    /**
     * @param $id
     * @return array
     */
    public static function getCustomerUsedWorkers($id)
    {
        $customerWorker = CustomerWorker::findAll(['customer_id'=>$id]);
        $workers = [];
        foreach($customerWorker as $k=>$v)
        {
            $worker = $v->attributes;
            $worker_model = Worker::findOne($v->worker_id);
            if(!empty($worker_model)) {
                $worker['worker_name'] = $worker_model->worker_name;
                $workers[]=$worker;
            }
        }
        return $workers;
    }

	/**********************************block*************************************************************/

    /**
     * customer is block
     * @param $customer_id
     * @return array
     */
    public static function isBlock($customer_id){
        $customer = Customer::find()->select(['is_del'])->where(['id'=>$customer_id])->asArray()->one();
        if(empty($customer)){
            return ['response'=>'error', 'errcode'=>1, 'errmsg'=>'customer not exist'];
        }
        return ['response'=>'success', 'errcode'=>0, 'errmsg'=>'', 'is_block'=>$customer['is_del']];
    }

    /**
     * customer is block by phone
     * @param $customer_phone
     * @return array
     */
    public static function isBlockByPhone($customer_phone){
        $customer = Customer::find()->select(['is_del'])->where(['customer_phone'=>$customer_phone])->asArray()->one();
        if(empty($customer)){
            return ['response'=>'error', 'errcode'=>1, 'errmsg'=>'customer not exist'];
        }
        return ['response'=>'success', 'errcode'=>0, 'errmsg'=>'', 'is_block'=>$customer['is_del']];
    }
    
/***********************************customer feedback************************************************/
    /**
     * submit feedback of customer
     * @param $customer_id
     * @param $feedback_content
     * @return array
     */
    public static function addFeedback($customer_id, $feedback_content){
        $customer_phone = self::getCustomerPhoneById($customer_id);
        $customerFeedback = new CustomerFeedback;
        $customerFeedback->customer_id = $customer_id;
        $customerFeedback->customer_phone = $customer_phone;
        $customerFeedback->feedback_content = $feedback_content;
        $customerFeedback->created_at = time();
        $customerFeedback->updated_at = 0;
        $customerFeedback->is_del = 0;
        if(!$customerFeedback->validate()){
            return ['response'=>'error', 'errcode'=>1, 'errmsg'=>'客户提交意见失败'];
        }
        $customerFeedback->save();
        return ['response'=>'success', 'errcode'=>0, 'errmsg'=>''];
    }
	
	/**********************************count*************************************************************/
    /**
     * count all customers
     * @return int|string
     */
    public static function countAllCustomer(){
        $count = self::find()->count();
        return $count;
    }

    /**
     * count customers not in black-block
     * @return int|string
     */
    public static function countCustomer(){
        $count = self::find()->where(['is_del'=>0])->count();
        return $count;
    }

    /**
     * count black-block workers
     * @return int|string
     */
    public static function countBlockCustomer(){
        $count = self::find()->where(['is_del'=>1])->count();
        return $count;
    }
    
    /**************************************define relations*********************************/
//    public function getOrderCount(){
//        return self->hasOne();
//    }


	/**
     * get all customer relationally
	 */
	//public static function getAllRelationally(){
		//$customers = self::find()->with([
	//		'balance' => function($query) {
	//			$query->where('customer_ext_balance.customer_id = customer.id');
	//		},
	//		'score' => function($query) {
	//			$query->where('customer_ext_score.customer_id = customer.id');
	//		},
	//		'address' => function($query) {
	//			$query->where('customer_address.customer_id = customer.id');
	//		},
	//		'worker' => function($query) {
	//			$query->where('customer_worker.customer_id = customer.id');
	//		},
	//		'src' => function($query) {
	//			$query->where('customer_ext_src.customer_id = customer.id');
	//		},
	//	])->all();
	//	return $customers;
	//}


	/******************************other******************************************************************/
    /**
     * get city list
     * @return array
     */
    public static function cityList(){
        $onlineCityList = OperationCity::getCityOnlineInfoList();
        $cityList = $onlineCityList?ArrayHelper::map($onlineCityList,'city_id','city_name'):[];
		return $cityList;
    }

    /**
     * get city name by city id
     * @param $city_id
     * @return bool
     */
    public static function getCityName($city_id){
        $city_list = self::cityList();
        foreach($city_list as $key => $value){
            if($key == $city_id){
                return $value;
            }
        }
        return false;
    }
}
