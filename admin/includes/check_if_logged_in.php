<?php
	if (!IS_USER_LOGGED_IN) {
		//user is not logged in redirect to login page
		$page = LOGIN_URL;
		header("Location: $page"); 
		exit();
	}
?>