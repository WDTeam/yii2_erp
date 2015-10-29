<?php

namespace dbbase\models;

use Yii;

/**
 * This is the model class for table "{{%category}}".
 *
 * @property integer $cateid
 * @property string $catename
 * @property string $description
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['catename'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cateid' => Yii::t('app', '分类ID'),
            'catename' => Yii::t('app', '分类名称'),
            'description' => Yii::t('app', '分类描述'),
        ];
    }
}
