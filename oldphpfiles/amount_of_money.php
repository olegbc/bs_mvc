<?php	
	require "db.php";
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Big Step Number of students</title>
	
	<link rel="stylesheet" media="all" type="text/css" href="style.css" />
	<link rel="stylesheet" media="all" type="text/css" href="css/datepicker.css" />
	<link rel="stylesheet" media="screen" type="text/css" href="css/layout.css" />
	
	<script type="text/javascript" src="js/jquery-1.8.3.js"></script>
	<script type="text/javascript" src="js/main.js"></script> 	
	<script type="text/javascript" src="js/datepicker.js"></script>
	<script type="text/javascript" src="js/eye.js"></script>
	<script type="text/javascript" src="js/utils.js"></script>
	<script type="text/javascript" src="js/layout.js?ver=1.0.2"></script>
	
	<script type="text/javascript" src="js/jqBarGraph2.js"></script>
	
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
	<div class="table_title"><h1>Amount of money</h1></div>
<div class="wrapper_calendar">
<div class="calendar">
	<form name="datepicker" action="javascript:void(null);" method="post" onsubmit="amount_of_money_fn()" id="datepicker">
		<div id="widgetField">
			<span></span>
			<a href="#">Select date range</a>
		</div>
		<div id="widgetCalendar" style="display:block;height: 151px;position: static;">
		</div>
		<input type="submit" />
	</form>
</div>
</div>
<div id="stackedGraph_wrapper">
	<div id="Graph_money"></div>
</div>

</body>
</html>