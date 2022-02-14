<?php

use yii\db\Migration;

/**
 * Class m200706_062300_alter_user_id_name_field
 */
class m200706_062300_rename_field_user_id_to_target_user_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%methodist_schedule}} RENAME COLUMN user_id TO methodist_user_id");
        $this->execute("ALTER TABLE {{%students_schedule}} RENAME COLUMN user_id TO student_user_id");
        $this->execute("ALTER TABLE {{%teachers_schedule}} RENAME COLUMN user_id TO teacher_user_id");
        $this->execute("ALTER TABLE {{%students_timeline}} RENAME COLUMN user_id TO student_user_id");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200706_062300_rename_field_user_id_to_target_user_id cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200706_062300_alter_user_id_name_field cannot be reverted.\n";

        return false;
    }
    */
}
