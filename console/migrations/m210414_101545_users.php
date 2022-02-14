<?php

use yii\db\Migration;

/**
 * Class m210414_101545_users
 */
class m210414_101545_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%users}} ALTER COLUMN notes_lowest TYPE CHARACTER VARYING(3);");
        $this->execute("ALTER TABLE {{%users}} ALTER COLUMN notes_highest TYPE CHARACTER VARYING(3);");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210414_101545_users cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210414_101545_users cannot be reverted.\n";

        return false;
    }
    */
}
