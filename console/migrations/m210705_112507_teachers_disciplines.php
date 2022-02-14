<?php

use yii\db\Migration;

/**
 * Class m210705_112507_teachers_disciplines
 */
class m210705_112507_teachers_disciplines extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE {{%teachers_disciplines}}

              ADD FOREIGN KEY (discipline_id) REFERENCES {{%disciplines}} (discipline_id)
              MATCH SIMPLE ON UPDATE CASCADE ON DELETE CASCADE,

              ADD FOREIGN KEY (teacher_user_id) REFERENCES {{%users}} (user_id)
              MATCH SIMPLE ON UPDATE CASCADE ON DELETE CASCADE;

            ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210705_112507_teachers_disciplines cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210705_112507_teachers_disciplines cannot be reverted.\n";

        return false;
    }
    */
}
