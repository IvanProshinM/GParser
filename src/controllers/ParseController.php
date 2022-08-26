<?php

namespace app\controllers;


use app\services\JsonParseService;
use PHPHtmlParser\Dom;
use app\models\Country;
use yii\helpers\VarDumper;
use Yii;

class ParseController extends \yii\web\Controller
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
        $model = new \FindCityModel();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
        }

        $filepath = \Yii::getAlias("@app/example.json");
        $str = file_get_contents($filepath);
        $json = json_decode($str, true);
        $i = 0;
        $j = 0;
        if ($json[$i]["name"] === "Afghanistan") {
            $cities = $json[$i]["cities"];

            foreach ($cities as $city) {
                echo $city["name"] . "<br/>";
            }
        }
        return $this->render('findCity', ['model' => $model]);
    }


    public function actionJsonPage()
    {
        $countries = $this->jsonParseService->getCities( 0);
    }
}