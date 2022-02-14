<?php
return [
    'subject' => "Змінено час проведення уроку.",

    'body_html' => '
        <div>
            <p>Вiтаю, {teacher_display_name}</p>
            <p></p>
            <p>Учень {student_name} змінив розклад уроків з вами. (one time)</p>
            <p></p>
            <p>Старий час: {week_day_old} {lesson_date_old}</p>
            <p>Новий час: {week_day_new} {lesson_date_new}</p>
            <p></p>
            <p>Ви повинні бути на новому уроці у віртуальному класі.</p>
            <p></p>
            <p>Дякую.</p>
        </div>
    ',

    'body_text' => '
        Вiтаю, {teacher_display_name}

        Учень {student_name} змінив розклад уроків з вами. (one time)

        Старий час: {week_day_old} {lesson_date_old}
        Новий час: {week_day_new} {lesson_date_new}

        Ви повинні бути на новому уроці у віртуальному класі.

        Дякую.
    ',
];