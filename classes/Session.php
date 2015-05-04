<?php 
class Session{
	//makes  a new session
	public static function put($name, $value){
		return $_SESSION[$name] = $value;
	}
	
	//checks if a session already exists
	public static function exists($name){
		return (isset($_SESSION[$name])) ? true : false;
	}
	
	public static function delete($name){
		if(self::exists($name)){
			unset($_SESSION[$name]);
		}
	}
	
	public static function get($name){
		return $_SESSION[$name];
	}
	
	//flash a message on the specified page for a special purpous
	public static function flash($name, $string=''){
		if(self::exists($name)){
			$session = self::get($name);
			self::delete($name);
			return $session;
		} else {
			self::put($name, $string);
		}

	}
}

?>