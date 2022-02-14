<?php

use yii\db\Migration;

/**
 * Class m210201_140531_payments
 */
class m210201_140531_payments extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%payments}} ADD order_ip CHARACTER VARYING(30) DEFAULT NULL::character varying;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210201_140531_payments cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210201_140531_payments cannot be reverted.\n";

        return false;
    }
    */
}
