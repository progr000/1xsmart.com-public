<?php

use yii\db\Migration;

/**
 * Class m220110_104659_disciplines
 */
class m220110_104659_disciplines extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%disciplines}} ADD discipline_name_ua CITEXT NOT NULL DEFAULT '';");
        $this->execute("ALTER TABLE {{%disciplines}} ADD discipline_name_code CITEXT NOT NULL DEFAULT '';");

        $this->execute("UPDATE {{%disciplines}} SET discipline_name_code='', discipline_name_ua = discipline_name_ru");
        $this->execute("UPDATE {{%disciplines}} SET discipline_name_code='english', discipline_name_ua = 'Англійська мова' WHERE discipline_id=1");
        $this->execute("UPDATE {{%disciplines}} SET discipline_name_code='spanish', discipline_name_ua = 'Iспанська мова' WHERE discipline_id=2");
        $this->execute("UPDATE {{%disciplines}} SET discipline_name_code='german', discipline_name_ua = 'Німецька мова' WHERE discipline_id=3");
        $this->execute("UPDATE {{%disciplines}} SET discipline_name_code='russian', discipline_name_ua = 'Pосійська мова' WHERE discipline_id=4");
        $this->execute("UPDATE {{%disciplines}} SET discipline_name_code='chinese', discipline_name_ua = 'Китайська мова' WHERE discipline_id=5");
        $this->execute("UPDATE {{%disciplines}} SET discipline_name_code='arabic', discipline_name_ua = 'Арабська мова' WHERE discipline_id=6");
        $this->execute("UPDATE {{%disciplines}} SET discipline_name_code='math', discipline_name_ua = 'Математика' WHERE discipline_id=7");
        $this->execute("UPDATE {{%disciplines}} SET discipline_name_code='biology', discipline_name_ua = 'Біологія' WHERE discipline_id=8");
        $this->execute("UPDATE {{%disciplines}} SET discipline_name_code='chemistry', discipline_name_ua = 'Хімія' WHERE discipline_id=9");
        $this->execute("UPDATE {{%disciplines}} SET discipline_name_code='physics', discipline_name_ua = 'Фізика' WHERE discipline_id=10");
        $this->execute("UPDATE {{%disciplines}} SET discipline_name_code='music', discipline_name_ua = 'Музика' WHERE discipline_id=11");

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220110_104659_disciplines cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220110_104659_disciplines cannot be reverted.\n";

        return false;
    }
    */
}
