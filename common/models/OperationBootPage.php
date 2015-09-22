<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%operation_boot_page}}".
 *
 * @property integer $id
 * @property string $operation_boot_page_name
 * @property string $operation_boot_page_ios_img
 * @property string $operation_boot_page_android_img
 * @property string $operation_boot_page_url
 * @property integer $operation_boot_page_residence_time
 * @property integer $operation_boot_page_online_time
 * @property integer $operation_boot_page_offline_time
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationBootPage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operation_boot_page}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['operation_boot_page_residence_time', 'created_at', 'updated_at'], 'integer'],
            [['operation_boot_page_name'], 'string', 'max' => 60],
            [['operation_boot_page_name'], 'required'],
            [['operation_boot_page_ios_img', 'operation_boot_page_android_img', 'operation_boot_page_url'], 'url'],
            [['operation_boot_page_ios_img', 'operation_boot_page_android_img', 'operation_boot_page_url', 'operation_boot_page_online_time', 'operation_boot_page_offline_time'], 'required'],
            [['operation_boot_page_residence_time'], 'integer', 'min' => 1],
//            [['operation_boot_page_online_time', 'operation_boot_page_offline_time'], 'date'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '编号'),
            'operation_boot_page_name' => Yii::t('app', '启动页名称'),
            'operation_boot_page_ios_img' => Yii::t('app', 'ios图片'),
            'operation_boot_page_android_img' => Yii::t('app', 'android图片'),
            'operation_boot_page_url' => Yii::t('app', '启动页连接地址'),
            'operation_boot_page_residence_time' => Yii::t('app', '停留时间：秒数 （不填写默认为5秒）'),
            'operation_boot_page_online_time' => Yii::t('app', '上线时间'),
            'operation_boot_page_offline_time' => Yii::t('app', '下线时间'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '编辑时间'),
        ];
    }
}
