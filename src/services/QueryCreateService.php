<?php


use yii\helpers\Url;

class QueryCreateService
{


    public function actionCreateQuery($country, $city)
    {

            $str = (['https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input=' . $country . '&input =' . $city . '&inputtype=textquery
  &key=AIzaSyAmbNzxqwAq6zPCGNbG4ZwDRXBLt07wqK4'  ]);
            return $str;
    }

}