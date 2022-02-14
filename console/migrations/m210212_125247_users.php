<?php

use yii\db\Migration;

/**
 * Class m210212_125247_users
 */
class m210212_125247_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%users}} ADD user_last_lesson TIMESTAMP WITHOUT TIME ZONE DEFAULT NULL");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210212_125247_users cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210212_125247_users cannot be reverted.\n";

        return false;
    }
    */
}
