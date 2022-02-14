<?php
return [
    'subject' => "Your lesson is coming",

    'body_html' => '
        <div class="verify-email">
            <p>Hello, {student_name}.</p>
            <p></p>
            <p>You lesson with teacher {teacher_display_name} will be started in a 5 minutes.</p>
            <p></p>
            <p>Lesson time: {lesson_date}</p>
            <p>Virtual classroom: <a href="{link_to_the_lesson}">{link_to_the_lesson}</a></p>
            <p></p>
            <p>Thank you!</p>
        </div>
    ',

    'body_text' => '
        Hello, {student_name}.

        You lesson with teacher {teacher_display_name} will be started in a 5 minutes.

        Lesson time: {lesson_date}
        Virtual classroom: {link_to_the_lesson}

        Thank you!
    ',
];