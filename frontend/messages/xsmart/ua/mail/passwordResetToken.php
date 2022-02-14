<?php
return [
    'subject' => "Скидання пароля для {APP_NAME}",

    'body_html' => '
        <div class="password-reset">
            <p>Вiтаю, {user_name}.</p>

            <p>Використовуйте це посилання для відновлення пароля:</p>

            <p><a href="{resetLink}">{$resetLink}</a></p>
        </div>
    ',

    'body_text' => '
        Вiтаю, {user_name}.

        Використовуйте це посилання для відновлення пароля:

        {resetLink}
    ',
];