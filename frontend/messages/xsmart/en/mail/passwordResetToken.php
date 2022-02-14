<?php
return [
    'subject' => "Password reset for {APP_NAME}",

    'body_html' => '
        <div class="password-reset">
            <p>Hello, {user_name}.</p>
            <p></p>
            <p>Follow the link below to reset your password:</p>
            <p></p>
            <p><a href="{resetLink}">{$resetLink}</a></p>
        </div>
    ',

    'body_text' => '
        Hello, {user_name}.

        Follow the link below to reset your password:

        {resetLink}
    ',
];