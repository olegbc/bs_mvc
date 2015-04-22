<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Freeze data</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link media="all" rel="stylesheet" type="text/css" href="css/style.css" />
    <script type="text/javascript" src="js/jquery-1.8.3.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
    <script type="text/javascript" src="js/freeze_cabinet.js"></script>
</head>
<body>
<div>
    <div class="menu">
        <button class="btn_main">main</button>
        <button class="btn_attendance_table">attendance_table</button>
        <button class="btn_level_culculation">level_culculation</button>
        <button class="btn_number_of_students">number_of_students</button>
        <button class="btn_amount_of_money">amount_of_money</button>
    </div>
</div>
<?php include 'application/views/'.$content_view; ?>
</body>
</html>