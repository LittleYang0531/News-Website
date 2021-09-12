<?php
	require "config.php";
	require "function.php";
	$info=array(
		"code"=>0,
		"message"=>"0",
		"ttl"=>1,
		"data"=>$_SERVER
	);
	echo ReturnJSON($info);
?>















