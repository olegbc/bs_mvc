<?php
	require "db.php";
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Big Step</title>
	<link media="all" rel="stylesheet" type="text/css" href="style.css" />
	<script type="text/javascript" src="js/jquery-1.8.3.js"></script>
	<script type="text/javascript" src="js/main.js"></script> 
</head>
<body>
	<div class="back_gray"></div>
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

	<div class="wrp_btn_add"><button class="btn_add">Добавить ученика</button></div>
	<!--     MAIN     -->
	<div class="table_title"><h1>MAIN</h1>
	</div>
	<div class="search_input"><lebel for="search_fio">Поиск студента </lebel><input type="text" id="search_fio" /></div>
<!-- <div class="main_form_wrapper"> -->
	<div class="main_form">
		<form name="save2table" action="javascript:void(null);" method="post" onsubmit="call()" id="form">
			<table  class="main_table default_table">
				<tr>
					<th>id</th>
					<th>fio</th>
					<th>dog_num</th>
					<th>Создать уровень</th>
					<!-- <th>Отметить посещение</th> -->
					<th>Принять проплату</th>
					<th>Удалить</th>
				</tr>
				<?php
					
					$sql = "SELECT * FROM main ORDER BY `id` ASC ";
					$result = mysql_query($sql)	or die(mysql_error());
					while ($row = mysql_fetch_row($result)){
					
				?>
				<tr class="tr_<?php echo $row[0]; ?>">
					<td><input type="text" name="id" value="<?php echo $row[0]; ?>" readonly></td>
					<!-- <td><input type="text" name="fio" size="45" onblur="call2(this.value,<?php echo $row[0]; ?>,'fio')" class="id_<?php echo $row[0]; ?>" value=""><a href ='http://test.ru/bigstep/person.php?person=<?php echo $row[0]; ?>' target='_self'><?php echo $row[1]; ?></a></td> -->
					<td><a href ='/bigstep/person.php?person=<?php echo $row[0]; ?>' target='_self' class="fio_links"><?php echo $row[1]; ?></a></td>
					<td><input type="text" name="dog_num" size="5"  onchange="call2(this.value,<?php echo $row[0]; ?>,'dog_num')" class="id_<?php echo $row[0]; ?>"  value="<?php echo $row[2]; ?>"></td>
					<td><p class="lgtt" onclick="fillInNameAndIdInForm(<?php echo $row[0]; ?>);showDivWrapperOfFormShowGrayBackgroundResetForm();">Создать уровень</p></td>
					<!-- <td><p class="date_of_visit" onclick="dateofvisit(<?php echo $row[0]; ?>);takedown2();">Отметить посещение</p></td> -->
					<td><p class="take" onclick="take(<?php echo $row[0]; ?>);takedown();">Принять проплату</p></td>
					<td><p class="del" onclick="del(<?php echo $row[0].", '".$row[1]."'"; ?>)">Удалить</p></td>
				</tr><?php } ?>
			</table>
		</form>
	</div>
