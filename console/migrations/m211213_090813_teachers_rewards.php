<?php

use yii\db\Migration;

/**
 * Class m211213_090813_teachers_rewards
 */
class m211213_090813_teachers_rewards extends Migration
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

            CREATE SEQUENCE {$schema}.{$tablePrefix}teachers_rewards_id_seq
                INCREMENT 1
                START 1
                MINVALUE 1
                MAXVALUE 9223372036854775807
                CACHE 1;

            ALTER SEQUENCE {$schema}.{$tablePrefix}teachers_rewards_id_seq
                OWNER TO {$userName};

            CREATE TABLE {$schema}.{$tablePrefix}teachers_rewards
            (
                rw_id BIGINT PRIMARY KEY NOT NULL DEFAULT nextval('{$tablePrefix}teachers_rewards_id_seq'::regclass),
                rw_created TIMESTAMP WITHOUT TIME ZONE NOT NULL,
                rw_status SMALLINT DEFAULT 0,
                rw_amount_usd NUMERIC(11,2) NOT NULL DEFAULT 0.00,
                rw_description TEXT,
                teacher_user_id BIGINT NOT NULL,

                CONSTRAINT rw_teacher_user_id FOREIGN KEY (teacher_user_id)
                    REFERENCES {$schema}.{$tablePrefix}users (user_id) MATCH SIMPLE
                    ON UPDATE CASCADE
                    ON DELETE CASCADE

            ) WITH (
                OIDS = FALSE
            )
            TABLESPACE pg_default;

            ALTER TABLE {$schema}.{$tablePrefix}teachers_rewards
                OWNER to {$userName};
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211213_090813_teachers_rewards cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211213_090813_teachers_rewards cannot be reverted.\n";

        return false;
    }
    */
}
