<?php
class Hash{
	public static function make($string, $salt=''){ //makes a password hash with the passed password string and salt
		return hash('sha256', $string . $salt);
	}
	
	public static function salt(){
		$salt =  "adnbcbvksdfjf";//here i need to user mcrypt function
		return $salt;
	}
	
	public static function unique(){//maeks a unique id
		return self::make(uniqid());
	}
} 
?>