<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Big Step level data</title>
	<link  rel="stylesheet" type="text/css" href="style.css" />
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
	<div class="wrapper_level_culc">
		<form name="level_culc" action="javascript:void(null);" method="post" onsubmit="level_culc_fn()" id="level_culc">
			<input type="text" name="level_start_date" id="level_start_date"   /><label for="level_start_date" >Дата начала уровня*</label><Br>
			<input type="text" name="level_choose" id="level_choose"  /><label for="level_choose" >Уровень*</label><Br>
			<input type="text" name="teacher_choose" id="teacher_choose"   /><label for="teacher_choose" >Преподаватель*</label><Br>
			<input type="text" name="timetable_choose" id="timetable_choose"   /><label for="timetable_choose" >Расписание*</label><Br>
			<input type="submit" />
		</form>
	</div>
	<p> * - обязательное для заполнения поле </p>
</body>
</html>