<?php

namespace frontend\models\admin;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use common\models\Users;

/**
 * StudentsListSearch
 *
 * @property string $filter
 * @property string $sort
 *
 */
class StudentsListSearch extends Users
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
        $query = self::find()->alias('t1');

        /**/
        $query->andWhere([
            't1.user_type'   => self::TYPE_STUDENT,
        ]);


        /**/
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder' => ['created' => SORT_DESC],
                'attributes' => [
                    'id'  => [
                        'asc' =>  ['t1.user_id' => SORT_ASC],
                        'desc' => ['t1.user_id' => SORT_DESC],
                        'default' => SORT_DESC,
                        'label' => 'ID',
                    ],
                    'created'  => [
                        'asc' =>  ['t1.user_created' => SORT_ASC,  't1.user_id' => SORT_ASC],
                        'desc' => ['t1.user_created' => SORT_DESC, 't1.user_id' => SORT_ASC],
                        'default' => SORT_DESC,
                        'label' => 'Created',
                    ],
                    'balance' => [
                        'asc' =>  ['t1.user_balance' => SORT_ASC,  't1.user_id' => SORT_ASC],
                        'desc' => ['t1.user_balance' => SORT_DESC, 't1.user_id' => SORT_ASC],
                        'default' => SORT_DESC,
                        'label' => 'Name',
                    ],
                    'last-login' => [
                        'asc' =>  [new Expression('t1.user_last_visit ASC NULLS first')],
                        'desc' => [new Expression('t1.user_last_visit DESC NULLS last')],
                        'default' => SORT_DESC,
                        'label' => 'LastLogin',
                    ],
                    'paid' => [
                        'asc' =>  [new Expression('t1.user_last_pay ASC NULLS first')],
                        'desc' => [new Expression('t1.user_last_pay DESC NULLS last')],
                        'default' => SORT_DESC,
                        'label' => 'Paid',
                    ],
                ]
            ],
            'pagination' => [ 'pageSize' => 20 ],
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
                ['like', 'CAST(t1.user_id AS TEXT)', $this->filter],
                ['like', 't1.user_full_name', $this->filter],
                ['like', 't1.user_first_name', $this->filter],
                ['like', 't1.user_middle_name', $this->filter],
                ['like', 't1.user_last_name', $this->filter],
                ['like', 't1.user_email', $this->filter],
                ['like', 't1.user_phone', $this->filter],
            ]);
        }

        return $dataProvider;
    }

}
