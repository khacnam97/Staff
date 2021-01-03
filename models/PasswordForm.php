<?php
namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;

class PasswordForm extends Model{
    public $old_password;
    public $new_password;
    public $repeat_password;

    public function rules(){
        return [
            [['old_password','new_password','repeat_password'],'required'],
            ['old_password','validatePassword'],
            ['new_password', 'string', 'min' => 6],
            ['repeat_password','compare','compareAttribute'=>'new_password'],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $userId = Yii::$app->user->identity->id;
            $user = User::findOne($userId) ;
            $password = $user->password;
            // Yii::$app->security->validatePassword($password, $this->password)
            if ( !(Yii::$app->security->validatePassword($this->old_password, $password))) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }
    public function attributeLabels(){
        return [
            'old_passord'=>'Old Password',
            'new_password'=>'New Password',
            'repeat_password'=>'Repeat New Password',
        ];
    }
}
