<?php
return [
    'subject' => "Ученик изменил расписание",

    'body_html' => '
        <div>
            <p>Здравствуйте, {teacher_display_name}</p>
            <p></p>
            <p>Ученик {student_name} сменил постоянное расписание с Вами. (permanently)</p>
            <p></p>
            <p>Старое расписание: {week_day_old}, {hour_old} ({short_timezone})</p>
            <p>Новое расписание: {week_day_new}, {hour_new} ({short_timezone})</p>
            <p></p>
            <p>Вы должны быть на новом уроке в виртуальном классе.</p>
            <p></p>
            <p>Спасибо.</p>
        </div>
    ',

    'body_text' => '
        Здравствуйте, {teacher_display_name}

        Ученик {student_name} сменил постоянное расписание с Вами. (permanently)

        Старое расписание: {week_day_old}, {hour_old} ({short_timezone})
        Новое расписание: {week_day_new}, {hour_new} ({short_timezone})

        Вы должны быть на новом уроке в виртуальном классе.

        Спасибо.
    ',
];