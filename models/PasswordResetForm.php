<?php
namespace app\models;

use app\models\User;
use Yii;
use yii\base\Model;

class PasswordResetForm extends Model{
    public $newpass;

    public function rules(){
        return [
            [['newpass'],'required'],
            ['newpass', 'string', 'min' => 6],
        ];
    }

    public function attributeLabels(){
        return [
            'newpass'=>'New Password',
        ];
    }
}
