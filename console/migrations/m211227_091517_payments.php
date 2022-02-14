<?php

use yii\db\Migration;

/**
 * Class m211227_091517_payments
 */
class m211227_091517_payments extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%payments}} ADD is_read_by_user SMALLINT NOT NULL DEFAULT 0");
        $this->execute("ALTER TABLE {{%payments}} ADD is_read_by_admin SMALLINT NOT NULL DEFAULT 0");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211227_091517_payments cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211227_091517_payments cannot be reverted.\n";

        return false;
    }
    */
}
