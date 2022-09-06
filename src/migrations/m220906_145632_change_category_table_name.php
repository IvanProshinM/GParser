<?php

use yii\db\Migration;

/**
 * Class m220906_145632_change_category_table_name
 */
class m220906_145632_change_category_table_name extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('category', 'categoryName', 'name');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('category', 'name', 'categoryName');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220906_145632_change_category_table_name cannot be reverted.\n";

        return false;
    }
    */
}
