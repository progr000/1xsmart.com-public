<?php

use yii\db\Migration;

/**
 * Class m201022_081934_presets
 */
class m201022_081934_presets extends Migration
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

            CREATE SEQUENCE {$schema}.{$tablePrefix}presets_preset_id_seq
                INCREMENT 1
                START 1
                MINVALUE 1
                MAXVALUE 9223372036854775807
                CACHE 1;

            ALTER SEQUENCE {$schema}.{$tablePrefix}presets_preset_id_seq
                OWNER TO {$userName};

            CREATE TABLE {$schema}.{$tablePrefix}presets
            (
                preset_id BIGINT PRIMARY KEY NOT NULL DEFAULT nextval('{$tablePrefix}presets_preset_id_seq'::regclass),
                preset_created TIMESTAMP WITHOUT TIME ZONE NOT NULL,
                preset_updated TIMESTAMP WITHOUT TIME ZONE NOT NULL,

                preset_name PUBLIC.CITEXT NOT NULL,
                preset_description PUBLIC.CITEXT DEFAULT NULL,
                preset_file CHARACTER VARYING(255) NOT NULL,
                preset_image CHARACTER VARYING(255) NOT NULL,
                preset_status SMALLINT NOT NULL DEFAULT 0,
                preset_level SMALLINT NOT NULL DEFAULT 0,

                admin_user_id BIGINT,
                operator_user_id BIGINT,
                methodist_user_id BIGINT NOT NULL,

                FOREIGN KEY (admin_user_id) REFERENCES {$schema}.{$tablePrefix}users (user_id) MATCH SIMPLE
                    ON UPDATE CASCADE
                    ON DELETE SET NULL,

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

            ALTER TABLE {$schema}.{$tablePrefix}presets
                OWNER to {$userName};
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201022_081934_presets cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201022_081934_presets cannot be reverted.\n";

        return false;
    }
    */
}
