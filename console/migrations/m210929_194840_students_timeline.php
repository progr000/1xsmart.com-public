<?php

use yii\db\Migration;

/**
 * Class m210929_194840_students_timeline
 */
class m210929_194840_students_timeline extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%students_timeline}} ADD is_introduce_lesson SMALLINT NOT NULL DEFAULT 0;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210929_194840_students_timeline cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210929_194840_students_timeline cannot be reverted.\n";

        return false;
    }
    */
}
