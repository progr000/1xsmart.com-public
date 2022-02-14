<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sm_regions".
 *
 * @property int $region_id
 * @property int $country_id
 * @property string|null $title_ru
 * @property string|null $title_ua
 * @property string|null $title_be
 * @property string|null $title_en
 * @property string|null $title_es
 * @property string|null $title_pt
 * @property string|null $title_de
 * @property string|null $title_fr
 * @property string|null $title_it
 * @property string|null $title_pl
 * @property string|null $title_ja
 * @property string|null $title_lt
 * @property string|null $title_lv
 * @property string|null $title_cz
 */
class Regions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sm_regions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['region_id', 'country_id'], 'required'],
            //[['region_id', 'country_id'], 'default', 'value' => null],
            [['region_id', 'country_id'], 'integer'],
            [['title_ru', 'title_ua', 'title_be', 'title_en', 'title_es', 'title_pt', 'title_de', 'title_fr', 'title_it', 'title_pl', 'title_ja', 'title_lt', 'title_lv', 'title_cz'], 'string', 'max' => 150],
            [['region_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'region_id' => 'Region ID',
            'country_id' => 'Country ID',
            'title_ru' => 'Title Ru',
            'title_ua' => 'Title Ua',
            'title_be' => 'Title Be',
            'title_en' => 'Title En',
            'title_es' => 'Title Es',
            'title_pt' => 'Title Pt',
            'title_de' => 'Title De',
            'title_fr' => 'Title Fr',
            'title_it' => 'Title It',
            'title_pl' => 'Title Pl',
            'title_ja' => 'Title Ja',
            'title_lt' => 'Title Lt',
            'title_lv' => 'Title Lv',
            'title_cz' => 'Title Cz',
        ];
    }
}
