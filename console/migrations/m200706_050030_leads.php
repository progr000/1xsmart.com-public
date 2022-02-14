<?php

use yii\db\Migration;

/**
 * Class m200706_050030_leads
 */
class m200706_050030_leads extends Migration
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

            CREATE SEQUENCE {$schema}.{$tablePrefix}leads_lead_id_seq
                INCREMENT 1
                START 1
                MINVALUE 1
                MAXVALUE 9223372036854775807
                CACHE 1;

            ALTER SEQUENCE {$schema}.{$tablePrefix}leads_lead_id_seq
                OWNER TO {$userName};

            CREATE TABLE {$schema}.{$tablePrefix}leads
            (
                lead_id BIGINT PRIMARY KEY NOT NULL DEFAULT nextval('{$tablePrefix}leads_lead_id_seq'::regclass),
                lead_created TIMESTAMP WITHOUT TIME ZONE NOT NULL,
                lead_updated TIMESTAMP WITHOUT TIME ZONE NOT NULL,

                lead_name PUBLIC.CITEXT NOT NULL,
                lead_email PUBLIC.CITEXT NOT NULL,
                lead_phone CHARACTER VARYING(50) NOT NULL,
                lead_photo CHARACTER VARYING(255) DEFAULT NULL,
                lead_info PUBLIC.CITEXT DEFAULT NULL,

                operator_user_id BIGINT,
                operator_notice PUBLIC.CITEXT,

                FOREIGN KEY (operator_user_id) REFERENCES {$schema}.{$tablePrefix}users (user_id) MATCH SIMPLE
                    ON UPDATE CASCADE
                    ON DELETE SET NULL
            ) WITH (
                OIDS = FALSE
            )
            TABLESPACE pg_default;

            CREATE INDEX idx_leads_lead_email
                ON {$schema}.{$tablePrefix}leads USING BTREE (lead_email);

            CREATE INDEX idx_leads_lead_phone
                ON {$schema}.{$tablePrefix}leads USING BTREE (lead_phone);

            ALTER TABLE {$schema}.{$tablePrefix}leads
                OWNER to {$userName};
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200706_050030_leads cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200706_050030_leads cannot be reverted.\n";

        return false;
    }
    */
}
