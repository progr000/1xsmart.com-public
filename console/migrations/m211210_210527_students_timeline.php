<?php

use yii\db\Migration;

/**
 * Class m211210_210527_students_timeline
 */
class m211210_210527_students_timeline extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%students_timeline}} ADD lesson_amount_usd NUMERIC(11,2) NOT NULL DEFAULT 0.00;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211210_210527_students_timeline cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211210_210527_students_timeline cannot be reverted.\n";

        return false;
    }
    */
}
