<?php

use yii\db\Migration;

/**
 * Class m200506_150844_users
 */
class m200506_150844_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%users}} ADD user_level_general SMALLINT NOT NULL DEFAULT 0;");
        $this->execute("ALTER TABLE {{%users}} ADD user_level_range SMALLINT NOT NULL DEFAULT 0;");
        $this->execute("ALTER TABLE {{%users}} ADD user_level_coordination SMALLINT NOT NULL DEFAULT 0;");
        $this->execute("ALTER TABLE {{%users}} ADD user_level_timbre SMALLINT NOT NULL DEFAULT 0;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200506_150844_users cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200506_150844_users cannot be reverted.\n";

        return false;
    }
    */
}
