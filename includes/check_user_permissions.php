<?php

	//echo "LOGGED_IN_USER_GROUP_ID - " . LOGGED_IN_USER_GROUP_ID; exit;
	if (!$db->groupHasAnyRole(LOGGED_IN_USER_GROUP_ID, $permissions))
	{
		//user is not allowed to access page
		$page = SITEPATH."error";
		header("Location: $page"); 
		exit();
	}
	
?>