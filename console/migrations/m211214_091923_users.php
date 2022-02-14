<?php

use yii\db\Migration;

/**
 * Class m211214_091923_users
 */
class m211214_091923_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%users}} ADD pay_to_wallet CHARACTER VARYING(20) DEFAULT 'paypal';");
        $this->execute("ALTER TABLE {{%users}} ADD wallet_paypal CHARACTER VARYING(255) DEFAULT NULL;");
        $this->execute("ALTER TABLE {{%users}} ADD wallet_yandex CHARACTER VARYING(255) DEFAULT NULL;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211214_091923_users cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211214_091923_users cannot be reverted.\n";

        return false;
    }
    */
}
