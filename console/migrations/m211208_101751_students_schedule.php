<?php

use yii\db\Migration;

/**
 * Class m211208_101751_students_schedule
 */
class m211208_101751_students_schedule extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%students_schedule}} ADD teacher_user_id BIGINT;");
        $this->execute("ALTER TABLE {{%students_schedule}} ADD
                        FOREIGN KEY (teacher_user_id) REFERENCES {{%users}} (user_id)
                        MATCH SIMPLE ON UPDATE CASCADE ON DELETE CASCADE;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211208_101751_students_schedule cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211208_101751_students_schedule cannot be reverted.\n";

        return false;
    }
    */
}
