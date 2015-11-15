<?php
namespace dbbase\models\system;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;
use yii\helpers\ArrayHelper;
use dbbase\models\ActiveRecord;
use boss\components\RbacHelper;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $role
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class SystemUser extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = -1;
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const ROLE_USER = 10;
    
    private $_roles = [];
    public function setRoles($roles)
    {
        if(is_array($roles)){
            $this->_roles = $roles;
        }else{
            $this->_roles = [$roles];
        }
    }
    public function saveRoles()
    {
        $roles = $this->_roles;
        $auth = \Yii::$app->authManager;
        if(!empty($roles)){
            $auth->revokeAll($this->id);
            foreach ($roles as $role_name){
                $role = $auth->getRole($role_name);
                if(!empty($role)){
                    $auth->assign($role, $this->id);
                }
            }
            RbacHelper::updateConfigVersion();
            return $auth->getRolesByUser($this->id);
        }else{
            return [];
        }
    }
    public function getRoles()
    {
        return ArrayHelper::map(Yii::$app->authManager->getRolesByUser($this->id), 'name', 'name');
    }
    public function getRolesLabel()
    {
        return ArrayHelper::map(Yii::$app->authManager->getRolesByUser($this->id), 'name', 'description');
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%system_user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],

            ['mobile', 'string'],
            ['mobile', 'unique'],
            ['mobile', 'required'],
            ['classify', 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'password' => Yii::t('app', 'Password'),
            'repassword' => Yii::t('app', 'Repassword'),
            'auth_key'=> Yii::t('app', 'AuthKey'),
            'password_hash'=> Yii::t('app', 'PasswordHash'),
            'password_reset_token'=> Yii::t('app', 'PasswordResetToken'),
            'email' => Yii::t('app', 'Email'),
            'roles' => Yii::t('app', 'Role'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'create_user_id' => Yii::t('app', 'Create User Id'),
            'update_user_id' => Yii::t('app', 'Update User Id'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        if(trim($username)=='admin'){
            return static::findOne([
                'username' => $username, 
                'status' => self::STATUS_ACTIVE
                
            ]);
        }
        return static::findOne([
            'mobile' => $username, 
            'status' => self::STATUS_ACTIVE
        ]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public $_password = '';
    public function getPassword()
    {
        return $this->_password;
    }
    public function setPassword($password)
    {
        if(!empty($password)){
            $this->_password = $password;
            $this->password_hash = Yii::$app->security->generatePasswordHash($password);
        }
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
