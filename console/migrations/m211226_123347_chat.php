<?php

use yii\db\Migration;

/**
 * Class m211226_123347_chat
 */
class m211226_123347_chat extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%chat}} ADD is_system_empty_message SMALLINT DEFAULT 0;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211226_123347_chat cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211226_123347_chat cannot be reverted.\n";

        return false;
    }
    */
}
