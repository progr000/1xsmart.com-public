<?php
return [
    'subject' => "Ви купили пробне заняття.",

    'body_html' => '
        <div>
            <p>Вiтвю, {student_name}.</p>
            <p></p>
            <p>Ви успішно купили пробний урок із учителем {teacher_display_name}</p>
            <p>Вчитель чекатиме вас у призначений час у вашому віртуальному класі.</p>
            <p></p>
            <p>Сьогоднішній платіж: {order_amount_usd} USD</p>
            <p>Час уроку: {lesson_date}</p>
            <p>Віртуальний клас: <a href="{link_to_the_lesson}">{link_to_the_lesson}</a></p>
            <p></p>
            <p>Дякую!</p>
        </div>
    ',

    'body_text' => '
        Вiтаю, {student_name}.

        Ви успішно купили пробний урок із учителем {teacher_display_name}
        Вчитель чекатиме вас у призначений час у вашому віртуальному класі.

        Сьогоднішній платіж: {order_amount_usd} USD
        Час уроку: {lesson_date}
        Віртуальний клас: {link_to_the_lesson}

        Дякую!
    ',
];