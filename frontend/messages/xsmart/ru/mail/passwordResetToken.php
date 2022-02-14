<?php
return [
    'subject' => "Сброс пароля для {APP_NAME}",

    'body_html' => '
        <div class="password-reset">
            <p>Здравствуйте, {user_name}.</p>

            <p>Используйте эту ссылку для восстановления пароля:</p>

            <p><a href="{resetLink}">{$resetLink}</a></p>
        </div>
    ',

    'body_text' => '
        Здравствуйте, {user_name}.

        Используйте эту ссылку для восстановления пароля:

        {resetLink}
    ',
];