<?php

use yii\db\Migration;

/**
 * Class m220929_150302_add_cityCoordinates
 */
class m220929_150302_add_cityCoordinates extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cities', 'longitude', 'integer');
        $this->addColumn('cities', 'latitude', 'integer');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('cities', 'longitude');
        $this->dropColumn('cities', 'latitude');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220929_150302_add_cityCoordinates cannot be reverted.\n";

        return false;
    }
    */
}
