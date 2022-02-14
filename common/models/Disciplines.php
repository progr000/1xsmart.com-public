<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%disciplines}}".
 *
 * @property int $discipline_id
 * @property string $discipline_name_code
 * @property string $discipline_name_en
 * @property string $discipline_name_ru
 * @property string $discipline_name_ua
 * @property int $discipline_sort
 */
class Disciplines extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%disciplines}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['discipline_name_en', 'discipline_name_ru'], 'required'],
            [['discipline_name_en', 'discipline_name_ru', 'discipline_name_ua', 'discipline_name_code'], 'string'],
            [['discipline_sort'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'discipline_id' => 'Discipline ID',
            'discipline_name_en' => 'Discipline Name En',
            'discipline_name_ru' => 'Discipline Name Ru',
        ];
    }
}
