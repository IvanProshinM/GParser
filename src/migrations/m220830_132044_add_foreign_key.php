<?php

use yii\db\Migration;

/**
 * Class m220830_132044_add_foreign_key
 */
class m220830_132044_add_foreign_key extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey('country_id', 'cities', 'country_id', 'countries', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('country_id', 'cities');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220830_132044_add_foreign_key cannot be reverted.\n";

        return false;
    }
    */
}
