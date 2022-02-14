<?php
return [
    'subject' => "Student has bought trial lesson with you.",

    'body_html' => '
        <div>
            <p>Hello, {teacher_display_name}</p>
            <p></p>
            <p>Student {student_name} has bought trial lesson with you.</p>
            <p>Be on time in your virtual classroom and do your best at this lesson.</p>
            <p></p>
            <p>Lesson time: {lesson_date}</p>
            <p>Virtual classroom:  <a href="{link_to_the_lesson}">{link_to_the_lesson}</a></p>
            <p></p>
            <p>Thank you!</p>
        </div>
    ',

    'body_text' => '
        Hello, {teacher_display_name}

        Student {student_name} has bought trial lesson with you.
        Be on time in your virtual classroom and do your best at this lesson.

        Lesson time: {lesson_date}
        Virtual classroom:  {link_to_the_lesson}

        Thank you!
    ',
];