<?php

use yii\db\Migration;

/**
 * Class m211208_092305_users
 */
class m211208_092305_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%users}} ADD after_payment_action SMALLINT DEFAULT 0;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211208_092305_users cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211208_092305_users cannot be reverted.\n";

        return false;
    }
    */
}
