<?php

namespace app\models;


class Country extends \yii\db\ActiveRecord
{
    /**
     * @property string $name
     */


    public static function tableName()
    {
        return "countries";
    }


    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string']
        ];

    }

}