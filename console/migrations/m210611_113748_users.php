<?php

use yii\db\Migration;

/**
 * Class m210611_113748_users
 */
class m210611_113748_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE {{%users}}
              ADD country_id INTEGER DEFAULT NULL,

              ADD region_id INTEGER DEFAULT NULL,

              ADD city_id INTEGER DEFAULT NULL,

              ADD FOREIGN KEY (country_id) REFERENCES {{%countries}} (country_id)
              MATCH SIMPLE ON UPDATE CASCADE ON DELETE SET NULL,

              ADD FOREIGN KEY (region_id) REFERENCES {{%regions}} (region_id)
              MATCH SIMPLE ON UPDATE CASCADE ON DELETE SET NULL,

              ADD FOREIGN KEY (city_id) REFERENCES {{%cities}} (city_id)
              MATCH SIMPLE ON UPDATE CASCADE ON DELETE SET NULL;
            ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210611_113748_users cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210611_113748_users cannot be reverted.\n";

        return false;
    }
    */
}
