<?php

namespace core\models\operation;


/**
 * This is the model class for table "ejj_operation_service_card_info".
 *
 * @property string $id
 * @property string $service_card_info_name
 * @property integer $service_card_info_card_type
 * @property integer $service_card_info_card_level
 * @property string $service_card_info_par_value
 * @property string $service_card_info_reb_value
 * @property integer $service_card_info_use_scope
 * @property integer $service_card_info_valid_days
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class OperationServiceCardInfo extends \dbbase\models\operation\OperationServiceCardInfo
{
	public function serviceCardInfoCreate()
	{
		
		$this->created_at = time();
		$this->updated_at = time();
		$this->is_del = 1;
		return $this->save();
	}
	
	public function serviceCardInfoUpdate()
	{
		
		$this->updated_at = time();
		return $this->save();
	}
	
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
