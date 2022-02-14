<?php
return [
    'subject' => "Регистрация аккаунта на {APP_NAME}",

    'body_html' => '
        <div class="verify-email">
            <p>Здравствуйте, {user_name}.</p>

            <p>Используйте эту ссылку для подтверждения вашего Email:</p>

            <p><a href="{verifyLink}">{verifyLink}</a></p>
        </div>
    ',

    'body_text' => '
        Здравствуйте, {user_name}.

        Используйте эту ссылку для подтверждения вашего Email:

        {verifyLink}
    ',
];