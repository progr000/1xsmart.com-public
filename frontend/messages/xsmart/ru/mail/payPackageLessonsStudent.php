<?php
return [
    'subject' => "Вы купили пакет уроков.",

    'body_html' => '
        <div>
            <p>Здравствуйте, {student_name}.</p>
            <p></p>
            <p>Вы успешно приобрели пакет уроков с учителем {teacher_display_name}</p>
            <p>Теперь Вам нужно составить свое расписание с этим учителем.</p>
            <p></p>
            <p>Сегодняшний платеж: {order_amount_usd} USD</p>
            <p>Количество уроков: {lesson_count}</p>
            <p>Настроить расписание: <a href="{link_to_set_schedule}">{link_to_set_schedule}</a></p>
            <p></p>
            <p>Спасибо!</p>
        </div>
    ',

    'body_text' => '
        Здравствуйте, {student_name}.

        Вы успешно приобрели пакет уроков с учителем {teacher_display_name}
        Теперь Вам нужно составить свое расписание с этим учителем.

        Сегодняшний платеж: {order_amount_usd} USD
        Количество уроков: {lesson_count}
        Настроить расписание: {link_to_set_schedule}

        Спасибо!
    ',
];