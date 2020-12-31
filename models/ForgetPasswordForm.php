<?php
namespace app\models;

use app\models\User;
use Yii;
use yii\base\Model;

class ForgetPasswordForm extends Model{
    public $email_forget;


    public function rules(){
        return [
            [['email_forget'],'required'],
        ];
    }

    public function attributeLabels(){
        return [
            'email_forget'=>'Email forget',
        ];
    }
}
