<?php

use yii\db\Migration;

/**
 * Class m210807_044713_add_param_to_column_category_table
 */
class m210807_044713_add_param_to_column_category_table extends Migration
{

        /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('category', 'params', $this->json());
    }
    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('category', 'papams');
    }
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210807_044713_add_param_to_column_category_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210807_044713_add_param_to_column_category_table cannot be reverted.\n";

        return false;
    }
    */
}
