<?php	
	require "db.php";
	
	//var_dump($_POST);
	
 	if(isset($_POST["fio_add"]) and $_POST["fio_add"]!=""){
	//	var_dump($_POST);
	//	echo $_POST["fio_add"];
 		$sql="SELECT `id` FROM `main` WHERE `fio`='".$_POST["fio_add"]."'";
 		$result=mysql_query($sql) or die(mysql_error());
 		if(mysql_num_rows($result)){echo "bad";}else{
			$sql = "INSERT INTO main (fio) VALUES('".$_POST['fio_add']."'".")";
		//	echo	$sql;
			$result = mysql_query($sql)	or die(mysql_error());
			$id = mysql_insert_id();
			
			$sql = "SELECT * FROM main WHERE id=".$id;
			$result = mysql_query($sql)	or die(mysql_error());
			while ($row = mysql_fetch_row($result)){
			echo <<<HTML
<tr class='tr_$row[0]'>
	<td><input type='text' name='id' value='$row[0]' /></td>
	<td><input type='text' name='fio' onchange='call2(this.value,$row[0],'fio')'>
	<a href="http://test.ru/bigstep/person.php?person=$row[0]" target="_self">$row[1]</a>
	</td>
	<td><input type='text' name='dog_num' size='5'  onchange='call2(this.value,$row[0],'dog_num')' value=$row[2]></td>
	<td><p class='fillInNameAndIdInForm' onclick='fillInNameAndIdInForm($row[0]);showDivWrapperOfFormShowGrayBackgroundResetForm();'>Создать уровень</p></td>
	<td><p class='take' onclick='take($row[0]);takedown();'>Принять проплату</p></td>
	<td><p class='del' onclick='del($row[0])'>Удалить</p></td>
</tr>
HTML;
			}
	}
}