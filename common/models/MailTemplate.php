<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class for table "{{%mail_templates}}".
 *
 * @property string $template_id
 * @property string $template_key
 * @property string $template_lang
 * @property string $template_to_email
 * @property string $template_to_name
 * @property string $template_from_email
 * @property string $template_from_name
 * @property string $template_reply_to_email
 * @property string $template_reply_to_name
 * @property string $template_subject
 * @property string $template_body_html
 * @property string $template_body_text
 *
 *
 *
 *
 * Usage example
 *
MailTemplate::send([
    'to_email'   => $to_email,
    'to_name'    => $to_name,
    'from_email' => $from_email,
    'from_name'  => $from_name,
    'subject'    => $subject,
    'composeTemplate' => ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
    'composeData'     => ['user' => $this->CurrentUser],
]);

or

MailTemplate::send([
'to_email' => $to_email,
'to_name'  => $to_name,
'subject'  => $subject,
'body'     => $messageHtml_or_Text,
]);

or

MailTemplate::send([
'to_email' => $to_email,
'to_name'  => $to_name,
'subject'  => $subject,
'bodyHtml' => $messageHtml,
'bodyText' => $messageText,
]);
 *
 *
 */
class MailTemplate extends Model
{
    public static function send(array $params)
    {
        /**/
        if (isset($params['language'])) {
            if (!in_array($params['language'], Yii::$app->components['urlManager']['languages'])) {
                $params['language'] = Yii::$app->components['urlManager']['languages'][0];
            }
        }
        if (!isset($params['language'])) {
            $params['language'] = Yii::$app->language;
        }
        $language_old = Yii::$app->language;
        Yii::$app->language = $params['language'];

        /**/
        if (!isset($params['to_email'])) {
            Yii::error('MailTemplate:send:: Error to_email is required');
            return false;
        }
        if (!isset($params['to_name'])) {
            $params['to_name'] = mb_substr($params['to_email'], 0, mb_strpos($params['to_email'], '@'));
        }

        /**/
        if (!isset($params['from_email'])) {
            $params['from_email'] = Yii::$app->params['senderEmail'];
            $params['from_name'] = Yii::$app->params['senderName'];
        }
        if (!isset($params['from_name'])) {
            $params['from_name'] = mb_substr($params['from_email'], 0, mb_strpos($params['from_email'], '@'));
        }


        /**/
        if (isset($params['composeTemplate'])) {

            if (!isset($params['composeData'])) {
                $params['composeData'] = [];
            }
            if (isset($params['composeLinks']) && is_array($params['composeLinks'])) {
                foreach ($params['composeLinks'] as $k => $v) {
                    $params['composeData'][$k] = Yii::$app->urlManager->createAbsoluteUrl($v);
                }
            }
            //var_dump($params['composeData']);
            $params['subject']  = Yii::t("mail/{$params['composeTemplate']}", 'subject',   $params['composeData']);
            $params['bodyHtml'] = Yii::t("mail/{$params['composeTemplate']}", 'body_html', $params['composeData']);
            $params['bodyText'] = Yii::t("mail/{$params['composeTemplate']}", 'body_text', $params['composeData']);

        } elseif (isset($params['bodyHtml']) || isset($params['bodyText']) || isset($params['body'])) {

            if (!isset($params['subject'])) {
                $params['subject'] = '';
            }

            if (!isset($params['bodyHtml'])) {
                $params['bodyHtml'] = nl2br( isset($params['body']) ? $params['body'] : $params['bodyText'] );
            }

            if (!isset($params['bodyText'])) {
                $params['bodyText'] = strip_tags( isset($params['body']) ? $params['body'] : $params['bodyHtml'] );
            }

        } else {
            Yii::error('MailTemplate:send:: Error bodyHtml or bodyText or body or composeTemplate are required');
            return false;
        }

        try {

            /* compose mail */
            $mailer = Yii::$app->mailer;
            $newMail = $mailer
                ->compose()
                //->compose($params['composeTemplate'], $params['composeData'])
                ->setTo([$params['to_email'] => $params['to_name']])
                ->setFrom([$params['from_email'] => $params['from_name']])
                ->setSubject($params['subject'])
                ->setHtmlBody($params['bodyHtml'])
                ->setTextBody($params['bodyText']);

            if (!empty($params['reply_to_email'])) {
                $newMail->setReplyTo([$params['reply_to_email'] => $params['reply_to_email']]);
            }

            /* attachment */
            if (!empty($params['attachment'])) {
                if (file_exists($params['attachment'])) {
                    $newMail->attach($params['attachment']);
                }
            }

            /* logger */
            $mailer2 = Yii::$app->mailer->getSwiftMailer();//->getTransport();
            $logger = new \Swift_Plugins_Loggers_ArrayLogger();
            $mailer2->registerPlugin(new \Swift_Plugins_LoggerPlugin($logger));

            if ($newMail->send()) {
                $ret = true;
            } else {
                $ret = false;
            }


            $mailer_answer = $logger->dump();
            //var_dump($mailer_answer);
            $regexp_for_search_id = "/queued as ([a-z0-9]*)(?:$|\s)/siU";
            preg_match($regexp_for_search_id, $mailer_answer, $ma);
            //var_dump($ma);

            $pathMailTpl = Yii::$app->components['mailer']['viewPath'];


            $mq                       = new Mailq();
            $mq->mailer_answer        = $mailer_answer ? $mailer_answer : 'null (empty answer)';
            $mq->mailer_letter_id     = isset($ma[1]) ? $ma[1] : null;
            $mq->mailer_letter_status = isset($ma[1]) ? Mailq::STATUS_QUEUED : Mailq::STATUS_ERROR;
            $mq->mail_from            = $params['from_name'] . " <{$params['from_email']}>";
            $mq->mail_to              = $params['to_name'] . " <{$params['to_email']}>";
            $mq->mail_reply_to        = !empty($params['reply_to_email']) ? $params['reply_to_email'] : '';
            $mq->mail_subject         = $params['subject'];
            $mq->mail_body_html       = isset($params['bodyHtml'])
                ? $params['bodyHtml']
                : Yii::$app->getView()->renderFile("{$pathMailTpl}/{$params['composeTemplate']['html']}.php", $params['composeData']);
            $mq->mail_body_text       = isset($params['bodyText'])
                ? $params['bodyText']
                : Yii::$app->getView()->renderFile("{$pathMailTpl}/{$params['composeTemplate']['text']}.php", $params['composeData']);
            $mq->user_id              = (!empty($params['User']) && is_object($params['User']) && isset($params['User']->user_id))
                ? $params['User']->user_id
                : null;
            $mq->save();
            //var_dump($mq->save());
            //var_dump($mq->getErrors());


            if (!$ret) {
                Yii::error("MailTemplate:send:: Error {$mailer_answer}");
            }

            Yii::$app->language = $language_old;
            return $ret;

        } catch (\Exception $e) {
            Yii::error("MailTemplate:send:: Error {$e->getMessage()}");
            Yii::$app->language = $language_old;
            return false;
        }

    }
}
