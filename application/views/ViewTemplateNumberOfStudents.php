<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Big Step Number of students</title>

    <link rel="stylesheet" media="all" type="text/css" href="css/style.css" />
    <link rel="stylesheet" media="all" type="text/css" href="css/datepicker.css" />
    <link rel="stylesheet" media="screen" type="text/css" href="css/layout.css" />

    <script type="text/javascript" src="js/jquery-1.8.3.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
    <script type="text/javascript" src="js/datepicker.js"></script>
    <script type="text/javascript" src="js/eye.js"></script>
    <script type="text/javascript" src="js/utils.js"></script>
    <script type="text/javascript" src="js/layout.js?ver=1.0.2"></script>

    <script type="text/javascript" src="js/jqBarGraph.js"></script>

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
<div class="table_title"><h1>Number of students calculation</h1></div>

<?php include 'application/views/'.$content_view; ?>

</body>
</html>