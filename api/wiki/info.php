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
	$sql = "SELECT * FROM wikis where id=$id";
	$result = mysqli_query($conn, $sql);
	if (!$result)  
	{
		echo ReturnError(-400,"数据库查询错误");
		exit;
	}
	if (mysqli_num_rows($result)==0)
	{
		echo ReturnError(-404,"此词条不存在");
		exit;
	}
	else 
	{
		$version=array();
		$row = mysqli_fetch_assoc($result);
		$sql="SELECT * FROM wiki WHERE id=$id order by UpdateTime DESC";
		$result5=mysqli_query($conn,$sql);
		if (!$result5)
		{
			echo ReturnError(-400,"数据库查询错误");
			exit;
		}
		$latest=0;
		$sql = "SELECT * FROM LikeData where type='wiki' and id1=$id";
		$result = mysqli_query($conn, $sql);
		if (!$result) $like=0;
		else $like=mysqli_num_rows($result);
		$sql = "SELECT * FROM Star where type='wiki' and id1=$id";
		$result = mysqli_query($conn, $sql);
		if (!$result) $star=0;
		else $star=mysqli_num_rows($result);
		while ($row5=mysqli_fetch_assoc($result5))
		{
			if ($row5['banned']==1) continue;
			$sql = "SELECT * FROM Users where UserName='".$row5["Author"]."'";
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
			$version[]=array(
				"author"=>$author,
				"opentime"=>$row5["UpdateTime"],
				"version"=>$row5["version"],
				"wikiid"=>$row5["id"],
				"reason"=>$row5["reason"],
				"watch"=>$row5["WatchNum"],
				"link"=>$config["api-server-address"]."/".$config['wiki-data']."/".$row5["id"]."/".$row5["version"].".html",
			);
			$latest=max($latest,$row5["UpdateTime"]);
		}
		$information=array(
			"code"=>0,
			"message"=>"0",
			"ttl"=>1,
			"data"=>array(
				"id"=>$id,
				"name"=>$row['Title'],
				"opentime"=>$row["CreateTime"],
				"latest"=>$latest,
				'view'=>$row['WatchNum'],
				"like"=>$like,
				"star"=>$star,
				"history"=>$version,
			)
		);
		echo ReturnJSON($information);
	}
?>







