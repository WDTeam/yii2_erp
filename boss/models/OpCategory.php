<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ect_op_category".
 *
 * @property integer $id
 * @property string $category_name
 * @property integer $created_at
 * @property integer $updated_at
 */
class OpCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ect_op_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'integer'],
            [['category_name'], 'string', 'max' => 60]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '编号'),
            'category_name' => Yii::t('app', '服务品类名称'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '编辑时间'),
        ];
    }

    /**
     * @inheritdoc
     * @return OpCategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OpCategoryQuery(get_called_class());
    }
}
