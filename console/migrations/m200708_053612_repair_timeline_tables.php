<?php

use yii\db\Migration;

/**
 * Class m200708_053612_repair_timeline_tables
 */
class m200708_053612_repair_timeline_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%methodist_timeline}} ALTER schedule_id DROP NOT NULL;");
        $this->execute("ALTER TABLE {{%students_timeline}} ALTER schedule_id DROP NOT NULL;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200708_053612_repair_timeline_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200708_053612_repair_timeline_tables cannot be reverted.\n";

        return false;
    }
    */
}
