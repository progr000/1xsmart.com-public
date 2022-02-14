<?php
return [
    'subject' => "Приближается время вашего урока",

    'body_html' => '
        <div class="verify-email">
            <p>Здравствуйте, {student_name}.</p>
            <p></p>
            <p>You lesson with teacher {teacher_display_name} will be started in a 5 minutes.</p>
            <p></p>
            <p>Lesson time: {lesson_date}</p>
            <p>Virtual classroom: <a href="{link_to_the_lesson}">{link_to_the_lesson}</a></p>
            <p></p>
            <p>Спасибо!</p>
        </div>
    ',

    'body_text' => '
        Здравствуйте, {student_name}.

        Ваш урок с учителем {teacher_display_name} начнется через 5 минут.

        Время урока: {lesson_date}
        Виртуальный класс: {link_to_the_lesson}

        Спасибо!
    ',
];