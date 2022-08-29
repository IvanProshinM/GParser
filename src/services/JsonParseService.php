<?php


namespace app\services;


use yii\helpers\VarDumper;

class JsonParseService
{

    public function getCities()
    {
        $filepath = \Yii::getAlias("@app/countries+cities.json");
        $str = file_get_contents($filepath);
        $json = \JsonMachine\Items::fromFile($filepath);
        $result = [];
        foreach ($json as $countryObj) {

            $countryName = $countryObj->name;
            $result[$countryName] = [];
            foreach ($countryObj->cities as $city) {
                $result[$countryName][] = $city->name;
            }
        }


        return $result;
    }

    public function getCity($country, $city)
    {
        $filepath = \Yii::getAlias("@app/countries+cities.json");
        $str = file_get_contents($filepath);
        $json = \JsonMachine\Items::fromFile($filepath);
        $result = [];
        foreach ($json as $countryObj) {
            $countryName = $countryObj->name;
            $result[$countryName] = [];
            foreach ($countryObj->cities as $cities) {
                $result[$countryName][] = $cities->name;
            }

        }
        $queryStr = $this->findCity($result, $country, $city);
        echo $queryStr;
    }


    public function findCity(array $result, $country, $city)
    {
        $countryResult = null;
        $cityResult = null;
        foreach ($result as $countryName => $cityList) {
            if ($countryName !== $country) {
                continue;
            }
            foreach ($cityList as $cityName) {
                if ($cityName !== $city) {
                    continue;
                }
                $cityResult = $city;


                break;
            }
            $countryResult = $countryName;
            break;
        }

        if ($countryResult === null) {
            return "Страна с таким названием не найдена";
        }

        if ($cityResult === null) {
            return "Город с таким названием не найден";
        }
        return $countryResult . " " . $cityResult;
    }

}



/*
if ($cityResult === null) {
    return "Город с таким названием не найден";
}*/


