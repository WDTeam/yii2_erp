<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%courseware}}".
 *
 * @property string $id
 * @property string $image
 * @property string $url
 * @property string $name
 * @property integer $pv
 * @property integer $order_number
 * @property integer $classify_id
 */
class Courseware extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%courseware}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url', 'name'], 'required'],
            [['pv', 'order_number', 'classify_id'], 'integer'],
            [['image', 'url'], 'string', 'max' => 255],
            [['name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'image' => Yii::t('app', '图片URL'),
            'url' => Yii::t('app', '视频URL'),
            'name' => Yii::t('app', '视频名称'),
            'pv' => Yii::t('app', '播放次数'),
            'order_number' => Yii::t('app', '在分类下的排序'),
            'classify_id' => Yii::t('app', '服务技能ID'),
        ];
    }
}
