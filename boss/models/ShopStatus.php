<?php
namespace boss\models;

use yii\behaviors\TimestampBehavior;

class ShopStatus extends \common\models\ShopStatus
{
    const MODEL_SHOP = 'Shop';
    const MODEL_SHOPMANAGER = 'ShopManager';
    /**
     * 自动处理创建时间和修改时间
     * @see \yii\base\Component::behaviors()
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
            ],
        ];
    }
}