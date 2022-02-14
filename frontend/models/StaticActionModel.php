<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\base\InvalidArgumentException;
use common\helpers\Functions;
use common\models\Countries;
use common\models\Disciplines;
use common\models\Users;
use common\models\Reviews;
use frontend\models\schedule\TeachersScheduleForm;

/**
 * NextLessons represents the model behind the search form about common\models\News.
 */
class StaticActionModel extends Model
{

    /**
     * @param array $data
     * @param Users $User
     * @return array|\yii\db\ActiveRecord[]
     */
    public function disciplines($data=[], $User=null)
    {
        return Yii::$app->getDb()->cache(
            function($db) {
                return Disciplines::find()
                    ->asArray()
                    ->orderBy(['discipline_sort' => SORT_ASC])
                    ->all();
            },
            CACHE_TTL
        );
    }

    /**
     * @param array $data
     * @param Users $User
     * @return array
     */
    public function findTutors($data=[], $User=null)
    {
        return [
            'disciplines' => Yii::$app->getDb()->cache(
                function($db) {
                    return Disciplines::find()
                        ->asArray()
                        ->orderBy(['discipline_sort' => SORT_ASC])
                        ->all();
                },
                CACHE_TTL
            ),
            'countries'   => Yii::$app->getDb()->cache(
                function($db) {
                    return Countries::find()
                        ->orderBy(['country_id' => SORT_ASC])
                        ->asArray()
                        ->all();
                },
                CACHE_TTL
            ),
        ];
    }

    /**
     * @param array $data
     * @param Users|null $User
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function tutor($data=[], $User=null)
    {
        if (isset($data['id'])) {
            $data['id'] = intval($data['id']);
            $tutor = Users::find()->where([
                'user_id'   => $data['id'],
                'user_type' => Users::TYPE_TEACHER,
            ])->one();
            /** @var $tutor Users */
            if ($tutor) {

                if (isset($data['timezone'])) {
                    $check_tz = Functions::get_list_of_timezones('offset_short_name');
                    if (isset($check_tz[$data['timezone']])) {
                        $tz = intval($data['timezone']);
                        Yii::$app->session->set('js_user_time_zone', $tz);
                    } else {
                        $tz = 0;
                    }
                    $local_time = time() + $tz;
                } else {
                    if (!$User) {
                        $js_user_time_zone = Yii::$app->session->get('js_user_time_zone', null);
                        $check_tz = Functions::get_list_of_timezones('offset_short_name');
                        if (isset($check_tz[$js_user_time_zone])) {
                            $tz = intval($js_user_time_zone);
                        } else {
                            $tz = 0;
                        }
                        $local_time = time() + $tz;
                    } else {
                        $tz = $User->user_timezone;
                        $local_time = $User->_user_local_time;
                    }

//                    $tz = $User ? $User->user_timezone : 0;
//                    $local_time = $User ? $User->_user_local_time : time();
                }
                $scheduleModel = new TeachersScheduleForm();
                if ($scheduleModel->load([$scheduleModel->formName() => [
                        'user_id'       => $tutor->user_id,
                        'user_type'     => Users::TYPE_TEACHER,
                        'user_timezone' => $tz,
                    ]]) && $scheduleModel->validate()) {

                    return [
                        'tutor'    => $tutor,
                        'schedule' => $scheduleModel->getScheduleForTwoWeekByDate($local_time, $tz, false),
                        'reviews' => Reviews::find()->where(['teacher_user_id' => $tutor->user_id])->orderBy(['review_created' => SORT_DESC])->all(),
                        //'reviews' => null,
                        'current_tz' => $tz,
                    ];
                }


            }
        }

        throw new InvalidArgumentException('The requested page does not exist.');
    }
}
