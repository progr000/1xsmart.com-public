<?php

use yii\db\Migration;

/**
 * Class m200624_115713_students_timeline
 */
class m200624_115713_students_timeline extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("DELETE FROM {{%students_timeline}};");
        $this->execute("ALTER TABLE {{%students_timeline}} ADD timeline_timestamp BIGINT NOT NULL;");
        $this->execute("CREATE UNIQUE INDEX idx_students_timeline3 ON {{%students_timeline}} USING BTREE (user_id, timeline_timestamp);");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200624_115713_students_timeline cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200624_115713_students_timeline cannot be reverted.\n";

        return false;
    }
    */
}
