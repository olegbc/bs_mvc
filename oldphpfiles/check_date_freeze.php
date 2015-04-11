<?php
	require "db.php";
	require "func_lib.php";	
	header('Content-Type: text/html; charset=utf-8');

	if(isset($_POST["id_visit"]) and $_POST["id_visit"]!="" and 
		isset($_POST["date_of_visit"]) and $_POST["date_of_visit"]!=""and 
		isset($_POST["fio_visit"]) and $_POST["fio_visit"]!=""){


