<?php
	require "../config.php";
	require "../function.php";
	header("Content-Type:text/html;charset=utf-8");
	session_start();
	$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
	if(! $conn ){
		echo ReturnError(-500,"无法连接数据库");
		exit;
	}
	$sql = "SELECT * FROM NewsColumn";
	$result = mysqli_query($conn, $sql);
	if (!$result)  
	{
		echo ReturnError(-400,"请求错误");
		exit;
	}
	else 
	{
		$information=array(
			"code"=>0,
			"message"=>"0",
			"ttl"=>1,
			"max"=>mysqli_num_rows($result),
		);
		echo ReturnJSON($information);
	}
?>







