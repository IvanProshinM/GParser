<?php

namespace app\models;

/**
 * @property string $createdBy;
 * @property string $country;
 * @property string $city;
 * @property string $category;
 */


class Project extends \yii\db\ActiveRecord
{


    public function rules()
    {
        return [
            [["createdBy", "country", "city", "category"], "string" ],
            [["createdBy", "country", "city", "category"], "required"]

        ];
    }

}