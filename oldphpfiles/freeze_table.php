<?php 
	require "db.php"; 
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link media="all" rel="stylesheet" type="text/css" href="style.css" />
	<script type="text/javascript" src="js/jquery-1.8.3.js"></script>
	<script type="text/javascript" src="js/main.js"></script> 
	<script type="text/javascript" src="js/freeze_cabinet.js"></script> 
	<title>Freeze data</title>
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
	<div class="table_title"><h1>Заморозка</h1></div>
	<div id="attendance_table"></div>
</body>
</html>