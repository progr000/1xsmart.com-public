<?php
return [
    'subject' => "Student changed the schedule.",

    'body_html' => '
        <div>
            <p>Hello, {teacher_display_name}</p>
            <p></p>
            <p>Student {student_name} has changed permanent schedule with you. (permanently)</p>
            <p></p>
            <p>Old schedule: {week_day_old}, {hour_old} ({short_timezone})</p>
            <p>New schedule: {week_day_new}, {hour_new} ({short_timezone})</p>
            <p></p>
            <p>You have to be at new lesson time in your virtual classroom.</p>
            <p></p>
            <p>Thank you.</p>
        </div>
    ',

    'body_text' => '
        Hello, {teacher_display_name}

        Student {student_name} has changed permanent schedule with you. (permanently)

        Old schedule: {week_day_old}, {hour_old} ({short_timezone})
        New schedule: {week_day_new}, {hour_new} ({short_timezone})

        You have to be at new lesson time in your virtual classroom.

        Thank you.
    ',
];