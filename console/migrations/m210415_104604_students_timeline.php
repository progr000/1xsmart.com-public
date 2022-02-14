<?php

use yii\db\Migration;

/**
 * Class m210415_104604_students_timeline
 */
class m210415_104604_students_timeline extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%students_timeline}} ADD lesson_status SMALLINT DEFAULT 0;");
        $this->execute("ALTER TABLE {{%students_timeline}} ADD lesson_notice CITEXT;");
        $this->execute("CREATE INDEX students_timeline_lesson_status ON {{%students_timeline}} USING BTREE (lesson_status)");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210415_104604_students_timeline cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210415_104604_students_timeline cannot be reverted.\n";

        return false;
    }
    */
}
