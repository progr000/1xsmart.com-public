<?php

use yii\db\Migration;

/**
 * Class m200611_132745_students_timeline
 */
class m200611_132745_students_timeline extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%students_timeline}} ADD is_replacing SMALLINT NOT NULL DEFAULT 0;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200611_132745_students_timeline cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200611_132745_students_timeline cannot be reverted.\n";

        return false;
    }
    */
}
