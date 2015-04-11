<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Big Step attendance table</title>
	<link media="all" rel="stylesheet" type="text/css" href="style.css" />
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
			<button class="btn_bad_days">bad_days</button>
		</div>
	</div>

	<form action="javascript:void(null)" id='lgtt_form' method='post'>
		<input type='hidden' name='teacher_choose' />
		<input type='hidden' name='timetable_choose' />
		<input type='hidden' name='level_start_choose' />
	</form>
	<div class="back_gray"></div>
	<div class="table_title"><h1>Attendance table</h1></div>
	<div class="combination_all">
		<div class="past_combinations"></div>
		<div class="line_1"><hr></div>
		<div class="peresent_combinations"></div>
		<div class="line_2"><hr></div>
		<div class="future_combinations"></div>
	</div>
		
	<div class="wrapper_att_table">
		<div class="att_table att_page">
			<table width="50%" class="attendance_table default_table" id="attendance_table"></table>
		</div>
	</div>
</body>
</html>


