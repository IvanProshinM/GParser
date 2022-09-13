<?php

namespace app\models;

use yii\db\ActiveRecord;

class Category extends ActiveRecord
{
    /**
     * @var mixed|null
     */
    private $name;

    /**
     * @property string $name
     * @property integer $id
     */


    public static function tableName()
    {
        return "category";
    }

    public function rules()
    {
        return [
            [['name'], 'string'],
            [['id'], 'integer'],
            [['name'], 'required']
        ];
    }


}