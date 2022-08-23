<?php


namespace app\models;

use app\models\User;



class loginModel extends \yii\base\Model
{

    public  $login;
    public  $password;


    public function rules()
    {
        return [
            [["login", "password"], "required"],
            [["login"], "string", 'min' => 4, 'max' => 16],
            [["login"], "match", "pattern"=>"/^[A-Za-z0-9]+$/" ],
        ];
    }


}