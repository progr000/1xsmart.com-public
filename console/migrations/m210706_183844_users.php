<?php

use yii\db\Migration;

/**
 * Class m210706_183844_users
 */
class m210706_183844_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%users}} ADD user_price_peer_hour NUMERIC(11,2) DEFAULT 0.00 NOT NULL;");
        $this->execute("
            CREATE INDEX idx_user_price_peer_hour
                ON {{%users}} USING BTREE (user_price_peer_hour);
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210706_183844_users cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210706_183844_users cannot be reverted.\n";

        return false;
    }
    */
}
