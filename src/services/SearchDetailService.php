<?php

namespace app\services;


use app\models\FindCityModel;
use yii\helpers\VarDumper;

class SearchDetailService
{

    public function detail($place, $categorySearch, FindCityModel $model, $detailQueryArray)
    {
        return   [
            'name' => $place['name'],
            'category' => $categorySearch,
            'country' => $model->country,
            'city' => $model->city,
            'address' => $place['formatted_address'],
            'locationLat' => $place['geometry']['location']['lat'],
            'locationLng' => $place['geometry']['location']['lng'],
            'website' => $detailQueryArray['result']['website'],
            'rating' => $place['rating'],
            'Monday' => str_replace('Monday:', '', $detailQueryArray['result']['opening_hours']['weekday_text'][0]),
            'Tuesday' => str_replace('Tuesday:', '', $detailQueryArray['result']['opening_hours']['weekday_text'][1]),
            'Wednesday' => str_replace('Wednesday:', '', $detailQueryArray['result']['opening_hours']['weekday_text'][2]),
            'Thursday' => str_replace('Thursday:', '', $detailQueryArray['result']['opening_hours']['weekday_text'][3]),
            'Friday' => str_replace('Friday:', '', $detailQueryArray['result']['opening_hours']['weekday_text'][4]),
            'Saturday' => str_replace('Saturday:', '', $detailQueryArray['result']['opening_hours']['weekday_text'][5]),
            'Sunday' => str_replace('Sunday:', '', $detailQueryArray['result']['opening_hours']['weekday_text'][6]),
            ];
    }

}