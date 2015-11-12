<?php
/**
* 订单渠道数据库映射关系 
* ==========================
* 北京一家洁 版权所有 2015-2018 
* ----------------------------
* 这不是一个自由软件，未经授权不许任何使用和传播。
* ==========================
* @date: 2015-11-12
* @author: peak pan 
* @version:1.0
*/

namespace dbbase\models\operation;

use Yii;

/**
 * This is the model class for table "{{%operation_order_channel}}".
 *
 * @property integer $id
 * @property string $operation_order_channel_name
 * @property integer $operation_order_channel_type
 * @property string $operation_order_channel_rate
 * @property integer $system_user_id
 * @property string $system_user_name
 * @property integer $create_time
 * @property integer $is_del
 */
class OperationOrderChannel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operation_order_channel}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['operation_order_channel_type', 'system_user_id', 'create_time', 'is_del'], 'integer'],
            [['operation_order_channel_name'], 'string', 'max' => 50],
            [['operation_order_channel_rate'], 'string', 'max' => 6],
            [['system_user_name'], 'string', 'max' => 40]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '主键id'),
            'operation_order_channel_name' => Yii::t('app', '订单渠道名称'),
            'operation_order_channel_type' => Yii::t('app', '订单渠道类别'),
            'operation_order_channel_rate' => Yii::t('app', '比率'),
            'system_user_id' => Yii::t('app', '添加人id'),
            'system_user_name' => Yii::t('app', '添加人名称'),
            'create_time' => Yii::t('app', '增加时间'),
            'is_del' => Yii::t('app', '0 正常 1 删除'),
        ];
    }

   
}
