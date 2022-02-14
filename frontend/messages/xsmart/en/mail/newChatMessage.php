<?php
return [
    'subject' => "New message for you at {APP_NAME}.",

    'body_html' => '
        <div>
            <p>Hello, {user_name}!</p>
            <p>A message has been sent to you via the internal chat {APP_NAME}.</p>
            <p>To view the message, follow the link:</p>
            <p></p>
            <p><a href="{memberLink}">{memberLink}</a></p>
            <p></p>
            <p>Best regards, {APP_NAME}.</p>
        </div>
    ',

    'body_text' => '
        Hello, {user_name}!
        A message has been sent to you via the internal chat {APP_NAME}.
        To view the message, follow the link:

        {memberLink}

        Best regards, {APP_NAME}.
    ',
];