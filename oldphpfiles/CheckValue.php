<?php 
class CheckValue{
	private static $flag = true;
	public static function check($checkArr){
		foreach($checkArr as $checkValue){
			if($checkValue===""){
				self::$flag = false;
			}
		}
		return self::$flag;
	}
}