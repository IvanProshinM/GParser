<?php

namespace app\controllers;


use PHPHtmlParser\Dom;
use app\models\Country;

class ParseController extends \yii\web\Controller


    /*Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36*/


{
    public function actionParse($url, $referer = 'https://google.com/')
    {

    }

    public function actionGetPage()
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


    public function actionJsonPage()
    {



    }

}