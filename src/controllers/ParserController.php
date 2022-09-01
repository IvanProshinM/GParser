<?php

namespace app\controllers;


use app\models\Cities;
use app\models\Citites;
use app\services\JsonParseService;
use PHPHtmlParser\Dom;
use app\models\Country;
use PHPUnit\Util\Json;
use yii\helpers\VarDumper;
use app\models\FindCityModel;
use Yii;

class ParserController extends \yii\web\Controller
{


    /**
     * @var JsonParseService
     */

    private $jsonParseService;

    public function __construct($id,
        $module,
                                JsonParseService $jsonParseService,
        $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->jsonParseService = $jsonParseService;
    }


    public function actionParse()
    {
        $filepath = \Yii::getAlias("@app/example.json");
        $str = file_get_contents($filepath);
        $json = json_decode($str, true);
        VarDumper::dump($json, 2, true);
        echo $json[0]["cities"][0]["name"];
    }

    public function actionGetPage()
    {

    }


    public function actionJsonPage()
    {

        $model = new FindCityModel();
        $cityData = [];
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $country = Country::find()
                ->where(['name' => $model->country])
                ->one();
            if (!$country) {
                echo 'страны с таким названем не найдено';
            }

            $city = $country->getCities()
                ->andWhere(['country_id' => $country->id])
                ->andWhere(['cityName'=>$model->city])
                ->all();
            echo $country . $city;

        }
        return $this->render('findCity', ['model' => $model]);
    }


    public function actionSearchCountry()
    {
        $getParams = Yii::$app->request->get();
        $countryData = [];
        $country = Country::find()
            ->where(['like', 'name', '%' . $getParams['country'] . '%', false])
            ->all();
        foreach ($country as $countryList) {
            $countryData[] = ['value' => $countryList['name']];
        }
        return json_encode($countryData);
    }


    public function actionSearchCity()
    {
        $getParams = Yii::$app->request->get();
        $cityData = [];
        $city = Cities::find()
            ->where(['like', 'name', '%' . $getParams['city'] . '%', false])
            ->andWhere(['country_id'=>$getParams['country_id']])
            ->all();
        foreach ($city as $cityList) {
            $cityData[] = ['value' => $cityList['name']];
        }
        return json_encode($cityData);
    }


}