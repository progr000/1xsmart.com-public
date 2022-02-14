<?php

use yii\db\Migration;

/**
 * Class m200611_103621_rename_field_day_to_week_day
 */
class m200611_103621_rename_field_day_to_week_day extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%students_schedule}} RENAME COLUMN day TO week_day");
        $this->execute("ALTER TABLE {{%teachers_schedule}} RENAME COLUMN day TO week_day");
        $this->execute("ALTER TABLE {{%students_timeline}} RENAME COLUMN day TO week_day");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200611_103621_rename_field_day_to_week_day cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200611_103621_rename_field_day_to_week_day cannot be reverted.\n";

        return false;
    }
    */
}
