<?php

namespace app\models;

use yii\db\ActiveRecord;

class Category extends ActiveRecord
{

    /**
     * @property string $categoryName
     */


    public static function tableName()
    {
        return "category";
    }

    public function rules()
    {
        return [
            [['categoryName'], 'string'],
            [['categoryName'], 'required']
        ];
    }


}