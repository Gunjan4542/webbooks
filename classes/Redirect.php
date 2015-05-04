<?php 
class Redirect{
	public static function to($location = null){
		if($location){
			if(is_numeric($location)){
				switch ($location){
					case 404:
						header('HTTP/1.0 404 Not Found');
						include 'includes/errors/404.php';
						exit();
						break;
					case 502:
						header('HTTP/1.0 502 Forbidden');
						include 'includes/errors/502.php';
						exit();
						break;
				}
			}
			header('Location: '.$location);
		}
	}
}
?>