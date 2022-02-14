<?php

use yii\db\Migration;

/**
 * Class m200723_194333_users
 */
class m200723_194333_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%users}} ADD notes_played INT NOT NULL DEFAULT 0;");
        $this->execute("ALTER TABLE {{%users}} ADD notes_hit INT NOT NULL DEFAULT 0;");
        $this->execute("ALTER TABLE {{%users}} ADD notes_close INT NOT NULL DEFAULT 0;");
        $this->execute("ALTER TABLE {{%users}} ADD notes_lowest SMALLINT DEFAULT NULL;");
        $this->execute("ALTER TABLE {{%users}} ADD notes_highest SMALLINT DEFAULT NULL;");

        $this->execute("ALTER TABLE {{%students_timeline}} ADD notes_played INT NOT NULL DEFAULT 0;");
        $this->execute("ALTER TABLE {{%students_timeline}} ADD notes_hit INT NOT NULL DEFAULT 0;");
        $this->execute("ALTER TABLE {{%students_timeline}} ADD notes_close INT NOT NULL DEFAULT 0;");
        $this->execute("ALTER TABLE {{%students_timeline}} ADD notes_lowest SMALLINT DEFAULT NULL;");
        $this->execute("ALTER TABLE {{%students_timeline}} ADD notes_highest SMALLINT DEFAULT NULL;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200723_194333_users cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200723_194333_users cannot be reverted.\n";

        return false;
    }
    */
}
