<?php

use yii\db\Migration;

/**
 * Class m210126_123023_users
 */
class m210126_123023_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%users}} ADD admin_user_id BIGINT;");
        $this->execute("ALTER TABLE {{%users}} ADD admin_notice CITEXT;");
        $this->execute("ALTER TABLE {{%users}} ADD FOREIGN KEY (admin_user_id) REFERENCES public.sm_users (user_id) MATCH SIMPLE ON UPDATE CASCADE ON DELETE SET NULL");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210126_123023_users cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210126_123023_users cannot be reverted.\n";

        return false;
    }
    */
}
