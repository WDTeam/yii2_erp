<?php

namespace boss\models;

use Yii;

/**
 * This is the model class for table "{{%signed}}".
 *
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
 * @property string $sendSome
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
            [['uid'], 'required'],
            [['uid'], 'integer'],
            [['deposit'], 'number'],
            [['uname', 'shopname', 'contract_number'], 'string', 'max' => 50],
            [['bankcard'], 'required'],
            [['deposit'], 'required'],
//            [['contract_number'], 'required'],
            [['contract_time'], 'required'],
            [['signed'], 'required'],
            [['bankcard'], 'string', 'min' => 16, 'max' => 19],
            [['bankcard'],'number','message'=>'银行卡号必须为数字'],
            [['identity_number'], 'string', 'max' => 18],
            [['address'], 'string', 'max' => 255],
            [['emergency_contact'], 'string', 'max' => 15],
            [['shopid' ,'signed'], 'string', 'max' => 20],
            [['mobile'], 'string', 'min' => 11, 'max' => 11],
            [['sendSome'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => '用户id',
            'uname' => '用户名称',
            'identity_number' => '身份证号',
            'address' => '联系地址',
            'emergency_contact' => '紧急联系人',
            'shopid' => '门店编号',
            'shopname' => '选择门店',
            'bankcard' => '银行卡号',
            'deposit' => '押金',
            'mobile' => '手机号',
            'contract_number' => '合同编号',
            'contract_time' => '签订日期',
            'signed' => '签约员工',
            'sendSome' => '发放物品',
            'picture' => '照片',
        ];
    }
}
