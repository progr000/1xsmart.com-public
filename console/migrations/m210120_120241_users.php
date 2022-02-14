<?php

use yii\db\Migration;

/**
 * Class m210120_120241_users
 */
class m210120_120241_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%users}} ADD additional_service_info CITEXT;");
        $this->execute("ALTER TABLE {{%users}} ADD additional_service_notice CITEXT;");
        $this->execute("ALTER TABLE {{%users}} DROP user_skype;");
        $this->execute("ALTER TABLE {{%users}} ADD user_custom_messengers CITEXT;");
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210120_120241_users cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210120_120241_users cannot be reverted.\n";

        return false;
    }
    */
}
