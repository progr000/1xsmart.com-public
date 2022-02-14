<?php

use yii\db\Migration;

/**
 * Class m211206_141307_payments
 */
class m211206_141307_payments extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%payments}} ADD teacher_user_id BIGINT");
        $this->execute("ALTER TABLE {{%payments}}
                        ADD FOREIGN KEY (teacher_user_id) REFERENCES {{%users}} (user_id)
                        MATCH SIMPLE ON UPDATE CASCADE ON DELETE SET NULL");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211206_141307_payments cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211206_141307_payments cannot be reverted.\n";

        return false;
    }
    */
}
