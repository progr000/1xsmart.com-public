<?php

use yii\db\Migration;

/**
 * Class m211022_094057_auth
 */
class m211022_094057_auth extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        /*
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull(),
            'auth_key' => $this->string()->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->notNull(),
            'email' => $this->string()->notNull(),
            'github' => $this->string(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
        */

        $this->createTable('{{%auth}}', [
            'auth_id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'source' => $this->string()->notNull(),
            'source_id' => $this->string()->notNull(),
        ]);

        $this->addForeignKey('fk-auth-user_id-user-id', '{{%auth}}', 'user_id', '{{%users}}', 'user_id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211022_094057_auth cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211022_094057_auth cannot be reverted.\n";

        return false;
    }
    */
}
