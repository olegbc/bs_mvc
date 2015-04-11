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
	<script type="text/javascript" src="js/bad_days.js"></script> 
	<title>Bad Days</title>
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
	<div class="table_title">
		<h1>Bad Days</h1>
	</div>
	<div class="combination_all_bad_days">
		<div class="past_combinations"></div>
		<div class="line_1"><hr></div>
		<div class="peresent_combinations"></div>
		<div class="line_2"><hr></div>
		<div class="future_combinations"></div>
	</div>
	<div class="bad_days_calendar_monthes_wrapper">
		<div class="bad_days_calendar_monthes">
			<div class="left_arrow"></div>
			<div class="bad_days_calendar_1">
				<table>
					<td class="ui-datepicker-today" data-handler="selectDay" data-event="click" data-month="10" data-year="2014"><a class="ui-state-default ui-state-highlight ui-state-hover" href="#">13</a></td>
				</table>
			</div>
			<div class="bad_days_calendar_2">
				<table></table>
			</div>
			<div class="bad_days_calendar_3">
				<table></table>
			</div>
			<div class="bad_days_calendar_4">
				<table></table>
			</div>
			<div class="right_arrow"></div>
		</div>
	</div>
</body>
