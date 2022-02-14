<?php

use yii\db\Migration;

/**
 * Class m210727_185116_users
 */
class m210727_185116_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%users}} ADD teacher_profile_completed SMALLINT DEFAULT 0;");
        $this->execute("
            CREATE INDEX idx_teacher_profile_completed
                ON {{%users}} USING BTREE (teacher_profile_completed);
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210727_185116_users cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210727_185116_users cannot be reverted.\n";

        return false;
    }
    */
}
