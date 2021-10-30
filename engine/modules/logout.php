<?php	
	if(isset($_GET['exit']) and $_GET['exit'] == "all"){
		$db->remove_token_all(Store::get('USER.id'), $_COOKIE['user_token']);
	}
	else{
		setcookie('user_token', '', 0, '/');
		!isset($_COOKIE['user_token']) ?: $db->remove_token($_COOKIE['user_token']);
	}
	header('Location: /');
	die();
?>