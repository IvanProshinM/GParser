<?php

use yii\db\Migration;

/**
 * Class m220829_165743_create_cities_table
 */
class m220829_165743_create_cities_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{cities}}', [
            'id' => $this->primaryKey(),
            'country_id' => $this->integer(),
            'cityName'=>$this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{cities}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220829_165743_create_cities_table cannot be reverted.\n";

        return false;
    }
    */
}
