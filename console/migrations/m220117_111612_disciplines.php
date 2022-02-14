<?php

use yii\db\Migration;

/**
 * Class m220117_111612_disciplines
 */
class m220117_111612_disciplines extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%disciplines}} ADD discipline_sort SMALLINT;");

        $this->execute("UPDATE {{%disciplines}} SET discipline_sort=1 WHERE discipline_id=1");
        $this->execute("UPDATE {{%disciplines}} SET discipline_sort=2 WHERE discipline_id=2");
        $this->execute("UPDATE {{%disciplines}} SET discipline_sort=3 WHERE discipline_id=3");
        $this->execute("UPDATE {{%disciplines}} SET discipline_sort=4 WHERE discipline_id=4");
        $this->execute("UPDATE {{%disciplines}} SET discipline_sort=5 WHERE discipline_id=5");
        $this->execute("UPDATE {{%disciplines}} SET discipline_sort=6 WHERE discipline_id=6");
        $this->execute("UPDATE {{%disciplines}} SET discipline_sort=9 WHERE discipline_id=7");
        $this->execute("UPDATE {{%disciplines}} SET discipline_sort=10 WHERE discipline_id=8");
        $this->execute("UPDATE {{%disciplines}} SET discipline_sort=11 WHERE discipline_id=9");
        $this->execute("UPDATE {{%disciplines}} SET discipline_sort=12 WHERE discipline_id=10");
        $this->execute("UPDATE {{%disciplines}} SET discipline_sort=13 WHERE discipline_id=11");

        $this->execute("
        INSERT INTO {{%disciplines}}
                (discipline_name_en, discipline_name_ru, discipline_name_ua, discipline_name_code, discipline_sort)
            VALUES
                ('French language', 'Французский язык', 'Французька мова', 'french', 7),
                ('Ukrainian language', 'Украинский язык', 'Українська мова', 'ukrainian', 8),
                ('Informatics', 'Информатика', 'Інформатика', 'informatics', 14),
                ('History', 'История', 'Історія', 'history', 15),
                ('Geography', 'География', 'Географія', 'geography', 16),
                ('Literature', 'Литература', 'Література', 'literature', 17)
        ");

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220117_111612_disciplines cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220117_111612_disciplines cannot be reverted.\n";

        return false;
    }
    */
}
