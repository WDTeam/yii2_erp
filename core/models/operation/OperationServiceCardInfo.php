<?php

namespace core\models\operation;


/**
 * This is the model class for table "ejj_operation_service_card_info".
 *
 * @property string $id
 * @property string $service_card_info_name
 * @property integer $service_card_info_type
 * @property integer $service_card_info_level
 * @property string $service_card_info_value
 * @property string $service_card_info_rebate_value
 * @property integer $service_card_info_scope
 * @property integer $service_card_info_valid_days
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class OperationServiceCardInfo extends \dbbase\models\operation\OperationServiceCardInfo
{
	 /**
     * @intruction 获取服务卡信息
     * @param $id
     * @return null|static
     */
    public static function getServiceCardInfoById($id) {
        return self::findOne(['id'=>$id]);
    }
	/**
	 * @introduction 逻辑删除
	 * @return bool
	 */
	public function softDelete()
	{
		$this->isdel = 1;
		return $this->save();
	}

    /**
     * 查询所有服务卡信息
     * @return static[]
     */
    public static function getServiceCardInfo()
	{
		return self::findAll(['is_del' => 0]);
	}


	/**
	 * @return bool
	 */
	public function serviceCardInfoCreate()
	{
		
		$this->created_at = time();
		$this->updated_at = time();
		$this->is_del = 0;
		return $this->save();
	}

	/**
	 * @return bool
	 */
	public function serviceCardInfoUpdate()
	{
		
		$this->updated_at = time();
		return $this->save();
	}

	/**
	 * @return array
	 */
	public function getServiceCardConfig(){
		$config = [
			'type'=>[
				1=>'个人',
				2=>'企业',
			],
			'level'=>[
				1=>'青铜',
				2=>'白银',
				3=>'黄金',
			],
		];
		return $config;
	}
    
}
