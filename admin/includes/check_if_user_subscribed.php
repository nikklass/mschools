<?php
	if (!USER_SUBSCRIBED) {
		//user is not logged in redirect to login page
		$page = SITEPATH."my-subscriptions";
		header("Location: $page"); 
		exit();
	}
?>