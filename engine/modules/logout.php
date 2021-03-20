<?php	
	if($_GET['exit'] == "all"){
		$db->remove_token_all($_SESSION['user']['id'], $_COOKIE['user_token']);
	}
	else{
		setcookie('user_token', '', 0, '/');
		setcookie( session_name(), "", 0, '/');
		session_unset();
		session_destroy();
		$db->remove_token($_COOKIE['user_token']);		
	}
	header('Location: /');
	die();
?>