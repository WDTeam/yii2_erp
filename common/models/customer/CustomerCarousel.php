<?php

namespace common\models\customer;

use Yii;

/**
 * This is the model class for table "{{%customer_carousel}}".
 *
 * @property integer $id
 * @property string $carousel_pic
 * @property string $carousel_url
 * @property integer $carousel_sort
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class CustomerCarousel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_carousel}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['carousel_sort', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['carousel_pic', 'carousel_url'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('boss', '编号'),
            'carousel_pic' => Yii::t('boss', '广告图片'),
            'carousel_url' => Yii::t('boss', '连接地址'),
            'carousel_sort' => Yii::t('boss', '排序'),
            'created_at' => Yii::t('boss', '创建时间'),
            'updated_at' => Yii::t('boss', '修改时间'),
            'is_del' => Yii::t('boss', '是否已经逻辑删除,1为已删除'),
        ];
    }
}
