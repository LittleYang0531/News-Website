<?php
	require "../config.php";
	require "../function.php";
	$id = $_GET['id'];
	header("Content-Type:text/html;charset=utf-8");
	session_start();
	$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
	if(! $conn ){
		echo ReturnError(-500,"无法连接数据库");
		exit;
	}
	$sql = "SELECT * FROM Notice where num=$id";
	$result = mysqli_query($conn, $sql);
	if (!$result||mysqli_num_rows($result)==0)  
	{
		echo ReturnError(-400,"请求错误");
		exit;
	}
	else 
	{
		$row = mysqli_fetch_assoc($result);
		$result1=mysqli_query($conn,"SELECT * FROM Users WHERE UserName='".$row['Author']."'");
		if (!$result1||mysqli_num_rows($result1)==0)  
		{
			echo ReturnError(-400,"请求错误");
			exit;
		}
		$row1=mysqli_fetch_assoc($result1);
		$author=array(
			"uid"=>$row1['UserId'],
			"name"=>$row1['UserName'],
			"birth"=>"****-**-**",
			'school'=>$row1['School'],
			"class"=>$row1['Class'],
			"grade"=>$row1['Grade'],
			"title"=>$row1['Title'],
			"mail"=>"**********",
			"QQ"=>"**********",
			"bili"=>($row1['Bilibili']=="")?"":"//space.bilibili.com/".$row1['Bilibili']."/",
			"header"=>$config["api-server-address"]."/".$config['account-data']."/".$row1['UserName']."/header.jpg",
			"background"=>$config["api-server-address"]."/".$config['account-data']."/".$row1['UserName']."/background.jpg",
			"authority"=>$row1['Authority'],
		);
		$information=array(
			"code"=>0,
			"message"=>"0",
			"ttl"=>1,
			"data"=>array(
				"id"=>$id,
				"name"=>$row['Title'],
				"author"=>$author,
				"release"=>$row['ReleaseTime'],
				'view'=>$row['WatchNum'],
				'link'=>$config["api-server-address"]."/".$config['notice-data']."/$id.html"
			)
		);
		echo ReturnJSON($information);
	}
?>







