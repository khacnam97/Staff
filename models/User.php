<?php

namespace app\models;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;

class User extends \yii\db\ActiveRecord  implements \yii\web\IdentityInterface
{

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%user}}';
    }
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            [['username', 'password', 'email', 'description'], 'string', 'max' => 255],
            [['username', 'password', 'email'], 'required'],
            ['username', 'unique',  'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['email', 'email'],
            ['email', 'unique',  'message' => 'This email address has already been taken.'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'email' => 'Email',
            'description' => 'Description',
            'old_password' => 'Old Password',
            'repeat_password' => 'Repeat Password',
            'new_password' => 'New Password'
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            // TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {

        return static::findOne(['username' => $username]);
        //echo "<pre>";  print_r($user); exit();

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
        //return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        // return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
//    public function validatePassword($password) {
//        return $this->password === $password;
//    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }



    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        // $this->auth_key = Yii::$app->security->generateRandomString();
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

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
     public static function findByPasswordResetToken($token) {
         if (!static::isPasswordResetTokenValid($token)) {
             return null;
         }

         return static::findOne([
             'password_reset_token' => $token,
             // 'status' => self::STATUS_ACTIVE,
         ]);
     }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     * 
     */
//     public static function isPasswordResetTokenValid($token) {
//         if (empty($token)) {
//             return false;
//         }
//         $expire = Yii::$app->params['user.passwordResetTokenExpire'];
//         $parts = explode('_', $token);
//         $timestamp = (int) end($parts);
//         return $timestamp + $expire >= time();
//     }
}
