<?php
return [
    'subject' => "Изменено время проведения урока.",

    'body_html' => '
        <div>
            <p>Здравствуйте, {teacher_display_name}</p>
            <p></p>
            <p>Ученик {student_name} изменил расписание уроков с Вами. (one time)</p>
            <p></p>
            <p>Старое время: {week_day_old} {lesson_date_old}</p>
            <p>Новое время: {week_day_new} {lesson_date_new}</p>
            <p></p>
            <p>Вы должны быть на новом уроке в виртуальном классе.</p>
            <p></p>
            <p>Спасибо.</p>
        </div>
    ',

    'body_text' => '
        Здравствуйте, {teacher_display_name}

        Ученик {student_name} изменил расписание уроков с Вами. (one time)

        Старое время: {week_day_old} {lesson_date_old}
        Новое время: {week_day_new} {lesson_date_new}

        Вы должны быть на новом уроке в виртуальном классе.

        Спасибо.
    ',
];