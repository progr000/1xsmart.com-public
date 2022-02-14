<?php

use yii\db\Migration;

/**
 * Class m210414_102344_homeworks_students
 */
class m210414_102344_homeworks_students extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%homeworks_students}} ALTER COLUMN notes_lowest TYPE CHARACTER VARYING(3);");
        $this->execute("ALTER TABLE {{%homeworks_students}} ALTER COLUMN notes_highest TYPE CHARACTER VARYING(3);");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210414_102344_homeworks_students cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210414_102344_homeworks_students cannot be reverted.\n";

        return false;
    }
    */
}
