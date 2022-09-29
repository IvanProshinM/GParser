<?php

use yii\db\Migration;

/**
 * Class m220929_163804_changeGeoColumnsName
 */
class m220929_163804_changeGeoColumnsName extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('cities','latitude', 'string');
        $this->alterColumn('cities','longitude', 'string');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('cities','latitude', 'float');
        $this->alterColumn('cities','latitude', 'float');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220929_163804_changeGeoColumnsName cannot be reverted.\n";

        return false;
    }
    */
}
