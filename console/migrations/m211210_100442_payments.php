<?php

use yii\db\Migration;

/**
 * Class m211210_100442_payments
 */
class m211210_100442_payments extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%payments}} ADD order_amount_usd NUMERIC(11,2) NOT NULL DEFAULT 0.00;");
        $this->execute("UPDATE {{%payments}} SET order_amount_usd = order_amount / 74.66;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211210_100442_payments cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211210_100442_payments cannot be reverted.\n";

        return false;
    }
    */
}
