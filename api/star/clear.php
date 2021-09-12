<?php
	require "../config.php";
	require "../function.php";
	header("Content-Type:text/html;charset=utf-8");
	session_start();
	if ($_SERVER['REQUEST_METHOD']!='POST')
	{
		echo ReturnError(-405,"调用方法错误");
		exit;
	}
	$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
	if(! $conn ){
		echo ReturnError(-500,"无法连接数据库");
		exit;
	}
	$type=trim($_POST['type']);
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
	$sql = "DELETE FROM Star WHERE type='$type' and uid=".$_COOKIE['DedeUserId'];
	if (!$conn->query($sql))  
	{
		echo ReturnError(-400,"数据库执行错误");
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