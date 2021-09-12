<?php
	require "../config.php";
	require "../function.php";
	$uid=$_GET['uid'];
	header("Content-Type:text/html;charset=utf-8");
	session_start();
	if (!CheckLogin()) 
	{
		$information=array(
			"code"=>0,
			"message"=>0,
			"ttl"=>0,
			"data"=>array(
				"toid"=>$uid,
				"follow"=>0
			),
		);
		echo ReturnJSON($information);
		exit;
	}
	$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
	if(! $conn ){
		echo ReturnError(-500,"无法连接数据库");
		exit;
	}
	$sql = "SELECT * FROM Users where UserId=$uid";
	$result = mysqli_query($conn, $sql);
	if (!$result)  
	{
		echo ReturnError(-400,"数据库查询错误");
		exit;
	}
	if (mysqli_num_rows($result)==0)
	{
		echo ReturnError(-404,"查无此人");
		exit;
	}
	$row = mysqli_fetch_assoc($result);
	$result=mysqli_query($conn,"SELECT * FROM Relation WHERE fromid=".$_COOKIE["DedeUserId"]." and toid=$uid");
	if (!$result)  
	{
		echo ReturnError(-400,"数据库查询错误");
		exit;
	}
	$information=array(
		"code"=>0,
		"message"=>0,
		"ttl"=>0,
		"data"=>array(
			"toid"=>$uid,
			"follow"=>mysqli_num_rows($result)?1:0
		),
	);
	echo ReturnJSON($information);
?>