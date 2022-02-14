<?php
return [
    'subject' => "Реєстрація акаунту на {APP_NAME}",

    'body_html' => '
        <div class="verify-email">
            <p>Вітаю, {user_name}.</p>

            <p>Використовуйте це посилання для підтвердження вашого Email:</p>

            <p><a href="{verifyLink}">{verifyLink}</a></p>
        </div>
    ',

    'body_text' => '
        Вiтаю, {user_name}.

        Використовуйте це посилання для підтвердження вашого Email:

        {verifyLink}
    ',
];