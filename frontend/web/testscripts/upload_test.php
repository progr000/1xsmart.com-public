<?php
$room_hash = "2d5ee908004f63b095ec23d634e0b759";
?>
<html>
<head>
    <meta charset="UTF-8"/>
    <title>Test</title>
</head>
<body>
<form method="post"
      enctype="multipart/form-data"
      action="https://smartsing-member.frontend/api/upload-video-lessons">
    <input type="hidden" name="room_hash" value="<?= $room_hash ?>" />
    <input type="file" name="UploadVideoLessonsForm[uploadedFile]" />
    <br /><br />
    <input type="submit" />
</form>
</body>
</html>
