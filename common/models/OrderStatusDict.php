<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%order_status_dict}}".
 *
 * @property integer $id
 * @property string $order_status_name
 * @property string $order_status_alias
 * @property string $created_at
 * @property string $updated_at
 * @property integer $isdel
 */
class OrderStatusDict extends ActiveRecord
{
    const ORDER_INIT=1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_status_dict}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_status_name'], 'required'],
            [['created_at', 'updated_at', 'isdel'], 'integer'],
            [['order_status_name', 'order_status_alias'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_status_name' => '状态名称服务端',
            'order_status_alias' => '状态名称客户端',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'isdel' => 'Isdel',
        ];
    }
}
