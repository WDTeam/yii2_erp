<?php
namespace boss\models\operation;
use Yii;


/**
 * OperationCitySearch represents the model behind the search form about `dbbase\models\OperationCity`.
 */
class OperationBootPage extends \core\models\operation\OperationBootPage
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['operation_boot_page_residence_time', 'created_at', 'updated_at'], 'integer'],
            [['operation_boot_page_name'], 'string', 'max' => 60],
            [['operation_boot_page_name'], 'required'],
            [['operation_boot_page_url'], 'url'],
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
            'citylist' => Yii::t('app', '适用城市'),
        ];
    }
}
