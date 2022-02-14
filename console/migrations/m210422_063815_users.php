<?php

use yii\db\Migration;

/**
 * Class m210422_063815_users
 */
class m210422_063815_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%users}} ADD user_local_video CHARACTER VARYING(255) DEFAULT NULL::character varying;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210422_063815_users cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210422_063815_users cannot be reverted.\n";

        return false;
    }
    */
}
