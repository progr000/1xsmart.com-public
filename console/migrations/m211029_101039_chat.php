<?php

use yii\db\Migration;

/**
 * Class m211029_101039_chat
 */
class m211029_101039_chat extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $schema   = isset(Yii::$app->components['db']['schemaMap']['pgsql']['defaultSchema'])
            ? Yii::$app->components['db']['schemaMap']['pgsql']['defaultSchema']
            : 'public';

        $tablePrefix = isset(Yii::$app->components['db']['tablePrefix'])
            ? Yii::$app->components['db']['tablePrefix']
            : '';

        $userName = isset(Yii::$app->components['db']['username'])
            ? Yii::$app->components['db']['username']
            : 'username';

        $this->db->pdo->exec("
            SET search_path TO {$schema}, public;

            CREATE SEQUENCE {$schema}.{$tablePrefix}chat_msg_id_seq
                INCREMENT 1
                START 1
                MINVALUE 1
                MAXVALUE 9223372036854775807
                CACHE 1;

            ALTER SEQUENCE {$schema}.{$tablePrefix}chat_msg_id_seq
                OWNER TO {$userName};

            CREATE TABLE {$schema}.{$tablePrefix}chat
            (
                msg_id BIGINT PRIMARY KEY NOT NULL DEFAULT nextval('{$tablePrefix}chat_msg_id_seq'::regclass),
                msg_created TIMESTAMP WITHOUT TIME ZONE NOT NULL,
                msg_text TEXT, -- Message
                msg_unread SMALLINT NOT NULL DEFAULT 0,
                sender_user_id BIGINT NOT NULL DEFAULT '0'::bigint,
                receiver_user_id BIGINT NOT NULL DEFAULT '0'::bigint,

                CONSTRAINT chat_sender_user_id FOREIGN KEY (sender_user_id)
                    REFERENCES {$schema}.{$tablePrefix}users (user_id) MATCH SIMPLE
                    ON UPDATE CASCADE
                    ON DELETE CASCADE,

                CONSTRAINT chat_receiver_user_id FOREIGN KEY (receiver_user_id)
                    REFERENCES {$schema}.{$tablePrefix}users (user_id) MATCH SIMPLE
                    ON UPDATE CASCADE
                    ON DELETE CASCADE
            ) WITH (
                OIDS = FALSE
            )
            TABLESPACE pg_default;

            ALTER TABLE {$schema}.{$tablePrefix}chat
                OWNER to {$userName};
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute('DROP TABLE IF EXISTS {{%chat}};');
        $this->execute('DROP SEQUENCE IF EXISTS {{%chat_msg_id_seq}};');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211029_101039_chat cannot be reverted.\n";

        return false;
    }
    */
}
