<?php

namespace dbbase\models\order;

use Yii;
use dbbase\models\ActiveRecord;
/**
 * This is the model class for table "{{%order_src}}".
 *
 * @property string $id
 * @property string $created_at
 * @property string $updated_at
 * @property string $order_src_name
 * @property integer $isdel
 */
class OrderSrc extends ActiveRecord
{
    const ORDER_SRC_BOSS = 1;
    const ORDER_SRC_IOS = 2;
    const ORDER_SRC_ANDROID = 3;
    const ORDER_SRC_POP = 4;
    const ORDER_SRC_H5 = 5;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_src}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'isdel'], 'integer'],
            [['order_src_name'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '编号',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
            'order_src_name' => '订单来源，订单入口名称',
            'isdel' => '是否已删除',
        ];
    }
}
