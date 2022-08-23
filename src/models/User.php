<?php

namespace app\models;

use phpDocumentor\Reflection\Types\Boolean;
use yii\db\ActiveRecord;
use Yii;
use yii\web\IdentityInterface;


/**
 * @property string $userName;
 * @property string $login;
 * @property string $password;
 * @property boolean|null $isActive;
 *
 */




class User extends ActiveRecord implements IdentityInterface
{


    public $username;

    public static function tableName()
    {
       return "user";
    }

    public function rules() {
        return [
            [["userName", "login", "password"], "string"],
            [["isActive"], "boolean"],
        ];
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }


    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    public static function findIdentity($id)
    {
        return self::findOne($id);
    }



}