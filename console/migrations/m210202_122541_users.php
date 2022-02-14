<?php

use yii\db\Migration;

/**
 * Class m210202_122541_users
 */
class m210202_122541_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%users}} ADD user_lessons_available INT DEFAULT 0;");
        $this->execute("ALTER TABLE {{%users}} ADD user_lessons_completed INT DEFAULT 0;");
        $this->execute("ALTER TABLE {{%users}} ADD user_lessons_missed INT DEFAULT 0;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210202_122541_users cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210202_122541_users cannot be reverted.\n";

        return false;
    }
    */
}
