<?php
return [
    'subject' => "Profile has been approved.",

    'body_html' => '
        <div>
            <p>Hello, {user_name}!</p>
            <p>Your profile has been approved, now students can see your profile in search results.</p>
            <p>If necessary, you can change your schedule and other details by following the link:</p>
            <p></p>
            <p><a href="{memberLink}">{memberLink}</a></p>
            <p></p>
            <p>We wish you fruitful interaction with the platform!</p>
        </div>
    ',

    'body_text' => '
        Hello, {user_name}!
        Your profile has been approved, now students can see your profile in search results.
        If necessary, you can change your schedule and other details by following the link:

        {memberLink}

        We wish you fruitful interaction with the platform!
    ',
];