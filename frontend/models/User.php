<?php

namespace frontend\models;

use Yii;
use yii\base\InvalidParamException;
use yii\base\ErrorException;
use common\models\Studylog;
/**
 * This is the model class for table "{{%user}}".
 *
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
 * @property string $ecn
 * @property string $city
 * @property string $province
 * @property string $district
 * @property string $whatodo
 * @property string $from_type
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
 * @property integer $exam_result
 * @property integer $operation_time
 * @property integer $operation_score
 * @property integer $test_status
 * @property string $test_situation
 * @property integer $test_result
 * @property integer $sign_status
 */
class User extends \common\models\User
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
            [['username','idnumber','when','whatodo','from_type'],'required'],
            [['age', 'status','from_type','when', 'created_at', 'updated_at', 'study_status', 'study_time', 'notice_status', 'online_exam_time', 'online_exam_score', 'exam_result', 'operation_time', 'operation_score', 'test_status', 'test_result', 'sign_status'], 'integer'],
            [['birthday'], 'safe'],
            [['password_hash', 'password_reset_token', 'email','whatodo'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['idnumber'], 'string', 'max' => 18,'min'=>18],
            [['mobile', 'ecn'], 'string', 'max' => 11],
            [['city', 'province'], 'string', 'max' => 3],
            [['district'], 'string', 'max' => 2],
            [['ecn'],'string','max'=>11,'min'=>11],
            [['username'],'string','max'=>48],
            [['username'],'match','pattern'=>'/^[\x{4e00}-\x{9fa5}]+$/u','message'=>'名字为中文'],          
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'verification'=>'验证码',
            'id' => 'ID',
            'username' => '姓名',
            'auth_key' => 'Auth Key',
            'password_hash' => '加密的密码',
            'password_reset_token' => '密码重置',
            'email' => '邮箱',
            'idnumber' => '身份证号',
            'age' => '年龄',
            'birthday' => '生日',
            'mobile' => '手机',
            'ecn' => '紧急联系号码',
            'city' => '市',
            'province' => '省',
            'district' => '区',
            'whatodo' => '提供的服务列表',
            'from_type' => '来自哪里',
            'when' => '选择服务时间',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'isdel' => '1',
            'study_status' => '学习状态:1认同，2不认同',
            'study_time' => '学习所用时长（单位秒）',
            'notice_status' => '通知状态：1已通知，2未通知',
            'online_exam_time' => '在线考试时间（开始还是结束呢？）',
            'online_exam_score' => '在线考试成绩',
            'exam_result' => '现场考试结果：1通过，2未通过',
            'operation_time' => '实操考试时间',
            'operation_score' => '实操考试成绩',
            'test_status' => '试工状态：1.安排试工，2：不用试工',
            'test_situation' => '试工情况',
            'test_result' => '试工结果',
            'sign_status' => '签约状态',
            
        ];
    }
    /**
     * 计算和设置学习总时长
     */
    public function computeStudyTimeTotal()
    {
        $total = Studylog::findBySql("SELECT SUM(end_time-start_time) FROM {{%studylog}} 
            WHERE student_id={$this->id} AND end_time>0 AND start_time>0")
        ->scalar();
        $this->study_time = $total;
        if($this->save(false)==false){
            throw new InvalidParamException("计算和设置学习总时长失败", 1);
        }
        return $total;
    }
    /**
     * 设置学习状态
     * 学习状态:1未学习，2学习中， 3未通过，4已通过
     * 注意：状态不可逆
     */
    public function saveStudyStatus($status_code)
    {
        // var_dump($this->getIsNewRecord(),$this->id);exit;
        if($status_code>$this->study_status){
            $this->study_status = $status_code;
            if($this->save(false)==false){
                throw new InvalidParamException("设置学习状态失败", 1);
            }
        }
    }
}
