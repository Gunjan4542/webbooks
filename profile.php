<?php 
require_once 'core/init.php';

$username = Input::get('user');
$user = new User($username);
if(!$username){
	Redirect::to('index.php');
} else if ($user->checkid(Input::get('unique'))) {// checking if the provided unique id and the id that is stored in the database same for the username
	if(!$user-> exists()){
		Redirect::to(404);
	} else {
		$data = $user-> data();
	}
?> 

<h3><?php print escape($data->username);?></h3>
<p>Full name:<?php print escape($data->name); ?></p>

<?php 
} else {
	Redirect::to(502);
}
?>