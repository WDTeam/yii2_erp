<?php
namespace frontend\models;

use common\models\SystemUser;
use yii\base\Model;
use Yii;
use frontend\models\User;
/**
 * Signup form
 */
class SignupForm extends Model
{
    // public $email;
    public $mobile;
    //public $password;
    //public $username;
    //public $idnumber;
    public $verification;
   // public $ecn;
   // public $whatodo;
    //public $where;
    //public $when;
    public $mobile_code;
  
     private $_user = false;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
         //    ['username', 'filter', 'filter' => 'trim'],
           //  ['username', 'required'],
             //['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
             //['username', 'string', 'min' => 2, 'max' => 255],

            ['mobile', 'filter', 'filter' => 'trim'],
            [['mobile',], 'required','message'=>'请输入手机号'],
           // ['mobile', 'unique', 'targetClass' => '\frontend\models\User', 'message' => '手机号已经被注册，请点击登陆'],
            ['mobile', 'string', 'min' => 11, 'max' => 11],
            [['verification'],'required','message'=>'请输入验证码'],
             ['verification', 'validateMobileCode'],
            //['idnumber','string','min'=>18,'max'=>18],
            //['ecn','string','min'=>11,'max'=>11],
            
            // ['email', 'filter', 'filter' => 'trim'],
            // ['email', 'required'],
            // ['email', 'email'],
           //  ['idnumber', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This mobie address already been taken.'],

           // ['password', 'required'],
            //['password', 'string', 'min' => 4],
        ];
    }

    /**
     * Signs user up.
     *
     * @return SystemUser|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = $this->_user;
            if($user->getIsNewRecord()){
                if ($user->save(false)){
                    return $user;
                }
            }
            return $user;
        }
        return null;
    }
     
    public function attributeLabels()
    {
         return [
              'mobile' => Yii::t('app', '手机号'),
              'verification' => Yii::t('app', '验证码'),
         ];
    }    
    
    
      public function validateMobileCode($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            $cache_code = $user->getMobileValidateCode();
            if(isset($cache_code) && $cache_code!=$this->verification){
                $this->addError($attribute, Yii::t('app', '手机验证码错误.'));
            }
        }
    }
    
    public function getUser()
    {
        if ($this->_user === false) {
            $user = User::find()->where(['mobile'=>$this->mobile])->one();
            if(empty($user)){
                $user = new User();
            }
            $user->password_hash = Yii::$app->getSecurity()->generatePasswordHash($this->mobile_code);
            $this->_user = $user;
            $this->_user->mobile = $this->mobile;
            $user->created_at = time();
        }
        return $this->_user;
    }
    
}
