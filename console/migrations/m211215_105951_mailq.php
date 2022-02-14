<?php

use yii\db\Migration;

/**
 * Class m211215_105951_mailq
 */
class m211215_105951_mailq extends Migration
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

            CREATE SEQUENCE {$schema}.{$tablePrefix}mailq_mail_id_seq
                INCREMENT 1
                START 1
                MINVALUE 1
                MAXVALUE 9223372036854775807
                CACHE 1;

            ALTER SEQUENCE {$schema}.{$tablePrefix}mailq_mail_id_seq
                OWNER TO {$userName};

            CREATE TABLE {$schema}.{$tablePrefix}mailq
            (
                mail_id bigint PRIMARY KEY NOT NULL DEFAULT nextval('{$tablePrefix}mailq_mail_id_seq'::regclass),
                mail_created timestamp without time zone NOT NULL,
                mail_from citext COLLATE pg_catalog.\"default\" NOT NULL,
                mail_to citext COLLATE pg_catalog.\"default\" NOT NULL,
                mail_reply_to citext COLLATE pg_catalog.\"default\",
                mail_subject citext COLLATE pg_catalog.\"default\" NOT NULL,
                mail_body_html citext COLLATE pg_catalog.\"default\" NOT NULL,
                mail_body_text citext COLLATE pg_catalog.\"default\" NOT NULL,
                mailer_letter_id character varying(32) COLLATE pg_catalog.\"default\",
                mailer_answer citext COLLATE pg_catalog.\"default\" NOT NULL,
                mailer_letter_status character varying(32) COLLATE pg_catalog.\"default\" NOT NULL,
                mailer_description citext COLLATE pg_catalog.\"default\",
                remote_ip bigint NOT NULL DEFAULT '0'::bigint,
                user_id bigint,
                CONSTRAINT fk_mailq_user_id FOREIGN KEY (user_id)
                    REFERENCES {$schema}.{$tablePrefix}users (user_id) MATCH SIMPLE
                    ON UPDATE CASCADE
                    ON DELETE SET NULL
            )
            WITH (
                OIDS = FALSE
            )
            TABLESPACE pg_default;

            ALTER TABLE {$schema}.{$tablePrefix}mailq
                OWNER to {$userName};
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211215_105951_mailq cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211215_105951_mailq cannot be reverted.\n";

        return false;
    }
    */
}
