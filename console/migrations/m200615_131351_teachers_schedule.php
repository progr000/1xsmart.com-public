<?php

use yii\db\Migration;

/**
 * Class m200615_131351_teachers_schedule
 */
class m200615_131351_teachers_schedule extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%teachers_schedule}} ADD student_user_id BIGINT DEFAULT NULL;");
        $this->execute("DROP INDEX idx_teachers_schedule;");
        $this->execute("CREATE UNIQUE INDEX idx_teachers_schedule ON {{%teachers_schedule}} USING BTREE (user_id, week_day, work_hour)");
        $this->execute("CREATE INDEX idx_teachers_schedule2 ON {{%teachers_schedule}} USING BTREE (user_id, student_user_id, week_day, work_hour)");
        $this->execute("ALTER TABLE {{%teachers_schedule}}
                    ADD FOREIGN KEY (student_user_id) REFERENCES {{%users}} (user_id) MATCH SIMPLE
                    ON UPDATE CASCADE
                    ON DELETE SET NULL");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200615_131351_teachers_schedule cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200615_131351_teachers_schedule cannot be reverted.\n";

        return false;
    }
    */
}
