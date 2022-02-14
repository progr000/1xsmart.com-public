<?php

use yii\db\Migration;

/**
 * Class m200706_060959_methodist_schedule
 */
class m200706_060959_methodist_schedule extends Migration
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

            CREATE SEQUENCE {$schema}.{$tablePrefix}methodist_schedule_id_seq
                INCREMENT 1
                START 1
                MINVALUE 1
                MAXVALUE 9223372036854775807
                CACHE 1;

            ALTER SEQUENCE {$schema}.{$tablePrefix}methodist_schedule_id_seq
                OWNER TO {$userName};

            CREATE TABLE {$schema}.{$tablePrefix}methodist_schedule
            (
                schedule_id BIGINT PRIMARY KEY NOT NULL DEFAULT nextval('{$tablePrefix}methodist_schedule_id_seq'::regclass),

                user_id BIGINT NOT NULL,
                week_day SMALLINT NOT NULL,
                work_hour SMALLINT NOT NULL,
                student_user_id BIGINT,

                FOREIGN KEY (user_id) REFERENCES {$schema}.{$tablePrefix}users (user_id) MATCH SIMPLE
                    ON UPDATE CASCADE
                    ON DELETE CASCADE,
                FOREIGN KEY (student_user_id) REFERENCES {$schema}.{$tablePrefix}users (user_id) MATCH SIMPLE
                    ON UPDATE CASCADE
                    ON DELETE SET NULL
            ) WITH (
                OIDS = FALSE
            )
            TABLESPACE pg_default;

            ALTER TABLE {$schema}.{$tablePrefix}methodist_schedule
                OWNER to {$userName};

            CREATE UNIQUE INDEX idx_methodist_schedule
                ON {$schema}.{$tablePrefix}methodist_schedule USING BTREE (user_id, week_day, work_hour);
            CREATE INDEX idx_methodist_schedule2
                ON {$schema}.{$tablePrefix}methodist_schedule USING BTREE (user_id, student_user_id, week_day, work_hour);
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200706_060959_methodist_schedule cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200706_060959_methodist_schedule cannot be reverted.\n";

        return false;
    }
    */
}
