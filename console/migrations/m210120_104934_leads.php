<?php

use yii\db\Migration;
use common\models\Users;

/**
 * Class m210120_104934_leads
 */
class m210120_104934_leads extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%leads}} ADD admin_user_id BIGINT;");
        $this->execute("ALTER TABLE {{%leads}} ADD admin_notice CITEXT;");
        $this->execute("ALTER TABLE {{%leads}} ADD FOREIGN KEY (admin_user_id) REFERENCES public.sm_users (user_id) MATCH SIMPLE ON UPDATE CASCADE ON DELETE SET NULL");
        $this->execute("ALTER TABLE {{%leads}} ADD user_type SMALLINT NOT NULL DEFAULT " . Users::TYPE_STUDENT . ";");
        $this->execute("ALTER TABLE {{%leads}} ADD additional_service_info CITEXT;");
        $this->execute("ALTER TABLE {{%leads}} ADD additional_service_notice CITEXT;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210120_104934_leads cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210120_104934_leads cannot be reverted.\n";

        return false;
    }
    */
}
