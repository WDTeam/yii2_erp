<?php

namespace boss\models;

use Yii;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property string $common_mobile
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $idnumber
 * @property integer $age
 * @property string $birthday
 * @property string $mobile
 * @property string $ecp
 * @property string $ecn
 * @property string $address
 * @property string $city
 * @property string $province
 * @property string $district
 * @property string $whatodo
 * @property string $where
 * @property string $when
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $isdel
 * @property integer $study_status
 * @property integer $study_time
 * @property integer $notice_status
 * @property integer $online_exam_time
 * @property integer $online_exam_score
 * @property integer $online_exam_mode
 * @property integer $exam_result
 * @property integer $operation_time
 * @property integer $operation_score
 * @property integer $test_status
 * @property integer $test_situation
 * @property integer $test_result
 * @property integer $sign_status
 */
class Interview extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['age', 'status', 'created_at', 'updated_at', 'study_status', 'study_time', 'notice_status', 'online_exam_time', 'online_exam_score', 'online_exam_mode', 'exam_result', 'operation_time', 'operation_score', 'test_status', 'test_situation', 'test_result', 'sign_status'], 'integer'],
            [['birthday'], 'safe'],
            [['common_mobile'], 'string', 'max' => 15],
            [['mobile'], 'string','min'=>11, 'max' => 11],
            [['mobile'], 'required'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'address', 'whatodo'], 'string', 'max' => 255],
            [['email'], 'email'],
            [['username'], 'required'],
            [['address'], 'required'],
            [['auth_key'], 'string', 'max' => 32],
            [['idnumber'], 'string', 'min' => 18, 'max' => 18],
            [['idnumber'], 'required'],
            [['ecp'], 'string', 'max' => 30],
            [['ecn'], 'string', 'min'=>11, 'max' => 11],
            [['city', 'province'], 'string', 'max' => 3],
            [['district'], 'string', 'max' => 2],
            [['from_type', 'when', 'isdel'], 'string', 'max' => 1],
            [['username'],'match','pattern'=>'/^[\x{4e00}-\x{9fa5}]+$/u','message'=>'名字必须为中文'],
            [['ecp'],'match','pattern'=>'/^[\x{4e00}-\x{9fa5}]+$/u','message'=>'紧急联系人必须为中文'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'common_mobile' => '常用的手机号',
            'id' => '编号',
            'username' => '阿姨姓名',
            'auth_key' => '验证码',
            'password_hash' => '加密的密码',
            'password_reset_token' => '密码重置',
            'email' => '邮箱',
            'idnumber' => '身份证号',
            'age' => '年龄',
            'birthday' => '生日',
            'mobile' => '手机',
            'ecp' => '紧急联系人',
            'ecn' => '紧急联系号码',
            'address' => '住址',
            'city' => '市',
            'province' => '省',
            'district' => '区',
            'whatodo' => '提供的服务列表',
            'where' => '来自哪里',
            'when' => '选择服务时间',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'isdel' => '是否删除',
            'study_status' => '学习状态',
            'study_time' => '学习所用时长',
            'notice_status' => '通知状态',
            'online_exam_time' => '现场在线考试时间',
            'online_exam_score' => '现场在线考试成绩',
            'online_exam_mode' => '现场在线考试方式',
            'exam_result' => '现场在线考试状态',
            'operation_time' => '实操考试时间',
            'operation_score' => '实操考试成绩',
            'test_status' => '安排试工状态',
            'test_situation' => '试工情况',
            'test_result' => '试工结果',
            'sign_status' => '签约状态',
        ];
    }
    
    public static function _find($conditions){
        return !empty($conditions) ? Interview::find()->where($conditions) : Interview::find()->where(null);
    }
    
    public static function processSecond($second){
        $str = '';
        if(!empty($second)){
            $s = $second % 86400;
            $d = ($second - $s)/86400;
            $hs = $s % 3600;
            $h = ($s - $hs)/3600;
            $ms = $hs % 60;
            $m = ($hs - $ms)/60;
            if(isset($d) && $d > 0){$str .= $d.'天';}
            if(isset($h) && $h > 0){$str .= $h.'小时';}
            if(isset($m) && $m > 0){$str .= $m.'分钟';}
            if(isset($ms) && $ms > 0){$str .= $ms.'秒';}
        }
        return $str;
    }
}
