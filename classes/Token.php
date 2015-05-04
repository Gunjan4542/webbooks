<?php 
	
	class Token{
		
		public static function generate(){ // generates a new random token 
			return Session::put(Config::get('session/token_name'), md5(uniqid()));
		}
		
		public static function check($token){ // check if a token exists
			$tokenName = Config::get('session/token_name');
			
			if(Session::exists($tokenName) && $token === Session::get($tokenName))
			{
				Session::delete($tokenName);//if token exists then return true and delete the token
				return true;
			}else 
				return false;
		}
	}
	
?>