<!-- </div> -->
	<!--     /MAIN     -->

	<!--     LEVELS PERSON     -->
	<div class="wrap_payment_form display_none">
	<div class="table_title"><h1>LEVELS PERSON</h1></div>	
		<div class="payment_form">
			<form name="payment_form" action="javascript:void(null);" method="post" onsubmit="call()" id="form">
				<table width="100%" class="lp_table default_table">
					<tr>
						<th>id</th>
						<th>id_person</th>
						<th>level</th>
						<th>group</th>
						<th>timetable</th>
						<th>start</th>
					</tr>
					<?php
						
						$sql = "SELECT * FROM `levels_person` ORDER BY `id` ASC ";
						$result = mysql_query($sql)	or die(mysql_error());
						while ($row = mysql_fetch_row($result)){
						
					?>
					<tr class="tr_<?php echo $row[0]; ?>">
						<td><input type="text" name="id"  size="5" value="<?php echo $row[0]; ?>" readonly></td>
						<td><input type="text" name="id_person" size="5" class="id_<?php echo $row[0]; ?>"  value="<?php echo $row[1]; ?>" readonly></td>
						<td><input type="text" name="level" size="15"  onchange="lpupdate(this.value,<?php echo $row[0]; ?>,'level')" class="id_<?php echo $row[0]; ?>"  value="<?php echo $row[2]; ?>"></td>
						<td><input type="text" name="group" size="15"  onchange="lpupdate(this.value,<?php echo $row[0]; ?>,'group')" class="id_<?php echo $row[0]; ?>"  value="<?php echo $row[3]; ?>"></td>
						<td><input type="text" name="timetable" size="15"  onchange="lpupdate(this.value,<?php echo $row[0]; ?>,'timetable')" class="id_<?php echo $row[0]; ?>"  value="<?php echo $row[4]; ?>"></td>
						<td><input type="text" name="start" size="15"  onchange="lpupdate(this.value,<?php echo $row[0]; ?>,'start')" class="id_<?php echo $row[0]; ?>"  value="<?php echo $row[5]; ?>"></td>
					</tr><?php } ?>
				</table>
			</form>
		</div>
	</div>
	<!--     /LEVELS PERSON      -->	
	
	<div class="add_form">
	<div class="close_cross"></div>	
		<form name="save2table" action="javascript:void(null);" method="post" onsubmit="add_fn()" id="add_form">
			<!--div class="item"><label for="id">ID:</label> <input id="id" class="add_form_input" type="text" name="id"></div-->
			<div class="item"><label for="fio_add">ФИО:</label> <input id="fio_add" class="add_form_input" type="text" name="fio_add"></div>
			<div class="item last"><input type="submit" class="add_form_btn" value="Сохранить" /></div>
		</form>
	</div>	
	<?php
		$sql = "SELECT DISTINCT `teacher` FROM `levels`";
		$result = mysql_query($sql)	or die(mysql_error());
	?>
	<div class="level_person_form">
	    <div class="close_cross"></div>
		<form name="level_person_form" action="javascript:void(null);" method="post" onsubmit="lgtttodb()" id="level_person_form">
			<div class="item"><label for="id_person">ID:</label> <input id="id_person" class="add_form_input" type="text" name="id_person" readonly></div>
			<div class="item"><label for="fio_person">ФИО:</label> <input id="fio_person" class="add_form_input" type="text" name="fio_person" readonly></div>
			<!-- <div class="item"><label for="teacher">Учитель:</label> <input class="add_form_input" type="text" id="teacher" name="teacher" value="Вера"></div > -->
			<div class="item teacher_soch"><label for="teacher">Учитель:</label>
				<select name="teacher" id="teacher" class="add_form_select" onchange="get_timetable(this.value)">
					<option value="choose_teacher" selected>Выберите учителя</option>
					<?php while ($row = mysql_fetch_row($result)){ ?>
						<option value="<?php echo $row[0]; ?>"><?php echo $row[0]; ?></option>
					<?php } ?>
				</select>
			</div>
			<!-- <div class="item"><label for="timetable">Расписание:</label> <input class="add_form_input" type="text" id="timetable" name="timetable" value="ПВ"></div> -->
			<!-- <div class="item"><label for="level">Уровень:</label> <input class="add_form_input" type="text" id="level" name="level" value="3"></div > -->
			<!-- <div class="item"><label for="level_start">Дата старта уровня:</label> <input class="add_form_input" type="text" id="level_start" name="level_start" value="2013-09-27"></div > -->
			<!-- <div class="item"><label for="person_start">Дата старта ученика:</label> <input class="add_form_input" type="text" id="person_start" name="person_start" value="2013-09-27"></div > -->
			<!-- <div class="item"><label for="person_stop">Дата стоп ученика:</label> <input class="add_form_input" type="text" id="person_stop" name="person_stop" value="2013-10-18"></div > -->
			<div class="item last"><div class="warning">Даты старт/стоп студента, нельзя изменить, если у студента на данном сочетании есть проплаты или посещеня.</div><input type="submit" class="add_form_btn" value="Сохранить" /></div>
		</form>
	</div>
	
	<div class="take_form">
	<div class="close_cross"></div>	
		<form name="take_to_bd" action="javascript:void(null);" method="post" onsubmit="taketobd()" id="take_to_bd_form">
			<div class="item"><label for="id_take">ID:</label> <input id="id_take" class="add_form_input" type="text" name="id_take" readonly></div>
			<div class="item"><label for="fio_take">ФИО:</label> <input id="fio_take" class="add_form_input" type="text" name="fio_take" readonly></div>
			<!-- <div class="item"><label for="date_take">Дата проплаты:</label> <input id="date_take" class="add_form_input" type="text" name="date_take" ></div> -->
			<!-- <div class="item"><label for="combination_take">Учитель, Расписание, Дата старта уровня:</label>  -->
			<!-- <select name="combination_take" id="combination_take" class="add_form_input" ></select></div>  -->
			<div class="item"><label for="take_person_money">Принять:</label> <input class="add_form_input" type="text" id="take_person_money" name="take_person_money"></div>
			<div class="item last"><input type="submit" class="add_form_btn" value="Сохранить" /></div>
		</form>
	</div>
	
	<div class="visit_form">
	<div class="close_cross"></div>	
		<form name="visit_form" action="javascript:void(null);" method="post" onsubmit="dateofvisittobd()" id="visit_form">
		<!--form name="visit_form" action="date_of_visit_to_bd.php" method="post" id="visit_form"-->
			<div class="item"><label for="id_visit">ID:</label> <input id="id_visit" class="add_form_input" type="text" name="id_visit" readonly></div>
			<div class="item"><label for="fio_visit">ФИО:</label> <input id="fio_visit" class="add_form_input" type="text"	name="fio_visit" readonly></div>
			<div class="item"><label for="person_start">Записан на курс с:</label> <input id="person_start" class="add_form_input" type="text"	name="person_start" readonly></div>
			<div class="item"><label for="date_of_visit">Дата посещения:</label> <input class="add_form_input" type="text" id="date_of_visit" name="date_of_visit" value="2014-01-06"></div>
			<div class="item last"><input type="submit" class="add_form_btn" value="Сохранить" /></div>
		</form>
	</div>
	
	<!--     ATTENDANCE     -->
	<div class="wrap_payment_form display_none">
	<div class="table_title"><h1>ATTENDANCE</h1></div>	
		<div class="payment_form">
			<form name="payment_form" action="javascript:void(null);" method="post" onsubmit="call()" id="form">
				<table width="100%" class="visit_table default_table">
					<tr>
						<th>id</th>
						<th>fio_id</th>
						<th>date_of visit</th>
					</tr>				
					<?php
						
						$sql = "SELECT * FROM `attendance` ORDER BY `id` ASC ";
						$result = mysql_query($sql)	or die(mysql_error());
						while ($row = mysql_fetch_row($result)){
						
					?>
					<tr class="tr_<?php echo $row[0]; ?>">
						<td><input type="text" name="id"  size="5" value="<?php echo $row[0]; ?>"></td>
						<td><input type="text" name="fio_id" size="5" onblur="call2(this.value,<?php echo $row[0]; ?>,'fio_id')" class="id_<?php echo $row[0]; ?>"  value="<?php echo $row[1]; ?>"></td>
						<td><input type="text" name="given" size="15"  onchange="call2(this.value,<?php echo $row[0]; ?>,'given')" class="id_<?php echo $row[0]; ?>"  value="<?php echo $row[2]; ?>"></td>
					</tr><?php } ?>
				</table>
			</form>
		</div>
	</div>
	<!--     /ATTENDANCE     -->
	
	<!--     PAYMENT     -->
