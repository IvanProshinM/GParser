<?php

namespace app\models;

class FindCityModel extends \yii\base\Model
{
    public $country;
    public $city;

    public function rules()
    {
        return [
            [["country", "city"], "required"],
            [["country", "city"], "string"],
        ];
    }


}