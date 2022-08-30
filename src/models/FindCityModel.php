<?php

namespace app\models;

class FindCityModel extends \yii\base\Model
{
    public $country;

    public function rules()
    {
        return [
            [["country"], "required"],
            [["country"], "string"],
        ];
    }


}