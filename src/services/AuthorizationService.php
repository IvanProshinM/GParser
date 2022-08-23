<?php

namespace app\services;

use app\models\loginModel;
use app\models\User;


/**
 * @var loginModel $model;
 */

class AuthorizationService
{

    public function authorization(loginModel $model)
    {
        $user =User::find()
            ->where(['login'=>$model->login])
            ->one();
        if (!$user) {
            return null;
        }
        return $user;
    }


}