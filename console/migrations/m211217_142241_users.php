<?php

use yii\db\Migration;

/**
 * Class m211217_142241_users
 */
class m211217_142241_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("UPDATE {{%users}} SET receive_system_notif=1, receive_lesson_notif=1;");
        $this->execute("ALTER TABLE {{%users}} ADD last_system_language CHARACTER VARYING(5) DEFAULT 'en';");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211217_142241_users cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211217_142241_users cannot be reverted.\n";

        return false;
    }
    */
}
