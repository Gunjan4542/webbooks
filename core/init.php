<?php 
	
	session_start();
	ini_set ('display_errors', 1);// this is for enableing displaying errors
	
	$GLOBALS['config'] = array(
		'mysql' => array(
			'host' => '127.0.0.1',
			'username' => 'root',
			'password' => 'iamAvishek',
			'db' => 'testsite'				
	),
		'remember' => array(
			'cookie_name' => 'hash',
			'cookie_expiry' => 604800//1 months in seconds			
	),
		'session' => array(
			'session_name' => 'user',
			'token_name' => 'token' 
		)
			
			
	);

	spl_autoload_register( function($class){
		
		require_once 'classes/'.$class.'.php';
		
	});
	
	require_once 'functions/sanitize.php';
	
	if (Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))){
		$hash = Cookie::get(Config::get('remember/cookie_name'));
		$hashCheck = DB::getInstance()->get('users_session', array('hash', '=', $hash));

		if($hashCheck -> count()){
			$user = new User($hashCheck->first()->user_id);
			$user->login();
		}
	}
	
?>
