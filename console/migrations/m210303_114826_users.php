<?php

use yii\db\Migration;

/**
 * Class m210303_114826_users
 */
class m210303_114826_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%users}} ADD user_youtube_video CHARACTER VARYING(255) DEFAULT NULL::character varying;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210303_114826_users cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210303_114826_users cannot be reverted.\n";

        return false;
    }
    */
}
