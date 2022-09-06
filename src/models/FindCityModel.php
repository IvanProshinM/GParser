<?php

namespace app\models;

class FindCityModel extends \yii\base\Model
{
    public $country;
    public $city;
    public $category;

    public function rules()
    {
        return [
            [["country", "city", "category"], "required"],
            [["country", "city", "category"], "string"],
        ];
    }


}