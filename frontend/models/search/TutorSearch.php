<?php

namespace frontend\models\search;

use Yii;
use yii\base\Model;
use yii\caching\TagDependency;
use yii\data\ActiveDataProvider;
use common\models\Users;

/**
 * MethodistsListSearch
 *
 * @property int $discipline_id
 * @property string $price
 * @property array $_user_goals_of_education
 * @property array $_user_speak_also
 * @property array $_user_are_native
 * @property array $_user_day_ability
 * @property array $_user_time_ability
 * @property string $filter
 */
class TutorSearch extends Users
{
    public $discipline_id;
    public $filter;
    public $page;
    public $sort;
    public $price;
    public $_user_goals_of_education;
    public $_user_speak_also;
    public $_user_are_native;
    public $_user_day_ability;
    public $_user_time_ability;

    protected $time_ability = [
        '6-10' => [6, 7, 8, 9, 10],
        '10-13' => [10, 11, 12, 13],
        '13-19' => [13, 14, 15, 16, 17, 18, 19],
        '19-0' => [19, 20, 21, 22, 23, 0],
        '0-6' => [0, 1, 2, 3, 4, 5, 6],
    ];

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
                'price',
                '_user_goals_of_education',
                '_user_speak_also',
                '_user_are_native',
                '_user_day_ability',
                '_user_time_ability',
                'sort',
            ], 'safe'],
            [[
                'discipline_id',
                'country_id',
                'region_id',
                'city_id',
            ], 'integer'],
            ['user_can_teach_children', 'in', 'range' => [self::YES, self::NO, -1]],
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
        //var_dump($params);

        $query = self::find()->alias('t1');
        $query->select('t1.*');
        $query->groupBy('t1.user_id');
        $query->leftJoin(
        //$query->innerJoin(
            '{{%teachers_disciplines}} as t2',
            '(t1.user_id = t2.teacher_user_id)'
        );
        $query->where([
            't1.user_type'   => self::TYPE_TEACHER,
            't1.user_status' => self::STATUS_ACTIVE,
            't1.teacher_profile_completed' => self::TEACHER_PROFILE_APPROVED,
        ]);

        /**/
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder' => ['exact_match' => SORT_DESC],
                'attributes' => [
                    'exact_match'  => [
                        'asc' =>  ['t1.user_id' => SORT_ASC],
                        'desc' => ['t1.user_id' => SORT_ASC],
                        'default' => SORT_ASC,
                        'label' => 'ID',
                    ],
                    'price_lowest'  => [
                        'asc' =>  ['t1.user_price_peer_hour' => SORT_ASC,  't1.user_id' => SORT_ASC],
                        'desc' => ['t1.user_price_peer_hour' => SORT_ASC,  't1.user_id' => SORT_ASC],
                        'default' => SORT_ASC,
                        'label' => 'Created',
                    ],
                    'price_highest' => [
                        'asc' =>  ['t1.user_price_peer_hour' => SORT_DESC,  't1.user_id' => SORT_ASC],
                        'desc' => ['t1.user_price_peer_hour' => SORT_DESC, 't1.user_id' => SORT_ASC],
                        'default' => SORT_DESC,
                        'label' => 'Name',
                    ],
//                    'rating' => [
//                        'asc' =>  [new Expression('tmt ASC NULLS LAST')],
//                        'desc' => [new Expression('tmt DESC NULLS LAST')],
//                        'default' => SORT_DESC,
//                        'label' => 'Name',
//                    ]
                ]
            ],
            'pagination' => [ 'pageSize' => 8 ],
        ]);

        /**/
        $this->load($params);

        /**/
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (intval($this->country_id) == 0) { $this->country_id = null; }
        if (intval($this->region_id) == 0) { $this->region_id = null; } else { $this->country_id = null; }
        if (intval($this->city_id) == 0) { $this->city_id = null; } else { $this->region_id = null; $this->country_id = null; }
        if ($this->user_can_teach_children <= 0) { $this->user_can_teach_children = null; }

        /**/
        $query->andFilterWhere([
            't2.discipline_id' => $this->discipline_id,
            't1.country_id' => $this->country_id,
            't1.region_id' => $this->region_id,
            't1.city_id' => $this->city_id,
            't1.user_can_teach_children' => $this->user_can_teach_children,
        ]);

        if (isset($this->_user_day_ability) || isset($this->_user_time_ability)) {
            /*
             * вариант поиска по совпадению всех дней недели и можно тут же по аналогии и с часами добавить
SELECT
t1.user_id
--, SUM(1) AS "sum_week_day"
--, SUM(CASE WHEN t3.week_day = ANY('{ 1, 2, 3, 4, 5, 6}'::int[]) THEN 1 ELSE 0 END) as sum_week_day
, ARRAY[1, 4, 3, 5]
, array_agg(DISTINCT t3.week_day ORDER BY t3.week_day ASC) as t
, CASE WHEN (ARRAY[1, 3, 4, 5]) = array_agg(DISTINCT t3.week_day::INT ORDER BY t3.week_day::INT ASC) THEN 1 ELSE 0 END as sa_week_day
, array_agg(DISTINCT t3.work_hour ORDER BY t3.work_hour ASC) as sa_work_hour
FROM "sm_users" "t1"
--LEFT JOIN "sm_teachers_disciplines" "t2" ON (t1.user_id = t2.teacher_user_id)
INNER JOIN "sm_teachers_schedule" "t3" ON (t1.user_id = t3.teacher_user_id)
WHERE (("t1"."user_type"=3)
AND ("t1"."user_status"=10))
AND ("t3"."week_day" IN ('1', '3', '4', '5', '6'))
GROUP BY "t1"."user_id"
HAVING array_agg(DISTINCT t3.week_day::INT ORDER BY t3.week_day::INT ASC) = ARRAY[1, 3, 4, 5]

            */
            //$query->leftJoin(
            //$query->select("t1.*, SUM(CASE WHEN t3.week_day = ANY('{ 1 , 2 , 3}'::int[]) THEN 1 ELSE 0 END) as sum_week_day ");
            //$query->select("t1.* ");
            $query->innerJoin(
                '{{%teachers_schedule}} as t3',
                '(t1.user_id = t3.teacher_user_id) AND (t3.student_user_id IS NULL)'
            );
            if (isset($this->_user_day_ability)) {
                foreach ($this->_user_day_ability as $k=>$v) {
                    $this->_user_day_ability[$k] = intval($v);
                }
                sort($this->_user_day_ability);
                //var_dump($this->_user_day_ability);
                $query->andWhere(['t3.week_day' => $this->_user_day_ability]);
            }
            if (isset($this->_user_time_ability)) {
                //var_dump($this->_user_time_ability);
                $uta = [];
                foreach ($this->_user_time_ability as $k=>$v) {
                    //$this->_user_time_ability[$k] = intval($v);
                    //var_dump($v);
                    //var_dump(isset($this->time_ability[$v]));
                    if (isset($this->time_ability[$v])) {
                        $uta = array_merge($uta, $this->time_ability[$v]);
                    }
                }
                $uta = array_unique($uta);
                //var_dump($uta);
                sort($uta);
                $query->andWhere(['t3.work_hour' => $uta]);
            }
        }

        if ($this->price && isset(Users::$_price_vars[$this->price])) {
            $query->andWhere('t1.user_price_peer_hour BETWEEN :min AND :max', [
                'min' => Users::$_price_vars[$this->price]['min'],
                'max' => Users::$_price_vars[$this->price]['max'],
            ]);
        }

        if ($this->_user_goals_of_education) {
            $like_goals = [];
            foreach ($this->_user_goals_of_education as $k=>$v) {
                $like_goals[] = "(t1.user_goals_of_education LIKE '%$k%')";
            }
            $query->andWhere(implode(' AND ', $like_goals));
        }

        if ($this->_user_are_native) {
            $like_native = [];
            foreach ($this->_user_are_native as $k=>$v) {
                $like_native[] = "(t1.user_are_native LIKE '%$k%')";
            }
            $query->andWhere(implode(' AND ', $like_native));
        }

        if ($this->_user_speak_also) {
            $like_also = [];
            foreach ($this->_user_speak_also as $k=>$v) {
                $like_also[] = "(t1.user_speak_also LIKE '%$k%')";
            }
            $query->andWhere(implode(' AND ', $like_also));
        }

        //var_dump($this->sort);
        //var_dump($query->createCommand()->getRawSql());

        //echo "count: {$dataProvider->count}<br />";
        //echo "page:  {$dataProvider->pagination->page}<br />";
        //echo "orders: " . print_r($dataProvider->sort->orders) . "<br />";
        //exit;

        self::getDb()->cache(function ($db) use ($dataProvider) {
            $dataProvider->prepare();
        }, CACHE_TTL, new TagDependency(['tags' => 'Users.Teachers']));

        return $dataProvider;
    }
}
