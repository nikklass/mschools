<?php
	if (!IS_USER_LOGGED_IN) {
		//user is not logged in redirect to login page
		$page = SITEPATH."admin/login";
		header("Location: $page"); 
		exit();
	}
?>