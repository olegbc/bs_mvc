<?php

	define("DB_HOST","test.ru");
	define("DB_LOGIN","root");
	define("DB_PASSWORD","pass@word1");	
	define("DB_NAME","bigstep");				
	
	mysql_connect(DB_HOST, DB_LOGIN, DB_PASSWORD)or die(mysql_error());	
	mysql_select_db(DB_NAME) or die(mysql_error());
	
	

/*	
$sql="SELECT count(*) FROM basket WHERE customer= '".session_id()."'";	
$result= mysql_query($sql) or die(mysql_error());
$row=mysql_fetch_row($result);
$count=$row[0];
*/	
	
?>