<?php

namespace app\services;

use app\models\FindCityModel;

class QueryTextService
{

    public function createQuery(FindCityModel $model, $categorySearch)
    {
        $queryUrl = "https://maps.googleapis.com/maps/api/place/textsearch/json?";
        $queryArray = [
            'query' => $model->country . ' ' . $model->city . ' ' . $categorySearch,
            'key' => 'AIzaSyDgKrL7ZGekAAuAgW6-hi936Nxa_6LAVPM'
        ];

        $queryUrl .= http_build_query($queryArray, '', '&',);
        $queryJson = file_get_contents($queryUrl);

        return json_decode($queryJson, true);
    }


}