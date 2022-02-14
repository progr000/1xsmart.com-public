<?php
use yii\authclient\widgets\AuthChoice;

function linkAccountWidget($Auth, $for_mobile=false)
{
    if (isset(Yii::$app->components['authClientCollection']['clients'])) {
        $links_count = sizeof(Yii::$app->components['authClientCollection']['clients']);
        //$links_count = 1;
        if (sizeof($Auth) < $links_count) {
            if (!$for_mobile) {
                echo '<div class="settings-sidebar-section">';
            }
            echo '<div class="profile-photo">';
            echo '<div class="profile-photo__label">' . Yii::t('app/settings-and-profile', 'Link_account_to_') . '</div>';
            $exist_auth = [];
            /** @var \common\models\Auth $el */
            foreach ($Auth as $el) {
                $exist_auth[$el->source] = $el->source;
            }
            $social_key = [
                'google' => '<svg class="svg-icon-google svg-icon" width="22" height="22"><use xlink:href="#google"></use></svg>',
                'facebook' => '<svg class="svg-icon-fb svg-icon" width="11" height="22"><use xlink:href="#fb"></use></svg>',
            ];
            $authAuthChoice = AuthChoice::begin([
                'baseAuthUrl' => ['site/auth'],
                'popupMode' => true,
                'options' => ['class' => 'social-auth__links member'],
            ]);
            foreach ($authAuthChoice->getClients() as $key => $client) {
                if (isset($exist_auth[$key])) {
                    continue;
                }
                if (isset($social_key[$key])) {
                    $text = $social_key[$key];
                } else {
                    $text = 'Unknown';
                }
                echo $authAuthChoice->clientLink(
                    $client,
                    $text,
                    ['class' => "social-auth__link -auth-icon {$key}"]
                );
                //echo $authAuthChoice->createClientUrl($client);
            }
            AuthChoice::end();
            echo '</div>';
            if (!$for_mobile) {
                echo '</div>';
            }
        }
    }
}
