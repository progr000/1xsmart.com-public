<?php

use yii\db\Migration;

/**
 * Class m200506_160844_users
 */
class m200506_160844_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%users}} ALTER user_token DROP NOT NULL");
        $this->execute("ALTER TABLE {{%users}} ADD user_need_set_password SMALLINT NOT NULL DEFAULT 1;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200506_160844_users cannot be reverted.\n";

        //return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200506_160844_users cannot be reverted.\n";

        return false;
    }
    */
}
