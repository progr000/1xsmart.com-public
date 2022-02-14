<?php
return [
    'subject' => "You have bought lessons packet.",

    'body_html' => '
        <div>
            <p>Hello, {student_name}.</p>
            <p></p>
            <p>You have successfully bought lessons packet with teacher {teacher_display_name}</p>
            <p>Now you need to set up your schedule with this teacher.</p>
            <p></p>
            <p>Today\'s charge: {order_amount_usd} USD</p>
            <p>Lesson count: {lesson_count}</p>
            <p>Set up schedule:  <a href="{link_to_set_schedule}">{link_to_set_schedule}</a></p>
            <p></p>
            <p>Thank you!</p>
        </div>
    ',

    'body_text' => '
        Hello, {student_name}.

        You have successfully bought lessons packet with teacher {teacher_display_name}
        Now you need to set up your schedule with this teacher.

        Today\'s charge: {order_amount_usd} USD
        Lesson count: {lesson_count}
        Set up schedule:  {link_to_set_schedule}

        Thank you!
    ',
];