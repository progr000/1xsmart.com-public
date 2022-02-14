<?php

use yii\db\Migration;

/**
 * Class m200624_103334_users
 */
class m200624_103334_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->execute("ALTER TABLE {{%users}} ADD receive_system_notif SMALLINT NOT NULL DEFAULT 0;");
        $this->execute("ALTER TABLE {{%users}} ADD receive_lesson_notif SMALLINT NOT NULL DEFAULT 0;");


        $this->execute("CREATE INDEX idx_receive_system_notif ON {{%users}} USING BTREE (receive_system_notif);");
        $this->execute("CREATE INDEX idx_receive_lesson_notif ON {{%users}} USING BTREE (receive_lesson_notif);");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200624_103334_users cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200624_103334_users cannot be reverted.\n";

        return false;
    }
    */
}
