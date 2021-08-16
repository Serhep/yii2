<?php

use yii\db\Migration;

/**
 * Class m210802_044159_add_category_columns
 */
class m210802_044159_add_category_columns extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('category', 'expanded', $this->boolean());
        $this->addColumn('category', 'leaf', $this->boolean());
    }
    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('category', 'expanded');
        $this->dropColumn('category', 'leaf');
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
        echo "m210802_044159_add_category_columns cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210802_044159_add_category_columns cannot be reverted.\n";

        return false;
    }
    */
}
