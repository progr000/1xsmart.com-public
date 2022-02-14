<?php

use yii\db\Migration;

/**
 * Class m210714_071006_users
 */
class m210714_071006_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%users}} ADD user_lessons_spent SMALLINT DEFAULT 0;");
        $this->execute("ALTER TABLE {{%users}} ADD user_reviews SMALLINT DEFAULT 0;");
        $this->execute("ALTER TABLE {{%users}} ADD user_rating NUMERIC(5,2) DEFAULT 0.00 NOT NULL;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210714_071006_users cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210714_071006_users cannot be reverted.\n";

        return false;
    }
    */
}
