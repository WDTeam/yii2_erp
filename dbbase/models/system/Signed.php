<?php

namespace dbbase\models;

use Yii;

/**
 * This is the model class for table "{{%signed}}".
 *
 * @property integer $sign_id
 * @property integer $uid
 * @property string $uname
 * @property string $identity_number
 * @property string $address
 * @property string $emergency_contact
 * @property string $shopid
 * @property string $shopname
 * @property string $bankcard
 * @property double $deposit
 * @property string $mobile
 * @property string $contract_number
 * @property integer $contract_time
 * @property string $signed
 */
class Signed extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%signed}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sign_id', 'uid', 'contract_time'], 'integer'],
            [['deposit'], 'number'],
            [['uname', 'shopname', 'bankcard', 'contract_number'], 'string', 'max' => 50],
            [['identity_number'], 'string', 'max' => 18],
            [['address'], 'string', 'max' => 255],
            [['emergency_contact'], 'string', 'max' => 15],
            [['shopid', 'mobile', 'signed'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sign_id' => Yii::t('app', '自增编号'),
            'uid' => Yii::t('app', '用户id'),
            'uname' => Yii::t('app', '用户名称'),
            'identity_number' => Yii::t('app', '身份证号'),
            'address' => Yii::t('app', '联系地址'),
            'emergency_contact' => Yii::t('app', '紧急联系人'),
            'shopid' => Yii::t('app', '门店编号'),
            'shopname' => Yii::t('app', '门店名称'),
            'bankcard' => Yii::t('app', '银行卡号'),
            'deposit' => Yii::t('app', '押金'),
            'mobile' => Yii::t('app', '手机号'),
            'contract_number' => Yii::t('app', '合同编号'),
            'contract_time' => Yii::t('app', '签订日期'),
            'signed' => Yii::t('app', '签约员工'),
        ];
    }
}
