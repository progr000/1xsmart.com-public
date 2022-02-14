<?php

use yii\db\Migration;

/**
 * Class m200623_121716_users
 */
class m200623_121716_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%users}} ALTER user_last_name SET DEFAULT ''::citext;");
        $this->execute("ALTER TABLE {{%users}} ADD user_skype CHARACTER VARYING(50) DEFAULT NULL;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200623_121716_users cannot be reverted.\n";

        //return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200623_121716_users cannot be reverted.\n";

        return false;
    }
    */
}
