<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\helpers\Security;
use yii\web\IdentityInterface;
use yii\web\HttpException;


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
 * @property integer $exam_result
 * @property integer $operation_time
 * @property integer $operation_score
 * @property integer $test_status
 * @property string $test_situation
 * @property integer $test_result
 * @property integer $sign_status
 */
 
class User extends ActiveRecord implements IdentityInterface 
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
            [['age', 'status', 'created_at', 'updated_at', 'study_status', 'study_time', 'notice_status', 'online_exam_time', 'online_exam_score', 'exam_result', 'operation_time', 'operation_score', 'test_status', 'test_result', 'sign_status'], 'integer'],
            [['birthday'], 'safe'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'whatodo', 'test_situation'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['idnumber'], 'string', 'max' => 24],
            [['mobile', 'ecn'], 'string', 'max' => 15],
            [['city', 'province'], 'string', 'max' => 3],
            [['district'], 'string', 'max' => 2],
            [['where', 'when', 'isdel'], 'string', 'max' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', '姓名'),
            'auth_key' => Yii::t('app', '手机验证码x'),
            'password_hash' => Yii::t('app', '加密的密码'),
            'password_reset_token' => Yii::t('app', '密码重置'),
            'email' => Yii::t('app', '邮箱'),
            'idnumber' => Yii::t('app', '身份证号'),
            'age' => Yii::t('app', '年龄'),
            'birthday' => Yii::t('app', '生日'),
            'mobile' => Yii::t('app', '手机'),
            'ecn' => Yii::t('app', '紧急联系号码'),
            'city' => Yii::t('app', '市'),
            'province' => Yii::t('app', '省'),
            'district' => Yii::t('app', '区'),
            'whatodo' => Yii::t('app', '提供的服务列表'),
            'where' => Yii::t('app', '来自哪里'),
            'when' => Yii::t('app', '选择服务时间'),
            'status' => Yii::t('app', '状态'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
            'isdel' => Yii::t('app', '1'),
            'study_status' => Yii::t('app', '学习状态:1认同，2不认同'),
            'study_time' => Yii::t('app', '学习所用时长（单位秒）'),
            'notice_status' => Yii::t('app', '通知状态：1已通知，2未通知'),
            'online_exam_time' => Yii::t('app', '在线考试时间（开始还是结束呢？）'),
            'online_exam_score' => Yii::t('app', '在线考试成绩'),
            'exam_result' => Yii::t('app', '现场考试结果：1通过，2未通过'),
            'operation_time' => Yii::t('app', '实操考试时间'),
            'operation_score' => Yii::t('app', '实操考试成绩'),
            'test_status' => Yii::t('app', '试工状态：1.安排试工，2：不用试工'),
            'test_situation' => Yii::t('app', '试工情况'),
            'test_result' => Yii::t('app', '试工结果'),
            'sign_status' => Yii::t('app', '签约状态'),
        ];
    }
    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        $user = self::findById($id);
        if ($user) {
            return new static($user);
        }
        return null;
    }
    
    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        $user = self::find()->where(array('accessToken' => $token))->one();
        if ($user) {
            return new static($user);
        }
        return null;
    }
    
    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username) {
        $user = self::find()->where(array('username' => $username))->one();
        if ($user) {
            return new static($user);
        }
    
        return null;
    }
    
    public static function findById($id) {
        $user = self::find()->where(array('id' => $id))->asArray()->one();
        if ($user) {
            return new static($user);
        }
    
        return null;
    }
    
    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->auth_key;
    }
    
    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return $this->authKey === $authKey;
    }
    
    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     * 在创建用户的时候，也需要对密码进行操作
     */
    public function validatePassword($password) {
//         //方法一:使用自带的加密方式
//         return $this->password === md5($password);
    
        //方法二：通过YII自带的验证方式来验证hash是否正确
        return Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }
    /**
     * 给用户发送手机验证码
     */
    public function sendMobileValidateCode()
    {
        if(empty($this->mobile)){
            throw new HttpException(401, '手机号不能为空');
        }
        $code = rand(1000, 9999);
        $msg = "登录验证码为:  {$code}，守住验证码，打死都不能告诉别人哦！唯一客服热线4006767636";
        $reqdata = http_build_query([
            'userId'=>'J02356',
            'password'=>'556201',
            'pszMobis'=>$this->mobile,
            'pszMsg'=>$msg,
            'iMobiCount'=>'1',
            'pszSubPort'=>'*'
        ]);
        $url='http://ws.montnets.com:9006/MWGate/wmgw.asmx/MongateCsSpSendSmsNew?'.$reqdata;
        $ch = \curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $date=  curl_exec($ch);
        curl_close($ch);
        $key = $this->mobile.'_mobile_code';
        \Yii::$app->cache->set($key, $code, 3600*24);
        return $code;
    }
    /**
     * 获取用户的手机验证码
     */
    public function getMobileValidateCode()
    {
        $key = $this->mobile.'_mobile_code';
        return \Yii::$app->cache->get($key);
    }
    
}
