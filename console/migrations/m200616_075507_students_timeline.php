<?php

use yii\db\Migration;

/**
 * Class m200616_075507_students_timeline
 */
class m200616_075507_students_timeline extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%students_timeline}} ADD teacher_user_id BIGINT NOT NULL;");
        $this->execute("ALTER TABLE {{%students_timeline}}
                    ADD FOREIGN KEY (teacher_user_id) REFERENCES {{%users}} (user_id) MATCH SIMPLE
                    ON UPDATE CASCADE
                    ON DELETE CASCADE");
        $this->execute("CREATE INDEX idx_students_timeline2 ON sm_students_timeline USING BTREE (user_id, teacher_user_id, week_day, work_hour);");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200616_075507_students_timeline cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200616_075507_students_timeline cannot be reverted.\n";

        return false;
    }
    */
}
