<?php
return [
    'subject' => "Учень змінив розклад",

    'body_html' => '
        <div>
            <p>Вiтаю, {teacher_display_name}</p>
            <p></p>
            <p>Учень {student_name} змінив постійний розклад із вами. (permanently)</p>
            <p></p>
            <p>Старий розклад: {week_day_old}, {hour_old} ({short_timezone})</p>
            <p>Новий розклад: {week_day_new}, {hour_new} ({short_timezone})</p>
            <p></p>
            <p>Ви повинні бути на новому уроці у віртуальному класі.</p>
            <p></p>
            <p>Дякую.</p>
        </div>
    ',

    'body_text' => '
        Вiтаю, {teacher_display_name}

        Учень {student_name} змінив постійний розклад із вами. (permanently)

        Старий розклад: {week_day_old}, {hour_old} ({short_timezone})
        Новий розклад: {week_day_new}, {hour_new} ({short_timezone})

        Ви повинні бути на новому уроці у віртуальному класі.

        Дякую.
    ',
];