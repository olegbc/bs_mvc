<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Big Step level data</title>
    <link  rel="stylesheet" type="text/css" href="css/style.css" />
    <script type="text/javascript" src="js/jquery-1.8.3.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
    <script type="text/javascript" src="js/level_calc.js"></script>
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
        <button class="btn_bad_days">bad_days</button>
    </div>
</div>
<div class="table_title"><h1>Level calculation</h1></div>
<?php include 'application/views/'.$content_view; ?>
</body>
</html>