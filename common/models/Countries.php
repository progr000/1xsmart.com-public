<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sm_countries".
 *
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
 * @property string|null $country_code
 */
class Countries extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sm_countries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['country_id'], 'required'],
            //[['country_id'], 'default', 'value' => null],
            [['country_id'], 'integer'],
            [['title_ru', 'title_ua', 'title_be', 'title_en', 'title_es', 'title_pt', 'title_de', 'title_fr', 'title_it', 'title_pl', 'title_ja', 'title_lt', 'title_lv', 'title_cz'], 'string', 'max' => 60],
            ['country_code', 'string', 'max' => 10],
            ['country_code', 'default', 'value' => 'undefined'],
            [['country_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
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
