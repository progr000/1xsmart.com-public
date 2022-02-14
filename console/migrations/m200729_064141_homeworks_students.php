<?php

use yii\db\Migration;

/**
 * Class m200729_064141_homeworks_students
 */
class m200729_064141_homeworks_students extends Migration
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

            CREATE TABLE {$schema}.{$tablePrefix}homeworks_students
            (
                work_id BIGINT NOT NULL,
                student_user_id BIGINT NOT NULL,
                hws_appointed TIMESTAMP WITHOUT TIME ZONE NOT NULL,
                hws_passed TIMESTAMP WITHOUT TIME ZONE,
                hws_status SMALLINT NOT NULL DEFAULT 0,
                hws_hash CHARACTER VARYING(32) NOT NULL,

                notes_played INTEGER NOT NULL DEFAULT 0,
                notes_hit INTEGER NOT NULL DEFAULT 0,
                notes_close INTEGER NOT NULL DEFAULT 0,
                notes_lowest SMALLINT,
                notes_highest SMALLINT,

                FOREIGN KEY (work_id) REFERENCES {$schema}.{$tablePrefix}homeworks (work_id) MATCH SIMPLE
                    ON UPDATE CASCADE
                    ON DELETE CASCADE,

                FOREIGN KEY (student_user_id) REFERENCES {$schema}.{$tablePrefix}users (user_id) MATCH SIMPLE
                    ON UPDATE CASCADE
                    ON DELETE CASCADE

            ) WITH (
                OIDS = FALSE
            )
            TABLESPACE pg_default;

            ALTER TABLE {$schema}.{$tablePrefix}homeworks_students
                OWNER to {$userName};

            CREATE UNIQUE INDEX idx_homeworks_students
                ON {$schema}.{$tablePrefix}homeworks_students USING BTREE (work_id, student_user_id);
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200729_064141_homeworks_students cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200729_064141_homeworks_students cannot be reverted.\n";

        return false;
    }
    */
}
