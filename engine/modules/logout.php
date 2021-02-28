<?php
	setcookie('user_token', '', time()-360);
	session_unset();
	session_destroy();
	$db->remove_token($_COOKIE['user_token']);
	header('Location: /');
	die();
?>