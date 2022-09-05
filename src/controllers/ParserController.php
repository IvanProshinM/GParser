<?php

namespace app\controllers;


use app\models\Cities;
use app\services\JsonParseService;
use app\models\Country;
use app\models\FindCityModel;
use PHPHtmlParser\Dom;
use Yii;
use yii\helpers\Url;

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


    public function actionJsonPage()
    {

        $model = new FindCityModel();
        $cityData = [];
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $queryStr = "https://maps.googleapis.com/maps/api/place/findplacefromtext/json?fields=formatted_address%2Copening_hours&input=' . $model->country . '&input=' . $model->city . '&inputtype=textquery&key=AIzaSyDgKrL7ZGekAAuAgW6-hi936Nxa_6LAVPM";
            $this->redirect($queryStr);
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
            ->where(['like', 'cityName', '%' . $getParams['city'] . '%', false])
    /*        ->andWhere(['country_id' => $getParams['country_id']])*/
            ->all();
        foreach ($city as $cityList) {
            $cityData[] = ['value' => $cityList['cityName']];
        }
        return json_encode($cityData);
    }


    /**
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     * @throws \PHPHtmlParser\Exceptions\CircularException
     * @throws \PHPHtmlParser\Exceptions\StrictException
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \PHPHtmlParser\Exceptions\NotLoadedException
     * @throws \PHPHtmlParser\Exceptions\ContentLengthException
     * @throws \PHPHtmlParser\Exceptions\LogicalException
     */
    public function actionUrl()
    {
        $dom = new Dom();
        $dom->loadFromUrl("https://www.omnicoreagency.com/google-business-pofile-categories-list/");
        $td = $dom->find('.elementor-widget-container > table > tbody *' );
       var_dump($td);
    }


}