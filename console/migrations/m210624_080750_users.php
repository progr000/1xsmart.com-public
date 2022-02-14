<?php

use yii\db\Migration;

/**
 * Class m210624_080750_users
 */
class m210624_080750_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%users}} ADD user_goals_of_education CITEXT DEFAULT NULL;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210624_080750_users cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210624_080750_users cannot be reverted.\n";

        return false;
    }
    */
}
