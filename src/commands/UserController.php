<?php

namespace app\commands;

use app\models\User;
use yii\console\Controller;

class UserController extends Controller
{
    public function actionAdd($login, $userName, $password)
    {
        $user = new User();
        $user->login = $login;
        $user->userName = $userName;
        $user->password = $password;
        $user->isActive = true;
        $user->save();
    }
}