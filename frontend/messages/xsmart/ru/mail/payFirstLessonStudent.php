<?php
return [
    'subject' => "Вы купили пробное занятие.",

    'body_html' => '
        <div>
            <p>Здравствуйте, {student_name}.</p>
            <p></p>
            <p>Вы успешно купили пробный урок с учителем {teacher_display_name}</p>
            <p>Учитель будет ждать Вас в назначенное время в Вашем виртуальном классе.</p>
            <p></p>
            <p>Сегодняшний платеж: {order_amount_usd} USD</p>
            <p>Время урока: {lesson_date}</p>
            <p>Виртуальный класс: <a href="{link_to_the_lesson}">{link_to_the_lesson}</a></p>
            <p></p>
            <p>Спасибо!</p>
        </div>
    ',

    'body_text' => '
        Здравствуйте, {student_name}.

        Вы успешно купили пробный урок с учителем {teacher_display_name}
        Учитель будет ждать Вас в назначенное время в вашем виртуальном классе.

        Сегодняшний платеж: {order_amount_usd} USD
        Время урока: {lesson_date}
        Виртуальный класс: {link_to_the_lesson}

        Спасибо!
    ',
];