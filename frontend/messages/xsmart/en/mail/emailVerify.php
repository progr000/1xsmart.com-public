<?php
return [
    'subject' => "Account registration at {APP_NAME}",

    'body_html' => '
        <div class="verify-email">
            <p>Hello, {user_name}.</p>
            <p></p>
            <p>Follow the link below to verify your Email:</p>
            <p></p>
            <p><a href="{verifyLink}">{verifyLink}</a></p>
        </div>
    ',

    'body_text' => '
        Hello, {user_name}.

        Follow the link below to verify your email:

        {verifyLink}
    ',
];