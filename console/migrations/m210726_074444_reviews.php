<?php

use yii\db\Migration;

/**
 * Class m210726_074444_reviews
 */
class m210726_074444_reviews extends Migration
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

            CREATE SEQUENCE {$schema}.{$tablePrefix}reviews_review_id_seq
                INCREMENT 1
                START 1
                MINVALUE 1
                MAXVALUE 9223372036854775807
                CACHE 1;

            ALTER SEQUENCE {$schema}.{$tablePrefix}reviews_review_id_seq
                OWNER TO {$userName};

            CREATE TABLE {$schema}.{$tablePrefix}reviews
            (
                review_id BIGINT PRIMARY KEY NOT NULL DEFAULT nextval('{$tablePrefix}reviews_review_id_seq'::regclass),
                review_created TIMESTAMP WITHOUT TIME ZONE NOT NULL,
                review_updated TIMESTAMP WITHOUT TIME ZONE NOT NULL,
                teacher_user_id BIGINT NOT NULL,
                student_user_id BIGINT NOT NULL,
                review_text PUBLIC.CITEXT NOT NULL,
                review_rating numeric(5,2) NOT NULL DEFAULT 0.00,
                
                CONSTRAINT sm_review_teacher_user_id FOREIGN KEY (teacher_user_id)
                    REFERENCES {$schema}.{$tablePrefix}users (user_id) MATCH SIMPLE
                    ON UPDATE CASCADE
                    ON DELETE CASCADE,
                    
                CONSTRAINT sm_review_student_user_id FOREIGN KEY (student_user_id)
                    REFERENCES {$schema}.{$tablePrefix}users (user_id) MATCH SIMPLE
                    ON UPDATE CASCADE
                    ON DELETE CASCADE
            ) WITH (
                OIDS = FALSE
            )
            TABLESPACE pg_default;

            ALTER TABLE {$schema}.{$tablePrefix}reviews
                OWNER to {$userName};
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210726_074444_reviews cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210726_074444_reviews cannot be reverted.\n";

        return false;
    }
    */
}
