<?php

namespace dbbase\models\shop;

use Yii;

/**
 * This is the model class for table "ejj_shop_custome_relation".
 *
 * @property string $id
 * @property integer $system_user_id
 * @property integer $baseid
 * @property integer $shopid
 * @property integer $shop_manager_id
 * @property integer $stype
 * @property integer $is_del
 */
class ShopCustomeRelation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ejj_shop_custome_relation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['system_user_id', 'baseid', 'shopid', 'shop_manager_id', 'stype', 'is_del'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('core', '主键'),
            'system_user_id' => Yii::t('core', '用户'),
            'baseid' => Yii::t('core', '父级'),
            'shopid' => Yii::t('core', '门店'),
            'shop_manager_id' => Yii::t('core', '家政公司'),
            'stype' => Yii::t('core', '1 家政公司 2 门店'),
            'is_del' => Yii::t('core', '0 正常  1删除'),
        ];
    }
}
