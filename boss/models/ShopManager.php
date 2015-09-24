<?php
namespace boss\models;
use yii;
use yii\behaviors\TimestampBehavior;
class ShopManager extends \common\models\ShopManager
{
    public static $bl_types = [
        1=>'个体户',
        2=>'商户'
    ];
    
    public static $audit_statuses = [
        0=>'未审核',
        1=>'通过',
        2=>'不通过'
    ];
    /**
     * 自动处理创建时间和修改时间
     * @see \yii\base\Component::behaviors()
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'create_at',
                'updatedAtAttribute' => 'update_at',
            ],
        ];
    }
}