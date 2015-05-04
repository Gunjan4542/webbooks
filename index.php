<?php 

require_once 'core/init.php';

if(Session::exists('home')){
	print '<p>'. Session::flash('home') .'</p>';
}
$user = new User();//current user
if($user->isLoggedIn()){
?>
<html>
<p>Hello <a href="profile.php?user=<?php print escape($user->data()->username); ?>&unique=<?php print $user->data()->unique_id;?>"><?php print escape($user->data()->username); ?></a></p>

<ul>
	<li><a href="logout.php">Log out</a></li>
	<li><a href="update.php">Update details</a></li>
	<li><a href="changepassword.php">Change password</a></li>
</ul>
<?php 
if ($user->hasPermission('moderator')){
	print "<p> You are a moderator!</p>";
}
?>

</html>
<?php
}else { 
	print '<P> You need to <a href="login.php">log in</a> or <a href="register.php">register</a></p>';
}
?>