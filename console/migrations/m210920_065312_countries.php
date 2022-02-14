<?php

use yii\db\Migration;

/**
 * Class m210920_065312_countries
 */
class m210920_065312_countries extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE {{%countries}} ADD country_code CHARACTER VARYING(10) DEFAULT 'undefined';");

        $this->execute("UPDATE {{%countries}} SET country_code='ru' WHERE country_id=1;");
        $this->execute("UPDATE {{%countries}} SET country_code='ua' WHERE country_id=2;");
        $this->execute("UPDATE {{%countries}} SET country_code='be' WHERE country_id=3;");
        $this->execute("UPDATE {{%countries}} SET country_code='es' WHERE country_id=87;");
        $this->execute("UPDATE {{%countries}} SET country_code='en' WHERE country_id=49;");
        $this->execute("UPDATE {{%countries}} SET country_code='us' WHERE country_id=9;");
        $this->execute("UPDATE {{%countries}} SET country_code='cz' WHERE country_id=215;");
        $this->execute("UPDATE {{%countries}} SET country_code='pl' WHERE country_id=160;");
        $this->execute("UPDATE {{%countries}} SET country_code='de' WHERE country_id=65;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210920_065312_countries cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210920_065312_countries cannot be reverted.\n";

        return false;
    }
    */
}
