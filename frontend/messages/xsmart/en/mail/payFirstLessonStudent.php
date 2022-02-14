<?php
return [
    'subject' => "You have bought trial lesson.",

    'body_html' => '
        <div>
            <p>Hello, {student_name}.</p>
            <p></p>
            <p>You have successfully bought trial lesson with teacher {teacher_display_name}</p>
            <p>Teacher will await for you at arranged time in your virtual classroom.</p>
            <p></p>
            <p>Today\'s charge: {order_amount_usd} USD</p>
            <p>Lesson time: {lesson_date}</p>
            <p>Virtual classroom:  <a href="{link_to_the_lesson}">{link_to_the_lesson}</a></p>
            <p></p>
            <p>Thank you!</p>
        </div>
    ',

    'body_text' => '
        Hello, {student_name}.

        You have successfully bought trial lesson with teacher {teacher_display_name}
        Teacher will await for you at arranged time in your virtual classroom.

        Today\'s charge: {order_amount_usd} USD
        Lesson time: {lesson_date}
        Virtual classroom:  {link_to_the_lesson}

        Thank you!
    ',
];