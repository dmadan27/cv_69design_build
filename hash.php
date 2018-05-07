<?php
	
	$hash = password_hash("abcd",PASSWORD_BCRYPT);

	echo $hash;
?>