<?php

use yii\db\Migration;

/**
 * Class m201223_073806_users
 */
class m201223_073806_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%users}} ADD user_level_general_notice CITEXT default NULL;");
        $this->execute("ALTER TABLE {{%users}} ADD user_level_range_notice CITEXT default NULL;");
        $this->execute("ALTER TABLE {{%users}} ADD user_level_coordination_notice CITEXT default NULL;");
        $this->execute("ALTER TABLE {{%users}} ADD user_level_timbre_notice CITEXT default NULL;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201223_073806_users cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201223_073806_users cannot be reverted.\n";

        return false;
    }
    */
}