<!-- 	<div class="wrap_payment_form">
	<div class="table_title"><h1>PAYMENT</h1></div>	
		<div class="payment_form">
			<form name="payment_form" action="javascript:void(null);" method="post" onsubmit="payment_add()" id="payment_form">
				<table width="100%" class="payment_table default_table">
					<tr>
						<th>id</th>
						<th>fio_id</th>
						<th>given</th>
						<th>date</th>
					</tr>				
					<?php
						
						$sql = "SELECT * FROM `payment_has` ORDER BY `date` DESC ";
						$result = mysql_query($sql)	or die(mysql_error());
						while ($row = mysql_fetch_row($result)){
						
					?>
					<tr class="tr_<?php echo $row[0]; ?>">
						<td><input type="text" name="id" value="<?php echo $row[0]; ?>"></td>
						<td><input type="text" name="fio_id"  onblur="call2(this.value,<?php echo $row[0]; ?>,'fio_id')" class="id_<?php echo $row[0]; ?>"  value="<?php echo $row[1]; ?>"></td>
						<td><input type="text" name="given"   onchange="call2(this.value,<?php echo $row[0]; ?>,'given')" class="id_<?php echo $row[0]; ?>"  value="<?php echo $row[2]; ?>"></td>
						<td><?php echo $row[3]; ?></td>
					</tr><?php } ?>
				</table>
			</form>
		</div>
	</div> -->
	<!--     /PAYMENT     -->
	
</body>
</html>