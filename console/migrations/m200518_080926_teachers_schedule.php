<?php

use yii\db\Migration;

/**
 * Class m200518_080926_lessons
 */
class m200518_080926_teachers_schedule extends Migration
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

            CREATE SEQUENCE {$schema}.{$tablePrefix}teachers_schedule_id_seq
                INCREMENT 1
                START 1
                MINVALUE 1
                MAXVALUE 9223372036854775807
                CACHE 1;

            ALTER SEQUENCE {$schema}.{$tablePrefix}teachers_schedule_id_seq
                OWNER TO {$userName};

            CREATE TABLE {$schema}.{$tablePrefix}teachers_schedule
            (
                schedule_id BIGINT PRIMARY KEY NOT NULL DEFAULT nextval('{$tablePrefix}teachers_schedule_id_seq'::regclass),

                user_id BIGINT NOT NULL,
                day SMALLINT NOT NULL,
                hour SMALLINT NOT NULL,

                FOREIGN KEY (user_id) REFERENCES {$schema}.{$tablePrefix}users (user_id) MATCH SIMPLE
                    ON UPDATE CASCADE
                    ON DELETE CASCADE
            ) WITH (
                OIDS = FALSE
            )
            TABLESPACE pg_default;

            ALTER TABLE {$schema}.{$tablePrefix}teachers_schedule
                OWNER to {$userName};

            CREATE UNIQUE INDEX idx_teachers_schedule
                ON {$schema}.{$tablePrefix}teachers_schedule USING BTREE (user_id, day, hour);
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200518_080926_teachers_schedule cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200518_080926_teachers_schedule cannot be reverted.\n";

        return false;
    }
    */
}
