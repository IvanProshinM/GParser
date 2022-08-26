<?php


namespace app\services;


class JsonParseService
{

    public function getCities($i)
    {
        $filepath = \Yii::getAlias("@app/countries+cities.json");
        $str = file_get_contents($filepath);
        $json = json_decode($str, true);
        $cities = [];
        $result = [];
        if ($json[$i]['name']) {
            foreach ($json[$i]["cities"] as $city)
            {
                array_push($cities, $city['name']);
            }
            $result = [$json[$i]['name'] => $cities];
        }
        return $result;
    }


}