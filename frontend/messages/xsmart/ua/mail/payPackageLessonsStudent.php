<?php
return [
    'subject' => "Ви купили пакет уроків.",

    'body_html' => '
        <div>
            <p>Вiтаю, {student_name}.</p>
            <p></p>
            <p>Ви успішно придбали пакет уроків з вчителем {teacher_display_name}</p>
            <p>Тепер вам потрібно скласти свiй розклад з цим вчителем.</p>
            <p></p>
            <p>Сьогоднішній платіж: {order_amount_usd} USD</p>
            <p>Кількість уроків: {lesson_count}</p>
            <p>Налаштувати розклад: <a href="{link_to_set_schedule}">{link_to_set_schedule}</a></p>
            <p></p>
            <p>Дякую!</p>
        </div>
    ',

    'body_text' => '
        Вiтаю, {student_name}.

        Ви успішно придбали пакет уроків з вчителем {teacher_display_name}
        Тепер вам потрібно скласти свiй розклад з цим вчителем.

        Сьогоднішній платіж: {order_amount_usd} USD
        Кількість уроків: {lesson_count}
        Налаштувати розклад: {link_to_set_schedule}

        Дякую!
    ',
];