<?php

namespace app\controllers;


use app\services\JsonParseService;
use PHPHtmlParser\Dom;
use app\models\Country;
use yii\helpers\VarDumper;
use app\models\FindCityModel;
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

    }


    public function actionJsonPage()
    {
        $model = new FindCityModel();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $countries = $this->jsonParseService->getCity($model->country, $model->city);

        }
        return $this->render('findCity', ['model' => $model]);
    }
}