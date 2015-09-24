<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%finance_header}}".
 *
 * @property integer $id
 * @property string $finance_header_name
 * @property integer $finance_order_channel_id
 * @property string $finance_order_channel_name
 * @property integer $finance_pay_channel_id
 * @property string $finance_pay_channel_name
 * @property integer $create_time
 * @property integer $is_del
 */
class FinanceHeader extends \yii\db\ActiveRecord
{
	
	
	
	public $finance_uplod_url;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance_header}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['finance_order_channel_id', 'finance_pay_channel_id', 'create_time', 'is_del'], 'integer'],
            [['finance_header_name', 'finance_order_channel_name', 'finance_pay_channel_name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('boss', '主键'),
			'finance_header_title' => Yii::t('boss', '当前名称'),
			'finance_header_name' => Yii::t('boss', '表头名称'),
			'finance_order_channel_id' => Yii::t('boss', '订单渠道id'),
            'finance_order_channel_name' => Yii::t('boss', '订单渠道名称'),
            'finance_pay_channel_id' => Yii::t('boss', '支付渠道id'),
            'finance_pay_channel_name' => Yii::t('boss', '支付渠道名称'),
			
'create_time' => Yii::t('boss', '创建时间'),
			'is_del' => Yii::t('boss', '0 正常 1 删除'),
			
        ];
    }
}
