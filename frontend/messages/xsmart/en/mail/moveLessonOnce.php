<?php
return [
    'subject' => "Arranged lesson time has been changed.",

    'body_html' => '
        <div>
            <p>Hello, {teacher_display_name}</p>
            <p></p>
            <p>Student {student_name} has changed lesson schedule with you. (one time)</p>
            <p></p>
            <p>Old time: {week_day_old} {lesson_date_old}</p>
            <p>New time: {week_day_new} {lesson_date_new}</p>
            <p></p>
            <p>You have to be at new lesson time in your virtual classroom.</p>
            <p></p>
            <p>Thank you.</p>
        </div>
    ',

    'body_text' => '
        Hello, {teacher_display_name}

        Student {student_name} has changed lesson schedule with you. (one time)

        Old time: {week_day_old} {lesson_date_old}
        New time: {week_day_new} {lesson_date_new}

        You have to be at new lesson time in your virtual classroom.

        Thank you.
    ',
];