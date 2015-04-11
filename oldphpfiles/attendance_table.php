<?php	
	require "db.php";
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Big Step attendance table</title>
	<link media="all" rel="stylesheet" type="text/css" href="style.css" />
	<script type="text/javascript" src="js/jquery-1.8.3.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
	<script src="js/jquery-ui.js"></script>
	<link rel="stylesheet" href="css/jquery-ui.css">
</head>
<body>
	<div class="back_gray"></div>
	<h1>Attendance table</h1>
	<form name="lgtt_match" action="javascript:void(null);" method="post" onsubmit="lgtt_match_fn2()" id="lgtt_form">
		<input type="text" name="level_choose" id="level_choose" /><label for="level_choose" >Уровень</label><Br>
		<input type="text" name="group_choose" id="group_choose" /><label for="group_choose" >Группа</label><Br>
		<input type="text" name="timetable_choose"  id="timetable_choose" /><label for="timetable_choose" >Расписание</label><Br>
		<input type="submit" />
	</form>
	<div class="main_form">
		<form name="save2table" action="javascript:void(null);" method="post" onsubmit="call()" id="form">
			<table width="50%" class="attendance_table">
				<tr>
					<th class="attendance_name_th"><div id="attendance_table_name">Имя</div></th>					
				<?php
					
					$sql = "SELECT * FROM `levels` WHERE level = 8"  ;
					$result = mysql_query($sql)	or die(mysql_error());
					while ($row = mysql_fetch_row($result)){
					
				?>
					<?php for($i=2;$i<=22;$i++){echo "<th class='attendance_th'><div class='rotateText'>".$row[$i]."</div></th>";} ?>
					<?php } ?>
				</tr>
				<tr>
				<?php  
					$sql = "SELECT `fio` FROM `main` WHERE id = 1";
					$result = mysql_query($sql)	or die(mysql_error());
					while ($row = mysql_fetch_row($result)){
					?>
					<td><?php echo $row[0]; ?></td>
					<?php }		
					$visit_array = array();
					$sql = "SELECT `date_of_visit` FROM `attendance` WHERE id_visit=1";
					$result = mysql_query($sql)	or die(mysql_error());
					$u=0;
					while ($row = mysql_fetch_row($result)){
						$visit_array[$u] = $row[0];
						$u++;
					}
					for($i=1;$i<=21;$i++){
						$match = "";
						$sql2 = "SELECT sd_".$i." FROM `levels` WHERE id = 1";
						$result2 = mysql_query($sql2) or die(mysql_error());
						while ($row2 = mysql_fetch_row($result2)){								
							$sd = $row2[0];
							foreach ($visit_array as $value) {
							if($sd == $value){ $match = $value;	
						?>
							<?php } ?>
						<?php } ?>						
				<?php 	} ?>
						<td><input type="text" name="sd_<?php echo $i; ?>" size="6" onblur="" class=""  value="<?php echo $match; ?>"></td>
			<?php 	} ?>
				</tr>
			</table>
		</form>
	</div>	
</body>
</html>
