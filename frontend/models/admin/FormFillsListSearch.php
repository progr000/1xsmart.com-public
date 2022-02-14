<?php

namespace frontend\models\admin;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Leads;

/**
 * FormFillsListSearch
 *
 * @property string $filter
 * @property string $sort
 *
 */
class FormFillsListSearch extends Leads
{
    public $filter;
    public $sort;

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //['sort', 'required'],
            [[
                'filter',
                'sort',
            ], 'safe'],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = self::find()->alias('t1')->where(['lead_status' => self::STATUS_NEW]);

        /**/
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder' => ['created' => SORT_DESC],
                'attributes' => [
                    'created'  => [
                        'asc' =>  ['t1.lead_created' => SORT_ASC,  't1.lead_id' => SORT_ASC],
                        'desc' => ['t1.lead_created' => SORT_DESC, 't1.lead_id' => SORT_DESC],
                        'default' => SORT_DESC,
                        'label' => 'Created',
                    ],
                ]
            ],
            'pagination' => [ 'pageSize' => 5 ],
        ]);

        /**/
        $this->load($params);

        /**/
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        /**/
        if ($this->filter) {
            $query->andFilterWhere([
                'or',
                ['like', 'CAST(t1.lead_id AS TEXT)', $this->filter],
                ['like', 't1.lead_name', $this->filter],
                ['like', 't1.lead_email', $this->filter],
                ['like', 't1.lead_phone', $this->filter],
                ['like', 't1.lead_info', $this->filter],
            ]);
        }

        return $dataProvider;
    }

}
