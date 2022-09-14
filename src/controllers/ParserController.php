<?php

namespace app\controllers;


use app\models\Category;
use app\models\Cities;
use app\services\CreateExcelSheetService;
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

    /**
     * @var CreateExcelSheetService
     */

    private $createExcelSheetService;

    public function __construct($id,
        $module,
                                JsonParseService $jsonParseService,
                                CreateExcelSheetService $createExcelSheetService,
        $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->jsonParseService = $jsonParseService;
        $this->createExcelSheetService = $createExcelSheetService;
    }


    public function actionJsonPage()
    {
        $resultArray = [];
        $model = new FindCityModel();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
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
                        'placeid' => $place['place_id'],
                        'fields' => 'opening_hours,website,rating,formatted_address,international_phone_number',
                        'key' => 'AIzaSyDgKrL7ZGekAAuAgW6-hi936Nxa_6LAVPM'
                    ];
                    $detailQueryUrl .= http_build_query($detailQueryArray, '', '&',);
                    $detailQueryJson = file_get_contents($detailQueryUrl);
                    $detailQueryArray = json_decode($detailQueryJson, true);
                    $resultArray = [
                        'name' => $place['name'],
                        'category' => $categorySearch,
                        'country' => $model->country,
                        'city' => $model->city,
                        'address' => $place['formatted_address'],
                        'locationLat' => $place['geometry']['location']['lat'],
                        'locationLng' => $place['geometry']['location']['lng'],
                        'website' => $detailQueryArray['result']['website'],
                        'rating' => $place['rating'],
                        'Monday' => str_replace('Monday:', '', $detailQueryArray['result']['opening_hours']['weekday_text'][0]),
                        'Tuesday' => str_replace('Tuesday:', '', $detailQueryArray['result']['opening_hours']['weekday_text'][1]),
                        'Wednesday' => str_replace('Wednesday:', '', $detailQueryArray['result']['opening_hours']['weekday_text'][2]),
                        'Thursday' => str_replace('Thursday:', '', $detailQueryArray['result']['opening_hours']['weekday_text'][3]),
                        'Friday' => str_replace('Friday:', '', $detailQueryArray['result']['opening_hours']['weekday_text'][4]),
                        'Saturday' => str_replace('Saturday:', '', $detailQueryArray['result']['opening_hours']['weekday_text'][5]),
                        'Sunday' => str_replace('Sunday:', '', $detailQueryArray['result']['opening_hours']['weekday_text'][6]),


                    ];
                    VarDumper::dump($detailQueryArray, 5, true);

                }
                VarDumper::dump($detailQueryArray, 5, true);


                /* $createExcel = $this->createExcelSheetService->CreateSheet($resultArray);*/

            }
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