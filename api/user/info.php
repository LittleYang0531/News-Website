<?php
	require "../config.php";
	require "../function.php";
	$uid = $_GET['uid'];
	header("Content-Type:text/html;charset=utf-8");
	session_start();
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
	else 
	{
		$row = mysqli_fetch_assoc($result);
		$result=mysqli_query($conn,"SELECT * FROM Relation WHERE fromid=$uid");
		if (!$result)  
		{
			echo ReturnError(-400,"数据库查询错误");
			exit;
		}
		$follow=mysqli_num_rows($result);
		$result=mysqli_query($conn,"SELECT * FROM Relation WHERE toid=$uid");
		if (!$result)  
		{
			echo ReturnError(-400,"数据库查询错误");
			exit;
		}
		$fans=mysqli_num_rows($result);
		$information=array(
			"code"=>0,
			"message"=>"0",
			"ttl"=>1,
			"data"=>array(
				"uid"=>$uid,
				"name"=>$row['UserName'],
				"birth"=>"****-**-**",
				'school'=>$row['School'],
				"class"=>$row['Class'],
				"grade"=>$row['Grade'],
				"title"=>$row['Title'],
				"mail"=>"**********",
				"QQ"=>"**********",
				"bili"=>($row['Bilibili']=="")?"":"//space.bilibili.com/".$row['Bilibili']."/",
				"header"=>$config["api-server-address"]."/".$config['account-data']."/".$row['UserName']."/header.jpg",
				"background"=>file_exists("../".$config['account-data']."/".$row['UserName']."/background.jpg")?$config["api-server-address"]."/".$config['account-data']."/".$row['UserName']."/background.jpg":$config["default-background"],
				"authority"=>$row['Authority'],
				"sign"=>($row["sign"]!="")?$row["sign"]:"empty",
				"follow"=>$follow,
				"fans"=>$fans,
				"banned"=>$row['banned'],
			)
		);
		echo ReturnJSON($information);
	}
?>







