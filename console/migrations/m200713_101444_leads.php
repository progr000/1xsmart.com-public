<?php

use yii\db\Migration;

/**
 * Class m200713_101444_leads
 */
class m200713_101444_leads extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%leads}} ADD lead_in_work TIMESTAMP WITHOUT TIME ZONE DEFAULT NULL;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200713_101444_leads cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200713_101444_leads cannot be reverted.\n";

        return false;
    }
    */
}
