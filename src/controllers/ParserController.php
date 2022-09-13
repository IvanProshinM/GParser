<?php

namespace app\controllers;


use app\models\Category;
use app\models\Cities;
use app\services\JsonParseService;
use app\models\Country;
use app\models\FindCityModel;
use Yii;
use yii\db\Query;
use yii\helpers\VarDumper;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


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
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $categoryList = [];
            foreach ($model->categoryList as $category) {
                $categories = Category::find()
                    ->where(["id" => $category])
                    ->one();
                $categoryList[] = $categories->name;
            }

            foreach ($categoryList as $categorySearch) {
                $queryUrl = "https://maps.googleapis.com/maps/api/place/textsearch/json?";
                $queryArray = [
                    'query' => $model->country . ' ' . $model->city . ' ' . $categorySearch,
                    'key' => 'AIzaSyDgKrL7ZGekAAuAgW6-hi936Nxa_6LAVPM'
                ];
                $queryUrl .= http_build_query($queryArray, '', '&',);
                $queryJson = file_get_contents($queryUrl);
                $queryArray = json_decode($queryJson, true);
                foreach ($queryArray['results'] as $place) {
                    $detailQueryUrl = 'https://maps.googleapis.com/maps/api/place/details/json?';
                        $detailQueryArray = [
                            'placeid'=>$place['place_id'],
                            'fields'=>'opening_hours',
                            'key'=> 'AIzaSyDgKrL7ZGekAAuAgW6-hi936Nxa_6LAVPM'
                        ];
                     $detailQueryUrl .= http_build_query($detailQueryArray, '', '&',);
                     $detailQueryJson = file_get_contents($detailQueryUrl);
                     $detailQueryArray = json_decode($detailQueryJson, true);
                    VarDumper::dump($detailQueryArray, 5, true);
                }

            }

            /* https://maps.googleapis.com/maps/api/place/textsearch/json?query=Russia%20Magnitogorsk%20Home Cinema Installation&key=AIzaSyDgKrL7ZGekAAuAgW6-hi936Nxa_6LAVPM*/

            /*https://maps.googleapis.com/maps/api/place/textsearch/json?query=Russia%20Magnitogorsk%20Home Cinema Installation&amp;key=AIzaSyDgKrL7ZGekAAuAgW6-hi936Nxa_6LAVPM*/


            /* $queryStr = "https://maps.googleapis.com/maps/api/place/textsearch/json?query=" . $model->country . '%20' . $model->city . '%20' . $model->category . '&key=AIzaSyDgKrL7ZGekAAuAgW6-hi936Nxa_6LAVPM';*/

            /*   $queryStr = "https://maps.googleapis.com/maps/api/place/findplacefromtext/json?fields=lawyer%2Clibrary
    &input=Museum%20of%20Contemporary%20Art%20Australia
    &inputtype=textquery" .*/


            /*'&key=AIzaSyDgKrL7ZGekAAuAgW6-hi936Nxa_6LAVPM';

           var_dump($queryStr);
           $this->redirect($queryStr);*/

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


    public function actionSearchCategory($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query;
            $query->select('id, name AS text')
                ->from('category')
                ->where(['like', 'name', $q])
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Category::find($id)->name];
        }
        return $out;
    }


    public function actionSpreadSheet()
    {

        $sheetName = [
            'A1' => 'Category',
            'B1' => 'Name',
            'C1' => 'Address',
            'D1' => 'Country',
            'E1' => 'City',
            'F1' => 'Website',
            'G1' => 'Phone',
            'H1' => 'Email',
            'I1' => 'Zip Code',
            'J1' => 'Claimed',
            'K1' => 'Virtual tour',
            'L1' => 'Google Reviews',
            'M1' => 'Google Rating',
            'N1' => 'Photos',
            'O1' => 'Image',
            'P1' => 'Google',
            'Q1' => 'Facebook',
            'R1' => 'Twitter',
            'S1' => 'Instagram',
            'T1' => 'LinkedIn',
            'U1' => 'Yelp',
            'V1' => 'YouTube',
            'W1' => 'Pinterest',
            'X1' => 'Monday',
            'Y1' => 'Tuesday',
            'Z1' => 'Wednesday',
            'AA1' => 'Thursday',
            'AB1' => 'Friday',
            'AC1' => 'Saturday',
            'AD1' => 'Sunday',
        ];
        $sheetColor = [
            'A1' => '#C0C0C0',
            'B1' => '#FFFF00',
            'C1' => '#C0C0C0',
            'D1' => 'Country',
            'E1' => '#C0C0C0',
            'F1' => 'Website',
            'G1' => '#C0C0C0',
            'H1' => 'Email',
            'I1' => '#C0C0C0',
            'J1' => 'Claimed',
            'K1' => 'Virtual tour',
            'L1' => 'Google Reviews',
            'M1' => 'Google Rating',
            'N1' => 'Photos',
            'O1' => 'Image',
            'P1' => 'Google',
            'Q1' => 'Facebook',
            'R1' => 'Twitter',
            'S1' => 'Instagram',
            'T1' => 'LinkedIn',
            'U1' => 'Yelp',
            'V1' => 'YouTube',
            'W1' => 'Pinterest',
            'X1' => 'Monday',
            'Y1' => 'Tuesday',
            'Z1' => 'Wednesday',
            'AA1' => 'Thursday',
            'AB1' => 'Friday',
            'AC1' => 'Saturday',
            'AD1' => 'Sunday',

        ];
        $spreadsheet = new Spreadsheet();
        foreach ($sheetName as $index => $name) {
            $sheet = $spreadsheet->getActiveSheet()->setCellValue($index, $name);
            $sheet = $spreadsheet->getActiveSheet()->getStyle($index)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF0000');
        }
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode('salary_manager.xlsx')
            . '"');
        ob_end_clean();
        $writer->save('php://output');
        exit();


    }


}