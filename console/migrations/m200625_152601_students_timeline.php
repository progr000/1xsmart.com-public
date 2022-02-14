<?php

use yii\db\Migration;

/**
 * Class m200625_152601_students_timeline
 */
class m200625_152601_students_timeline extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("DELETE FROM {{%students_timeline}};");
        $this->execute("ALTER TABLE {{%students_timeline}} ADD room_hash CHARACTER VARYING(32) NOT NULL;");
        $this->execute("CREATE UNIQUE INDEX idx_students_timeline_room_hash ON {{%students_timeline}} USING BTREE (room_hash);");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200625_152601_students_timeline cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200625_152601_students_timeline cannot be reverted.\n";

        return false;
    }
    */
}
