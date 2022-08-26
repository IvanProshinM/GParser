<?php

namespace app\commands;

use app\models\Country;
use PHPHtmlParser\Dom;

class InitController extends \yii\console\Controller {

    public function actionCountries()
    {
        $dom = new Dom();
        $countries = [];
        $i = 0;
        $dom->loadFromUrl('https://www.un.org/ru/about-us/member-states');
        foreach ($dom->find('h2') as $value) {
            $model = new Country();
            $countries[$i] = str_replace("*", "", strip_tags($value));;
            $model->name = $countries[$i];
            $model->save();
            $i++;
        }
    }

    public function actionCities() {

        $filepath = \Yii::getAlias("@app/countries+cities.json");
        $str = file_get_contents($filepath);
        $json = json_decode($str, true);
        $i = 0;
        if ($json[$i]["name"] === "Afghanistan") {
            $cities = $json[$i]["cities"];

            foreach ($cities as $city) {
                echo $city["name"] . "<br/>";
            }
        }
    }






}