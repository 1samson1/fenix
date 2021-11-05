<?php	
	if(isset($_GET['exit']) and $_GET['exit'] == "all"){
		$db->table('user_tokens')
			->where('user_id', '=', Store::get('USER.id'))
			->where('token', '!=', $_COOKIE['user_token'])
			->delete();
	}
	else{
		setcookie('user_token', '', 0, '/');
		if(isset($_COOKIE['user_token'])){
			$db->table('user_tokens')
				->where('token', '=', $_COOKIE['user_token'])
				->delete();
		}
	}
	header('Location: /');
	die();
?>