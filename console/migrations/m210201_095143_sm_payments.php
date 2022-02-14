<?php

use yii\db\Migration;

/**
 * Class m210201_095143_sm_payments
 */
class m210201_095143_sm_payments extends Migration
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

            CREATE SEQUENCE {$schema}.{$tablePrefix}payments_order_id_seq
                INCREMENT 1
                START 547835
                MINVALUE 547835
                MAXVALUE 9223372036854775807
                CACHE 1;

            ALTER SEQUENCE {$schema}.{$tablePrefix}payments_order_id_seq
                OWNER TO {$userName};

            CREATE TABLE {$schema}.{$tablePrefix}payments
            (
                order_id BIGINT PRIMARY KEY NOT NULL DEFAULT nextval('{$tablePrefix}payments_order_id_seq'::regclass),
                order_created TIMESTAMP WITHOUT TIME ZONE NOT NULL,
                order_updated TIMESTAMP WITHOUT TIME ZONE NOT NULL,

                order_amount NUMERIC(11,2) NOT NULL DEFAULT 0.00,
                order_count SMALLINT NOT NULL DEFAULT 0,
                order_description PUBLIC.CITEXT DEFAULT NULL,
                order_status CHARACTER VARYING(30) DEFAULT NULL::character varying,
                order_type CHARACTER VARYING(30) DEFAULT NULL::character varying,
                order_additional_fields PUBLIC.CITEXT DEFAULT NULL,

                student_user_id BIGINT,

                FOREIGN KEY (student_user_id) REFERENCES {$schema}.{$tablePrefix}users (user_id) MATCH SIMPLE
                    ON UPDATE CASCADE
                    ON DELETE SET NULL

            ) WITH (
                OIDS = FALSE
            )
            TABLESPACE pg_default;

            ALTER TABLE {$schema}.{$tablePrefix}payments
                OWNER to {$userName};

            CREATE INDEX idx_order_type
                ON {$schema}.{$tablePrefix}payments USING BTREE (order_type);
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210201_095143_sm_payments cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210201_095143_sm_payments cannot be reverted.\n";

        return false;
    }
    */
}
