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
            ['old_password','findPasswords'],
            ['repeat_password','compare','compareAttribute'=>'new_password'],
        ];
    }

    public function findPasswords($attribute, $params){
        $userId = Yii::$app->user->identity->id;
        $user = User::findOne($userId) ;
        $password = $user->password;
        $a =Yii::$app->security->generatePasswordHash($this->old_password);
        if($password!=$this->old_password)
            $this->addError($attribute,'Old password is incorrect');
    }

    public function attributeLabels(){
        return [
            'old_passord'=>'Old Password',
            'new_password'=>'New Password',
            'repeat_password'=>'Repeat New Password',
        ];
    }
}
