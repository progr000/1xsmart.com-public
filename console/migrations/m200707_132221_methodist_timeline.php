<?php

use yii\db\Migration;

/**
 * Class m200707_132221_methodist_timeline
 */
class m200707_132221_methodist_timeline extends Migration
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

            CREATE SEQUENCE {$schema}.{$tablePrefix}methodist_timeline_id_seq
                INCREMENT 1
                START 1
                MINVALUE 1
                MAXVALUE 9223372036854775807
                CACHE 1;

            ALTER SEQUENCE {$schema}.{$tablePrefix}methodist_timeline_id_seq
                OWNER TO {$userName};

            CREATE TABLE {$schema}.{$tablePrefix}methodist_timeline
            (
                timeline_id BIGINT PRIMARY KEY NOT NULL DEFAULT nextval('{$tablePrefix}methodist_timeline_id_seq'::regclass),
                schedule_id BIGINT NOT NULL,

                methodist_user_id BIGINT NOT NULL,
                week_day SMALLINT NOT NULL,
                work_hour SMALLINT NOT NULL,
                timeline TIMESTAMP WITHOUT TIME ZONE NOT NULL,
                timeline_timestamp BIGINT NOT NULL,

                student_user_id BIGINT DEFAULT NULL,
                room_hash CHARACTER VARYING(32) DEFAULT NULL,

                FOREIGN KEY (schedule_id) REFERENCES {$schema}.{$tablePrefix}methodist_schedule (schedule_id) MATCH SIMPLE
                    ON UPDATE CASCADE
                    ON DELETE CASCADE,

                FOREIGN KEY (methodist_user_id) REFERENCES {$schema}.{$tablePrefix}users (user_id) MATCH SIMPLE
                    ON UPDATE CASCADE
                    ON DELETE CASCADE,

                FOREIGN KEY (student_user_id) REFERENCES {$schema}.{$tablePrefix}users (user_id) MATCH SIMPLE
                    ON UPDATE CASCADE
                    ON DELETE CASCADE

            ) WITH (
                OIDS = FALSE
            )
            TABLESPACE pg_default;

            ALTER TABLE {$schema}.{$tablePrefix}students_schedule
                OWNER to {$userName};


            CREATE INDEX idx_methodist_timeline2
                ON {$schema}.{$tablePrefix}methodist_timeline USING BTREE (methodist_user_id, student_user_id, week_day, work_hour);

            CREATE UNIQUE INDEX idx_methodist_timeline
                ON {$schema}.{$tablePrefix}methodist_timeline USING BTREE (methodist_user_id, timeline);

            CREATE UNIQUE INDEX idx_methodist_timeline3
                ON {$schema}.{$tablePrefix}methodist_timeline USING BTREE (methodist_user_id, timeline_timestamp);

            CREATE UNIQUE INDEX idx_methodist_timeline_room_hash
                ON {$schema}.{$tablePrefix}methodist_timeline USING BTREE (room_hash);
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200707_132221_methodist_timeline cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200707_132221_methodist_timeline cannot be reverted.\n";

        return false;
    }
    */
}
