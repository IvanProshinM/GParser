<?php

namespace app\commands;

use app\models\Cities;
use app\models\Country;
use PHPHtmlParser\Dom;

class InitController extends \yii\console\Controller
{

    public function actionCountries()
    {
        $filepath = \Yii::getAlias("@app/countries+cities.json");
        $json = \JsonMachine\Items::fromFile($filepath);
        foreach ($json as $country) {
            $model = new Country();
            $model->name = $country->name;
            $model->save();
        }
    }

    public function actionCities()
    {

        $filepath = \Yii::getAlias("@app/countries+cities.json");
        $json = \JsonMachine\Items::fromFile($filepath);
        foreach ($json as $countryObj) {
            foreach ($countryObj->cities as $city) {
                $model = new Cities();
                $model->cityName = $city->name;
                $model->country_id = $countryObj->id;
                $model->save();
            }
        }
    }

    public function actionCategories()
    {

        $filepath = \Yii::getAlias("@app/countries+cities.json");
        $json = \JsonMachine\Items::fromFile($filepath);
        foreach ($json as $countryObj) {
            foreach ($countryObj->cities as $city) {
                $model = new Cities();
                $model->cityName = $city->name;
                $model->country_id = $countryObj->id;
                $model->save();
            }
        }
    }




}