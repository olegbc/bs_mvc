<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Big Step attendance table</title>
    <link media="all" rel="stylesheet" type="text/css" href="css/style.css" />
    <script type="text/javascript" src="js/jquery-1.8.3.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
    <script type="text/javascript" src="js/attendance.js"></script>
    <script src="js/jquery-ui.js"></script>
    <link rel="stylesheet" href="css/jquery-ui.css">
</head>
<body>
<div>
    <div class="menu">
        <button class="btn_main">main</button>
        <button class="btn_attendance_table">attendance_table</button>
        <button class="btn_level_culculation">level_culculation</button>
        <button class="btn_number_of_students">number_of_students</button>
        <button class="btn_amount_of_money">amount_of_money</button>
        <button class="btn_spu">spu</button>
        <button class="btn_bad_days">bad_days</button>
    </div>
</div>
<?php include 'application/views/'.$content_view; ?>
</body>
</html>