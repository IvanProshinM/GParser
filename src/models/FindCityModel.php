<?php


class FindCityModel extends \yii\base\Model
{
    public string $country;

    public function rules()
    {
        return [
            [["country"], "required"],
            [["country"], "string"],
        ];
    }


}