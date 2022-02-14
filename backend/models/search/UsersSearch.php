<?php

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\helpers\Functions;
use common\models\Users;

/**
 * UsersSearch represents the model behind the search form of `common\models\Users`.
 */
class UsersSearch extends Users
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'user_status', 'user_type', 'operator_user_id', 'methodist_user_id', 'teacher_user_id'], 'integer'],
            [['user_created', 'user_updated', 'password_hash', 'password_reset_token', 'verification_token', 'auth_key', 'user_first_name', 'user_middle_name', 'user_last_name', 'user_full_name', 'user_email', 'user_phone', 'user_last_pay', 'user_token', 'user_hash', 'operator_notice', 'methodist_notice', 'teacher_notice', 'user_last_ip'], 'safe'],
            [['user_balance'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function beforeValidate()
    {
        return true;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Users::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder' => ['user_id'=>SORT_DESC],
                'attributes' => [
                    'user_id',
                    'user_email',
                    'user_status',
                    'user_type',
                    'user_created',
                    'user_updated',
                    'operator_user_id',
                    'methodist_user_id',
                    'teacher_user_id',
                ]
            ],
            'pagination' => [
                'pageSize' => 100,
                'route'=>'users/index',
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'user_id' => $this->user_id,
            'user_updated' => $this->user_updated,
            'user_last_pay' => $this->user_last_pay,
            'user_status' => $this->user_status,
            'user_type' => $this->user_type,
            'operator_user_id' => $this->operator_user_id,
            'methodist_user_id' => $this->methodist_user_id,
            'teacher_user_id' => $this->teacher_user_id,
            'user_balance' => $this->user_balance,
            //'user_last_ip' => $this->user_last_ip,
            //'user_hash' => $this->user_hash,
            //'user_token'=> $this->user_token,
        ]);

        $query
            ->andFilterWhere(['like', 'user_first_name', $this->user_first_name])
            ->andFilterWhere(['like', 'user_middle_name', $this->user_middle_name])
            ->andFilterWhere(['like', 'user_last_name', $this->user_last_name])
            ->andFilterWhere(['like', 'user_full_name', $this->user_full_name])
            ->andFilterWhere(['like', 'user_email', $this->user_email])
            ->andFilterWhere(['like', 'user_phone', $this->user_phone])
            ;

        /**/
        if (($ip = ip2long($this->user_last_ip)) !== false) {
            $query->andFilterWhere(['user_last_ip' => $ip]);
        }

        // do we have values? if so, add a filter to our query
        if(!empty($this->user_created) && strpos($this->user_created, '-') !== false) {
            $tmp = explode(' - ', $this->user_created);
            if (isset($tmp[0], $tmp[1])) {
                $start_date = $tmp[0];
                $end_date = $tmp[1];

                $query->andFilterWhere([
                    'between',
                    'user_created',
                    date(SQL_DATE_FORMAT, Functions::getTimestampBeginOfDayByTimestamp(strtotime($start_date))),
                    date(SQL_DATE_FORMAT, Functions::getTimestampEndOfDayByTimestamp(strtotime($end_date))),
                ]);
            }
        }

        // do we have values? if so, add a filter to our query
        if(!empty($this->user_updated) && strpos($this->user_updated, '-') !== false) {
            $tmp = explode(' - ', $this->user_updated);
            if (isset($tmp[0], $tmp[1])) {
                $start_date = $tmp[0];
                $end_date = $tmp[1];

                $query->andFilterWhere([
                    'between',
                    'user_updated',
                    date(SQL_DATE_FORMAT, Functions::getTimestampBeginOfDayByTimestamp(strtotime($start_date))),
                    date(SQL_DATE_FORMAT, Functions::getTimestampEndOfDayByTimestamp(strtotime($end_date))),
                ]);
            }
        }

        return $dataProvider;
    }
}
