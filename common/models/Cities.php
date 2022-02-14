<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sm_cities".
 *
 * @property int $city_id
 * @property int $country_id
 * @property bool $important
 * @property int|null $region_id
 * @property string|null $title_ru
 * @property string|null $area_ru
 * @property string|null $region_ru
 * @property string|null $title_ua
 * @property string|null $area_ua
 * @property string|null $region_ua
 * @property string|null $title_be
 * @property string|null $area_be
 * @property string|null $region_be
 * @property string|null $title_en
 * @property string|null $area_en
 * @property string|null $region_en
 * @property string|null $title_es
 * @property string|null $area_es
 * @property string|null $region_es
 * @property string|null $title_pt
 * @property string|null $area_pt
 * @property string|null $region_pt
 * @property string|null $title_de
 * @property string|null $area_de
 * @property string|null $region_de
 * @property string|null $title_fr
 * @property string|null $area_fr
 * @property string|null $region_fr
 * @property string|null $title_it
 * @property string|null $area_it
 * @property string|null $region_it
 * @property string|null $title_pl
 * @property string|null $area_pl
 * @property string|null $region_pl
 * @property string|null $title_ja
 * @property string|null $area_ja
 * @property string|null $region_ja
 * @property string|null $title_lt
 * @property string|null $area_lt
 * @property string|null $region_lt
 * @property string|null $title_lv
 * @property string|null $area_lv
 * @property string|null $region_lv
 * @property string|null $title_cz
 * @property string|null $area_cz
 * @property string|null $region_cz
 */
class Cities extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sm_cities';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['city_id', 'country_id', 'important'], 'required'],
            //[['city_id', 'country_id', 'region_id'], 'default', 'value' => null],
            [['city_id', 'country_id', 'region_id'], 'integer'],
            [['important'], 'boolean'],
            [['title_ru', 'area_ru', 'region_ru', 'title_ua', 'area_ua', 'region_ua', 'title_be', 'area_be', 'region_be', 'title_en', 'area_en', 'region_en', 'title_es', 'area_es', 'region_es', 'title_pt', 'area_pt', 'region_pt', 'title_de', 'area_de', 'region_de', 'title_fr', 'area_fr', 'region_fr', 'title_it', 'area_it', 'region_it', 'title_pl', 'area_pl', 'region_pl', 'title_ja', 'area_ja', 'region_ja', 'title_lt', 'area_lt', 'region_lt', 'title_lv', 'area_lv', 'region_lv', 'title_cz', 'area_cz', 'region_cz'], 'string', 'max' => 150],
            [['city_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'city_id' => 'City ID',
            'country_id' => 'Country ID',
            'important' => 'Important',
            'region_id' => 'Region ID',
            'title_ru' => 'Title Ru',
            'area_ru' => 'Area Ru',
            'region_ru' => 'Region Ru',
            'title_ua' => 'Title Ua',
            'area_ua' => 'Area Ua',
            'region_ua' => 'Region Ua',
            'title_be' => 'Title Be',
            'area_be' => 'Area Be',
            'region_be' => 'Region Be',
            'title_en' => 'Title En',
            'area_en' => 'Area En',
            'region_en' => 'Region En',
            'title_es' => 'Title Es',
            'area_es' => 'Area Es',
            'region_es' => 'Region Es',
            'title_pt' => 'Title Pt',
            'area_pt' => 'Area Pt',
            'region_pt' => 'Region Pt',
            'title_de' => 'Title De',
            'area_de' => 'Area De',
            'region_de' => 'Region De',
            'title_fr' => 'Title Fr',
            'area_fr' => 'Area Fr',
            'region_fr' => 'Region Fr',
            'title_it' => 'Title It',
            'area_it' => 'Area It',
            'region_it' => 'Region It',
            'title_pl' => 'Title Pl',
            'area_pl' => 'Area Pl',
            'region_pl' => 'Region Pl',
            'title_ja' => 'Title Ja',
            'area_ja' => 'Area Ja',
            'region_ja' => 'Region Ja',
            'title_lt' => 'Title Lt',
            'area_lt' => 'Area Lt',
            'region_lt' => 'Region Lt',
            'title_lv' => 'Title Lv',
            'area_lv' => 'Area Lv',
            'region_lv' => 'Region Lv',
            'title_cz' => 'Title Cz',
            'area_cz' => 'Area Cz',
            'region_cz' => 'Region Cz',
        ];
    }
}
