<?php
	require "../config.php";
	require "../".$config["function-path"];
	header("Content-Type:text/html;charset=utf-8");
	session_start();
	$state=CheckLogin();
	if (!$state)
	{
		echo ReturnError(-101,"请先登录");
		exit;
	}
	$RealName=trim($_POST['realname']);
	$School=trim($_POST['school']);
	$Grade=trim($_POST['grade']);
	$Class=trim($_POST['class']);
	$QQ=trim($_POST['qq']);
	$Bilibili=trim($_POST['bilibili']);
	$Birth=trim($_POST['birth']);
	$Birth=strtotime($Birth);
	$sign=trim($_POST['sign']);
	$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
	if(! $conn ){die('Could not connect: ' . mysqli_error());}
	$sql="UPDATE Users set RealName='".$RealName."' where UserId=".$_COOKIE["DedeUserId"];
	if ($conn->query($sql) === TRUE) ;
	else 
	{
		echo ReturnError(-400,"数据库执行错误");
		exit;
	}
	$sql="UPDATE Users set School='".$School."' where UserId=".$_COOKIE["DedeUserId"];
	if ($conn->query($sql) === TRUE) ;
	else 
	{
		echo ReturnError(-400,"数据库执行错误");
		exit;
	}
	$sql="UPDATE Users set Grade='".$Grade."' where UserId=".$_COOKIE["DedeUserId"];
	if ($conn->query($sql) === TRUE) ;
	else 
	{
		echo ReturnError(-400,"数据库执行错误");
		exit;
	}
	$sql="UPDATE Users set Class='".$Class."' where UserId=".$_COOKIE["DedeUserId"];
	if ($conn->query($sql) === TRUE) ;
	else 
	{
		echo ReturnError(-400,"数据库执行错误");
		exit;
	}
	$sql="UPDATE Users set QQ='".$QQ."' where UserId=".$_COOKIE["DedeUserId"];
	if ($conn->query($sql) === TRUE) ;
	else 
	{
		echo ReturnError(-400,"数据库执行错误");
		exit;
	}
	$sql="UPDATE Users set Bilibili='".$Bilibili."' where UserId=".$_COOKIE["DedeUserId"];
	if ($conn->query($sql) === TRUE) ;
	else 
	{
		echo ReturnError(-400,"数据库执行错误");
		exit;
	}
	$sql="UPDATE Users set Birth='".$Birth."' where UserId=".$_COOKIE["DedeUserId"];
	if ($conn->query($sql) === TRUE) ;
	else 
	{
		echo ReturnError(-400,"数据库执行错误");
		exit;
	}
	$sql="UPDATE Users set sign='".$sign."' where UserId=".$_COOKIE["DedeUserId"];
	if ($conn->query($sql) === TRUE) ;
	else 
	{
		echo ReturnError(-400,"数据库执行错误");
		exit;
	}
	$info=array(
		"code"=>0,
		"message"=>"",
		"data"=>array(
			"realname"=>$RealName,
			"school"=>$School,
			"grade"=>$Grade,
			"class"=>$Class,
			"QQ"=>$QQ,
			"bili"=>$bilibili,
			"birth"=>$Birth,
			"sign"=>$sign,
		),
		"ttl"=>0,
	);
	echo ReturnJSON($info);
	exit;
?>