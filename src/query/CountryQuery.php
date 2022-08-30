<?php

namespace app\query;

/**
 * @see \app\models\Category
 */

class CountryQuery extends \yii\db\ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return \app\models\Country[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\Country|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

}