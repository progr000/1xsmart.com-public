<?php

use yii\db\Migration;

/**
 * Class m210625_080124_users
 */
class m210625_080124_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%users}} ADD user_is_confirmed SMALLINT DEFAULT 0;");
        $this->execute("
            CREATE INDEX idx_user_is_confirmed
                ON {{%users}} USING BTREE (user_is_confirmed);
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210625_080124_users cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210625_080124_users cannot be reverted.\n";

        return false;
    }
    */
}
