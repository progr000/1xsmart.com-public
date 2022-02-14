<?php

use yii\db\Migration;

/**
 * Class m200728_182053_homeworks
 */
class m200728_182053_homeworks extends Migration
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

            CREATE SEQUENCE {$schema}.{$tablePrefix}homeworks_work_id_seq
                INCREMENT 1
                START 1
                MINVALUE 1
                MAXVALUE 9223372036854775807
                CACHE 1;

            ALTER SEQUENCE {$schema}.{$tablePrefix}homeworks_work_id_seq
                OWNER TO {$userName};

            CREATE TABLE {$schema}.{$tablePrefix}homeworks
            (
                work_id BIGINT PRIMARY KEY NOT NULL DEFAULT nextval('{$tablePrefix}homeworks_work_id_seq'::regclass),
                work_created TIMESTAMP WITHOUT TIME ZONE NOT NULL,
                work_updated TIMESTAMP WITHOUT TIME ZONE NOT NULL,

                work_name PUBLIC.CITEXT NOT NULL,
                work_file CHARACTER VARYING(255) NOT NULL,
                work_status SMALLINT NOT NULL DEFAULT 0,
                work_description PUBLIC.CITEXT DEFAULT NULL,

                operator_user_id BIGINT,
                methodist_user_id BIGINT NOT NULL,

                FOREIGN KEY (operator_user_id) REFERENCES {$schema}.{$tablePrefix}users (user_id) MATCH SIMPLE
                    ON UPDATE CASCADE
                    ON DELETE SET NULL,

                FOREIGN KEY (methodist_user_id) REFERENCES {$schema}.{$tablePrefix}users (user_id) MATCH SIMPLE
                    ON UPDATE CASCADE
                    ON DELETE CASCADE

            ) WITH (
                OIDS = FALSE
            )
            TABLESPACE pg_default;

            ALTER TABLE {$schema}.{$tablePrefix}homeworks
                OWNER to {$userName};
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200728_182053_homeworks cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200728_182053_homeworks cannot be reverted.\n";

        return false;
    }
    */
}
