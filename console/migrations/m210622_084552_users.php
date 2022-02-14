<?php

use yii\db\Migration;

/**
 * Class m210622_084552_users
 */
class m210622_084552_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // user_confirm_lesson
        // user_are_native
        // user_speak_also
        $this->execute("ALTER TABLE {{%users}} ADD user_confirm_lesson SMALLINT DEFAULT 1;");
        $this->execute("ALTER TABLE {{%users}} ADD user_are_native CITEXT DEFAULT NULL;");
        $this->execute("ALTER TABLE {{%users}} ADD user_speak_also CITEXT DEFAULT NULL;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210622_084552_users cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210622_084552_users cannot be reverted.\n";

        return false;
    }
    */
}
