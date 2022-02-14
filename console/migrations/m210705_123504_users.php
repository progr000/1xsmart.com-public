<?php

use yii\db\Migration;

/**
 * Class m210705_123504_users
 */
class m210705_123504_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%users}} ADD user_can_teach_children SMALLINT DEFAULT 1;");
        $this->execute("
            CREATE INDEX idx_user_can_teach_children
                ON {{%users}} USING BTREE (user_can_teach_children);
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210705_123504_users cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210705_123504_users cannot be reverted.\n";

        return false;
    }
    */
}
