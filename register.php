<!DOCTYPE form PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Register</title>
</head>
<body>
	<p>
<?php
require_once 'core/init.php';

if (Input::exists ()) {
	if (Token::check ( Input::get ( 'token' ) )) {
		
		$validate = new Validate ();
		$validation = $validate->check ( $_POST, array (
				'username' => array (
						'required' => true,
						'min' => 2,
						'max' => 20,
						'unique' => 'users' 
				),
				'password' => array (
						'required' => true,
						'min' => 6,
						'max' => 20 
				),
				'cpassword' => array (
						'required' => true,
						'matches' => 'password' 
				),
				'name' => array (
						'required' => true,
						'min' => 2,
						'max' => 50 
				) 
		) );
		
		if ($validate->passed ()) {
			header ( 'location:index.php' );
			$user = new User ();
			
			$salt = Hash::salt ();
			
			try {
				$user->create ( array (
						'username' => Input::get ( 'username' ),
						'password' => Hash::make ( Input::get ( 'password' ), $salt ),
						'salt' => $salt,
						'name' => Input::get ( 'name' ),
						'joined' => date ( 'Y-m-d H:i:s' ),
						'groups' => 1,
						'unique_id' => md5 ( uniqid () )  // it is a unique id that is used for stopping a hacker to view another users profile
								) );
				
				Session::flash ( 'home', 'You have been registered successfully' );
				Redirect::to ( 'index.php' );
			} catch ( Exception $e ) {
				die ( $e->getMessage () );
			}
		} else {
			foreach ( $validate->errors () as $error ) {
				print $error;
			}
		}
	}
}
?>
</p>

	<form action="" method="post">
		<div class="field">
			<label for="username">Username</label> <input type="text"
				name="username"
				value="&lt;?php print Input::get('username');  ?&gt;" /><br /> <label
				for="password">Password</label> <input type="password"
				name="password" /><br /> <label for="cpassword">Confirm Password</label>
			<input type="password" name="cpassword" /><br /> <label for="name">Your
				Name</label> <input type="text" name="name"
				value="&lt;?php Print Input::get('name'); ?&gt;" /><br /> <input
				type="hidden" name="token"
				value="&lt;?php  print Token::generate(); ?&gt;" /><input
				type="submit" value="Register" />

		</div>
	</form>

</body>
</html>

