<?php

use yii\db\Migration;

/**
 * Class m220929_155757_add_changeColumnsName
 */
class m220929_155757_add_changeColumnsName extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('cities','longitude', 'float' );
        $this->alterColumn('cities','latitude', 'float' );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('cities','longitude', 'integer' );
        $this->alterColumn('cities','latitude', 'integer' );
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220929_155757_add_changeColumnsName cannot be reverted.\n";

        return false;
    }
    */
}
