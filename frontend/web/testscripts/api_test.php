<?php

$newLead = '{
    "action": "newLead",
    "data": {
        "lead_name":"Lead_2",
        "lead_email":"lead2@gmail.com",
        "lead_phone":"667676767"
    }
}';

$lessonStatistic = '{
    "action": "lessonStatistic",
    "data": {
        "room_hash": "9dd1465afe8a89056db9b088600335d3",
        "notes_played": 8,
        "notes_hit": 5,
        "notes_close": 0,
        "notes_lowest": "C3",
        "notes_highest": "C4"
    }
}';

$str = $lessonStatistic;
$url = "https://smartsing-member.frontend/api/";

$headers = [
    "Accept-Language: en",
    //"Accept: application/json, text/javascript, */*; q=0.01",
    //"Accept-Encoding: gzip, deflate",
    //"Accept-Language: ru,uk;q=0.9,en-US;q=0.8,en;q=0…fr;q=0.4,de-DE;q=0.3,de;q=0.1",
    //"User-Agent: Mozilla/5.0 (X11; Ubuntu; Linu…) Gecko/20100101 Firefox/65.0",
];
$ch = curl_init();    // initialize curl handle
curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
curl_setopt($ch, CURLOPT_TIMEOUT, 30); // times out after 40s
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $str); // add POST fields
$answer = curl_exec($ch);// run the whole process
curl_close($ch);

echo($answer);
