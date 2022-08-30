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

    /**
     * @return \yii\db\ActiveQuery
     */

    public function getCities()
    {
        return $this->hasMany(Cities::class, ['country_id'=>'id']);
    }

}