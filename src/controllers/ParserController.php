<?php

namespace app\controllers;


use app\models\Category;
use app\models\Cities;
use app\services\CreateExcelSheetService;
use app\services\JsonParseService;
use app\models\Country;
use app\models\FindCityModel;
use app\services\QueryDetailService;
use app\services\QueryTextService;
use app\services\SearchDetailService;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class ParserController extends \yii\web\Controller
{


    public function behaviors()
    {

        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['json-page'],
                'rules' => [
                    [
                        'actions' => ['json-page'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    Yii::$app->session->setFlash('error', 'Для использования требуется авторизация');
                    Yii::$app->controller->redirect('/auth/login');
                }
            ],

        ];
    }


    /**
     * @var QueryDetailService
     */

    private $queryDetailService;

    /**
     * @var QueryTextService
     */

    private $queryTextService;

    /**
     * @var JsonParseService
     */

    private $jsonParseService;

    /**
     * @var CreateExcelSheetService
     */

    private $createExcelSheetService;


    /**
     * @var SearchDetailService
     */

    private $searchDetailService;


    public function __construct($id,
        $module,
                                JsonParseService $jsonParseService,
                                CreateExcelSheetService $createExcelSheetService,
                                SearchDetailService $searchDetailService,
                                QueryTextService $queryTextService,
                                QueryDetailService $queryDetailService,
        $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->jsonParseService = $jsonParseService;
        $this->createExcelSheetService = $createExcelSheetService;
        $this->searchDetailService = $searchDetailService;
        $this->queryTextService = $queryTextService;
        $this->queryDetailService = $queryDetailService;
    }


    public function actionJsonPage()
    {
        $indexRow = 2;
        $model = new FindCityModel();
        $blankSheet = $this->createExcelSheetService->CreateSheet();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            foreach ($model->categoryList as $category) {
                $categories = Category::find()
                    ->where(["id" => $category])
                    ->one();
                $categoryList[] = $categories->name;
            }

            foreach ($categoryList as $categorySearch) {
                $indexCol = 1;
                $queryArray = $this->queryTextService->createQuery($model, $categorySearch);
                foreach ($queryArray['results'] as $place) {
                    $detailQueryArray = $this->queryDetailService->createQuery($place);
                    $resultArray = $this->searchDetailService->detail($place, $categorySearch, $model, $detailQueryArray);
                    foreach ($resultArray as $result) {
                        $sheet = $blankSheet->getActiveSheet()->setCellValueByColumnAndRow($indexCol++, $indexRow, $result);
                    }
                    $indexRow++;
                    $indexCol = 1;
                }


                if ($queryArray['next_page_token']) {
                    sleep(3);
                    $tokenQueryUrl = 'https://maps.googleapis.com/maps/api/place/textsearch/json?';
                    $tokenQueryArray = [
                        'pagetoken' => $queryArray['next_page_token'],
                        'key' => 'AIzaSyDgKrL7ZGekAAuAgW6-hi936Nxa_6LAVPM'
                    ];
                    $tokenQueryUrl .= http_build_query($tokenQueryArray, '', '&',);
                    $tokenQueryJson = file_get_contents($tokenQueryUrl);
                    $queryArray = json_decode($tokenQueryJson, true);
                    foreach ($queryArray['results'] as $place) {
                        $detailQueryArray = $this->queryDetailService->createQuery($place);
                        $resultArray = $this->searchDetailService->detail($place, $categorySearch, $model, $detailQueryArray);
                        foreach ($resultArray as $result) {
                            $sheet = $blankSheet->getActiveSheet()->setCellValueByColumnAndRow($indexCol++, $indexRow, $result);
                        }
                        $indexRow++;
                        $indexCol = 1;
                    }

                }
                if ($queryArray['next_page_token']) {
                    sleep(3);
                    $tokenQueryUrl = 'https://maps.googleapis.com/maps/api/place/textsearch/json?';
                    $tokenQueryArray = [
                        'pagetoken' => $queryArray['next_page_token'],
                        'key' => 'AIzaSyDgKrL7ZGekAAuAgW6-hi936Nxa_6LAVPM'
                    ];
                    $tokenQueryUrl .= http_build_query($tokenQueryArray, '', '&',);
                    $tokenQueryJson = file_get_contents($tokenQueryUrl);
                    $queryArray = json_decode($tokenQueryJson, true);
                    foreach ($queryArray['results'] as $place) {
                        $detailQueryArray = $this->queryDetailService->createQuery($place);
                        $resultArray = $this->searchDetailService->detail($place, $categorySearch, $model, $detailQueryArray);
                        foreach ($resultArray as $result) {
                            $sheet = $blankSheet->getActiveSheet()->setCellValueByColumnAndRow($indexCol++, $indexRow, $result);
                        }
                        $indexRow++;
                        $indexCol = 1;
                    }
                }
            }

            $writer = new Xlsx($blankSheet);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . urlencode('salary_manager.xlsx')
                . '"');
            ob_end_clean();
            $writer->save('php://output');
            exit();
        }
        return $this->render('findCity', ['model' => $model]);
    }


    public
    function actionSearchCountry()
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


    public
    function actionSearchCity()
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


    public
    function actionSearchCategory($q = null, $id = null)
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


    public
    function actionSpreadSheet()
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