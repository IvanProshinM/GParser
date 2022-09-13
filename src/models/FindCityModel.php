<?php

namespace app\models;

class FindCityModel extends \yii\base\Model
{
    public $country;
    public $city;
    public array $categoryList = [];

    public function rules()
    {
        return [
            [["country", "city", "categoryList"], "required"],
            [["country", "city"], "string"],
        ];
    }


}