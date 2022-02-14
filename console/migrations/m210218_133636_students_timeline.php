<?php

use yii\db\Migration;

/**
 * Class m210218_133636_students_timeline
 */
class m210218_133636_students_timeline extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%students_timeline}} ADD replacing_for_timeline_timestamp BIGINT NOT NULL;");
        $this->execute("CREATE UNIQUE INDEX idx_students_replacing ON sm_students_timeline USING BTREE (student_user_id, replacing_for_timeline_timestamp);");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210218_133636_students_timeline cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210218_133636_students_timeline cannot be reverted.\n";

        return false;
    }
    */
}
