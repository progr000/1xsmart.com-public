<?php

use yii\db\Migration;

/**
 * Class m210211_092051_methodist_timeline
 */
class m210211_092051_methodist_timeline extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%methodist_timeline}} ADD lesson_status SMALLINT DEFAULT 0;");
        $this->execute("ALTER TABLE {{%methodist_timeline}} ADD lesson_notice CITEXT;");
        $this->execute("CREATE INDEX methodist_timeline_lesson_status ON {{%methodist_timeline}} USING BTREE (lesson_status)");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210211_092051_methodist_timeline cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210211_092051_methodist_timeline cannot be reverted.\n";

        return false;
    }
    */
}
