<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use frontend\models\User;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $mobile;
//     public $username;
    public $mobile_code;
    public $rememberMe = false;

    private $_user = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['mobile', 'mobile_code'], 'required'],
            ['mobile', 'string', 'min' => 11, 'max' => 11],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['mobile', 'validateMobile'],
            ['mobile_code', 'validateMobileCode'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mobile' => Yii::t('app', '手机号'),
            'mobile_code' => Yii::t('app', '手机验证码'),
            'rememberMe' => Yii::t('app', '记住账号'),
        ];
    }
    /**
     * 验证手机号是否存在
     * @param unknown $attribute
     * @param unknown $params
     */
    public function validateMobile($attribute, $params)
    {
        $user = $this->getUser();
        if(empty($user)){
            $this->addError($attribute, Yii::t('app', '手机号不存在.'));
        }
    }
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateMobileCode($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            $cache_code = $user->getMobileValidateCode();
            if(isset($cache_code) && $cache_code!=$this->mobile_code){
                $this->addError($attribute, Yii::t('app', '手机验证码错误.'));
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return \Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[mobile]]
     *
     * @return SystemUser|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::find()->where(['mobile'=>$this->mobile])->one();
        }
        return $this->_user;
    }
}
