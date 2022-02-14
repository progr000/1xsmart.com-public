<?php

use yii\db\Migration;

/**
 * Class m210607_160928_geo_idx
 */
class m210607_160928_geo_idx extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            CREATE INDEX idx_regions_country_id
                ON {{%regions}} USING BTREE (country_id);
        ");

        $this->execute("
            CREATE INDEX idx_cities_region_id
                ON {{%cities}} USING BTREE (region_id);
        ");

        $this->execute("
            CREATE INDEX idx_cities_country_id
                ON {{%cities}} USING BTREE (country_id);
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210607_160928_geo_idx cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210607_160928_geo_idx cannot be reverted.\n";

        return false;
    }
    */
}
