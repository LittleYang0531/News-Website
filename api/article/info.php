<?php
	require "../config.php";
	require "../function.php";
	$column = $_GET['column'];
	$id = $_GET['id'];
	header("Content-Type:text/html;charset=utf-8");
	session_start();
	$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
	if(! $conn ){
		echo ReturnError(-500,"无法连接数据库");
		exit;
	}
	$sql = "SELECT * FROM Article where ColumnNum=$column and num=$id";
	$result = mysqli_query($conn, $sql);
	if (!$result)  
	{
		echo ReturnError(-400,"请求错误");
		exit;
	}
	if (mysqli_num_rows($result)==0)
	{
		echo ReturnError(-404,"传递参数错误");
		exit;
	}
	else 
	{
		$row = mysqli_fetch_assoc($result);
		$sql = "SELECT * FROM LikeData where type='article' and id1=$column and id2=$id";
		$result = mysqli_query($conn, $sql);
		if (!$result) $like=0;
		else $like=mysqli_num_rows($result);
		$sql = "SELECT * FROM Star where type='article' and id1=$column and id2=$id";
		$result = mysqli_query($conn, $sql);
		if (!$result) $star=0;
		else $star=mysqli_num_rows($result);
		$sql = "SELECT * FROM Comment where columnid=$column and id=$id";
		$result = mysqli_query($conn, $sql);
		if (!$result) $comment=0;
		else $comment=mysqli_num_rows($result);
		$sql = "SELECT * FROM Users where UserName='".$row["Author"]."'";
		$result1 = mysqli_query($conn, $sql);
		$row1 = mysqli_fetch_assoc($result1);
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
				"columnid"=>$column,
				"id"=>$id,
				"name"=>$row['Title'],
				"author"=>$author,
				'view'=>$row['WatchNum'],
				'time'=>$row['ReleaseTime'],
				'like'=>$like,
				'star'=>$star,
				'comment'=>$comment,
				'link'=>$config["api-server-address"]."/".$config['article-data']."/$column/$id.html",
				'state'=>$row['banned']
			)
		);
		echo ReturnJSON($information);
	}
?>







