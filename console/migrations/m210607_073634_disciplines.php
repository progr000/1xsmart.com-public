<?php

use yii\db\Migration;

/**
 * Class m210607_073634_disciplines
 */
class m210607_073634_disciplines extends Migration
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

            CREATE SEQUENCE {$schema}.{$tablePrefix}disciplines_discipline_id_seq
                INCREMENT 1
                START 1
                MINVALUE 1
                MAXVALUE 9223372036854775807
                CACHE 1;

            ALTER SEQUENCE {$schema}.{$tablePrefix}disciplines_discipline_id_seq
                OWNER TO {$userName};

            CREATE TABLE {$schema}.{$tablePrefix}disciplines
            (
                discipline_id BIGINT PRIMARY KEY NOT NULL DEFAULT nextval('{$tablePrefix}disciplines_discipline_id_seq'::regclass),
                discipline_name_en PUBLIC.CITEXT NOT NULL,
                discipline_name_ru PUBLIC.CITEXT NOT NULL

            ) WITH (
                OIDS = FALSE
            )
            TABLESPACE pg_default;

            ALTER TABLE {$schema}.{$tablePrefix}disciplines
                OWNER to {$userName};

            INSERT INTO {$schema}.{$tablePrefix}disciplines
                (discipline_name_en, discipline_name_ru)
            VALUES
                ('English language', 'Английский язык'),
                ('Spanish language', 'Испанский язык'),
                ('German language', 'Немецкий язык'),
                ('Russian language', 'Русский язык'),
                ('Chinese language', 'Китайский язык'),
                ('Arabic language', 'Арабский язык'),
                ('Math', 'Математика'),
                ('Biology', 'Биология'),
                ('Chemistry', 'Химия'),
                ('Physics', 'Физика'),
                ('Music', 'Музыка');
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210607_073634_disciplines cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210607_073634_disciplines cannot be reverted.\n";

        return false;
    }
    */
}
