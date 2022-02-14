<?php

use yii\db\Migration;

/**
 * Class m200611_104322_rename_field_hour_to_work_hour
 */
class m200611_104322_rename_field_hour_to_work_hour extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%students_schedule}} RENAME COLUMN hour TO work_hour");
        $this->execute("ALTER TABLE {{%teachers_schedule}} RENAME COLUMN hour TO work_hour");
        $this->execute("ALTER TABLE {{%students_timeline}} RENAME COLUMN hour TO work_hour");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200611_104322_rename_field_hour_to_work_hour cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200611_104322_rename_field_hour_to_work_hour cannot be reverted.\n";

        return false;
    }
    */
}
