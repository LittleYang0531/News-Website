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
	$sql = "SELECT * FROM NewsColumn where num=$id";
	$result = mysqli_query($conn, $sql);
	if (!$result)  
	{
		echo ReturnError(-400,"请求错误");
		exit;
	}
	if (mysqli_num_rows($result)==0)
	{
		echo ReturnError(-404,"此栏目不存在");
		exit;
	}
	else 
	{
		$row = mysqli_fetch_assoc($result);$article=array();
		$result2=mysqli_query($conn,"SELECT * FROM article WHERE ColumnNum=".$id);
		if (!$result2)
		{
			echo ReturnError(-400,"请求错误");
			exit;
		}
		while ($row2=mysqli_fetch_assoc($result2))
		{
			if ($row2['banned']==1) continue;
			$sql = "SELECT * FROM LikeData where type='article' and id1=$id and id2=".$row2["num"];
			$result3 = mysqli_query($conn, $sql);
			if (!$result3) $like=0;
			else $like=mysqli_num_rows($result3);
			$sql = "SELECT * FROM Star where type='article' and id1=$column and id2=$id";
			$result3 = mysqli_query($conn, $sql);
			if (!$result3) $star=0;
			else $star=mysqli_num_rows($result3);
			$sql = "SELECT * FROM Comment where columnid=$column and id=$id";
			$result3 = mysqli_query($conn, $sql);
			if (!$result3) $comment=0;
			else $comment=mysqli_num_rows($result3);
			$sql = "SELECT * FROM Users where UserName='".$row2["Author"]."'";
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
			$article[]=array(
				"column"=>$id,
				"id"=>$row2["num"],
				"name"=>$row2['Title'],
				"author"=>$author,
				'view'=>$row2['WatchNum'],
				'time'=>$row2['ReleaseTime'],
				'like'=>$like,
				'star'=>$star,
				'comment'=>$comment,
				'link'=>$config["api-server-address"]."/".$config['article-data']."/$id/".$row2["num"].".html",
			);
		}
		$information=array(
			"code"=>0,
			"message"=>"0",
			"ttl"=>1,
			"data"=>array(
				"id"=>$id,
				"name"=>$row['name'],
				"opentime"=>$row['starttime'],
				"overtime"=>$row['overtime'],
				'view'=>$row['WatchNum'],
				"article"=>$article
			)
		);
		echo ReturnJSON($information);
	}
?>







