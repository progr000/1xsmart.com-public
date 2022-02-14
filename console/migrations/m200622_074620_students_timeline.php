<?php

use yii\db\Migration;

/**
 * Class m200622_074620_students_timeline
 */
class m200622_074620_students_timeline extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("DROP INDEX idx_students_timeline;");
        $this->execute("CREATE UNIQUE INDEX idx_students_timeline ON sm_students_timeline USING BTREE (user_id, timeline);");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200622_074620_students_timeline cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200622_074620_students_timeline cannot be reverted.\n";

        return false;
    }
    */
}
