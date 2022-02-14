<?php

use yii\db\Migration;

/**
 * Class m210126_121840_leads
 */
class m210126_121840_leads extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%leads}} ADD lead_status SMALLINT NOT NULL DEFAULT 0;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210126_121840_leads cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210126_121840_leads cannot be reverted.\n";

        return false;
    }
    */
}
