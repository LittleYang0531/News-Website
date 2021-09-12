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
	$type=$_GET['type'];
	if (!CheckLogin())
	{
		echo ReturnError(-101,"账号未登录");
		exit;
	}
	if ($type!="article"&&$type!="wiki")
	{
		echo ReturnError(-404,"传递参数错误");
		exit;
	}
	$sql = "SELECT * FROM Star WHERE type='$type' and uid=".$_COOKIE['DedeUserId'];
	$result = mysqli_query($conn, $sql);
	if (!$result)  
	{
		echo ReturnError(-400,"数据库查询错误");
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