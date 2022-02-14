<?php
return [
    'subject' => "Приближается время вашего урока",

    'body_html' => '
        <div class="verify-email">
            <p>Здравствуйте, {teacher_display_name}.</p>
            <p></p>
            <p>Ваш урок с учеником {student_name} начнется через 5 минут.</p>
            <p></p>
            <p>Lesson time: {lesson_date}</p>
            <p>Virtual classroom: <a href="{link_to_the_lesson}">{link_to_the_lesson}</a></p>
            <p></p>
            <p>Спасибо!</p>
        </div>
    ',

    'body_text' => '
        Здравствуйте, {teacher_display_name}.

        Ваш урок с учеником {student_name} начнется через 5 минут.

        Время урока: {lesson_date}
        Виртуальный класс: {link_to_the_lesson}

        Спасибо!
    ',
];