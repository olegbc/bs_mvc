<?php	
	require "db.php";
	require "func_lib.php";
	$teacher = "Сергей";
	$timetable = "ПУ";
	$teacher = "";
	$sql = "SELECT DISTINCT `teacher` FROM `levels`";
	$result = mysql_query($sql) or die(mysql_error());

?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Big Step attendance table</title>
	<link media="all" rel="stylesheet" type="text/css" href="style.css" />
	<script type="text/javascript" src="js/jquery-1.8.3.js"></script>
	<script type="text/javascript" src="js/main.js"></script> 
	<script type="text/javascript" src="js/attendance.js"></script> 
</head>
<body>
	<div class="back_gray"></div>

	<h1>Attendance table</h1>
	<form action="javascript:void(null);" method='post' onsubmit="lgtt_match_fn()" id="lgtt_form">
		<select id="teacher_select" onchange="teacher_calculate();timetable_calculate();" name="teacher_choose">
			<?php while($row = mysql_fetch_row($result)){ ?>
				<option value="<?php echo $row[0]; ?>"><?php echo $row[0]; ?></option>
			<?php } ?>
		</select>
		<input type="submit">
	</form>
	
	<div class="ff"></div>
	<div class="main_form">
			<table width="50%" class="attendance_table default_table" id="attendance_table">
				
				
			</table>
	</div>
</body>
</html>
