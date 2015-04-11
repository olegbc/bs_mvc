<?php

	function getOrders() {

		// Получение заказчиков из log-файла 
		$orders = file(ORDERS_LOG);
		
		$allorders = array();
		
		foreach ($orders as $order) {
			list($name, $email, $phone, $address, $customer, $date) = explode("|", $order);
			
			$orderinfo = array();
			
			$orderinfo["name"] = $name;	
			$orderinfo["email"] = $email;	
			$orderinfo["phone"] = $phone;	
			$orderinfo["address"] = $address;	
			$orderinfo["customer"] = $customer;	
			$orderinfo["date"] = $date;	
			// Товары из заказов:
			$sql = "SELECT * FROM orders 
				WHERE customer='".$orderinfo["customer"]."' AND datetime=".$orderinfo["date"];
			$result = mysql_query($sql) or die(mysql_error());
	
			$orderinfo["goods"] = $result;
			$allorders[] = $orderinfo;

		}
		return $allorders;
	}
?>