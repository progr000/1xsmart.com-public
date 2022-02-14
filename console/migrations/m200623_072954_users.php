<?php

use yii\db\Migration;

/**
 * Class m200623_072954_users
 */
class m200623_072954_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%users}} ADD user_photo CHARACTER VARYING(255) DEFAULT NULL;");
        $this->execute("ALTER TABLE {{%users}} ADD user_gender SMALLINT;");
        $this->execute("ALTER TABLE {{%users}} ADD user_timezone INTEGER NOT NULL DEFAULT 0;");
        $this->execute("ALTER TABLE {{%users}} ADD user_birthday TIMESTAMP WITHOUT TIME ZONE;");
        $this->execute("ALTER TABLE {{%users}} ADD user_learning_objectives CITEXT;");
        $this->execute("ALTER TABLE {{%users}} ADD user_music_experience CITEXT;");
        $this->execute("ALTER TABLE {{%users}} ADD user_music_genres CITEXT;");
        $this->execute("ALTER TABLE {{%users}} ADD user_additional_info CITEXT;");

        $this->execute("CREATE INDEX idx_user_gender ON {{%users}} USING BTREE (user_gender);");
        $this->execute("CREATE INDEX idx_user_birthday ON {{%users}} USING BTREE (user_birthday);");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200623_072954_users cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200623_072954_users cannot be reverted.\n";

        return false;
    }
    */
}
