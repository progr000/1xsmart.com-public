<?php

/*
'{"first_lesson_xsmart":true,"timeline_timestamp":1629680400,"student_user_id":26,"teacher_user_id":5}'


  ===== 2021-08-23, 08:53:15 ====
'{"Status":"AUTHORIZED","Token":"698f9cca66dd180e3dbc7d064f799aa4cd895ca7b87fb28eb9a8be83c170f2b9","OrderId":"547869","TerminalKey":"1611832698743DEMO","Success":true,"PaymentId":692929136,"ErrorCode":"0","Amount":84000,"CardId":63054799,"Pan":"430000******0777","ExpDate":"1122"}'
 */

$data = '{"TerminalKey":"1611832698743DEMO","OrderId":"548033","Success":true,"Status":"CONFIRMED","PaymentId":445769996,"ErrorCode":"0","Amount":25000,"CardId":63054799,"Pan":"430000******0777","ExpDate":"1122","Token":"a1bc4227909dc54a8eba35f8078bb4917785ddff3412a0b5dd5bd293bb03d577"}';



$ch = curl_init();    // initialize curl handle
curl_setopt($ch, CURLOPT_URL, 'https://1xsmart.com.my/tinkoff'); // set url to post to
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
curl_setopt($ch, CURLOPT_TIMEOUT, 30); // times out after 40s
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // add POST fields
$answer = curl_exec($ch);// run the whole process
curl_close($ch);

echo($answer);
