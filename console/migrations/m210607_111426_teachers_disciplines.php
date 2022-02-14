<?php

use yii\db\Migration;

/**
 * Class m210607_111426_teachers_disciplines
 */
class m210607_111426_teachers_disciplines extends Migration
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

            CREATE TABLE {$schema}.{$tablePrefix}teachers_disciplines
            (
                discipline_id BIGINT NOT NULL,
                teacher_user_id BIGINT NOT NULL

            ) WITH (
                OIDS = FALSE
            )
            TABLESPACE pg_default;

            ALTER TABLE {$schema}.{$tablePrefix}teachers_disciplines
                OWNER to {$userName};

            CREATE UNIQUE INDEX idx_teachers_disciplines
                ON {$schema}.{$tablePrefix}teachers_disciplines USING BTREE (discipline_id, teacher_user_id);
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210607_111426_teachers_disciplines cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210607_111426_teachers_disciplines cannot be reverted.\n";

        return false;
    }
    */
}
