<?php

namespace app\models;

use yii\db\ActiveRecord;

class Cities extends ActiveRecord
{
    /**
     * @property string cityName;
     * @property int country_id;
     */

    public static function tableName()
    {
        return "cities";
    }

    public function rules()
    {
        return [
            [['cityName',"country_id" ], "required"],
            [['cityName'], 'string'],
            [['country_id'], 'integer'],
        ];
    }


    public function getCountry() {

        return $this->hasOne(Country::class, ['id'=>'country_id']);
    }


}