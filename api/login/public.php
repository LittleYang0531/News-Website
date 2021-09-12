<?php
	require "../config.php";
	require "../function.php";
    header("Content-Type:text/html;charset=utf-8");
    session_start();
	$info=array(
		"code"=>0,
		"message"=>"0",
		"ttl"=>1,
		"data"=>array(
			"public"=>$config["rsa-public-key"],
			"time"=>time(),
		),
	);
	echo ReturnJSON($info);
?>