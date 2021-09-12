<?php
	require "../config.php";
	require "../function.php";
	$l=$_GET["l"];
	$r=$_GET["r"];
	$keyword=$_GET["keyword"];
	$uid=$_GET["uid"];
	$sort=$_GET["sort"];
	header("Content-Type:text/html;charset=utf-8");
	session_start();
	if ($sort=="") $sort="hot";
	if($l==""||$r=="")
	{
		echo ReturnError(-400,"传递参数错误");
		exit;
	}
	$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
	if(! $conn ){
		echo ReturnError(-500,"无法连接数据库");
		exit;
	}
	$sql="SELECT * FROM notice";
	$used=0;
	if ($uid!="")
	{
		$used=1;
		$info=array();
		$sql1="SELECT * FROM Users WHERE UserId=$uid";
		$result=mysqli_query($conn,$sql1);
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
		$row=mysqli_fetch_assoc($result);
		if ($row["Authority"]<1) 
		{
			echo ReturnError(-650,"该人无管理员权限");
			exit;
		}
		$sql.=" WHERE Author='".$row["UserName"]."'";
	}
	$sql.=" order by ".($sort=="hot"?"WatchNum":"ReleaseTime")." DESC";
	$info=array();
	$result=mysqli_query($conn,$sql);
	if (!$result)
	{
		echo ReturnError(-400,"数据库查询错误");
		exit;
	}
	$now=0;
	while ($row=mysqli_fetch_assoc($result))
	{
		if ($keyword!=""&&strpos($row['Title'],$keyword)===FALSE) continue;
		$now++;
		if ($now<$l) continue;
		if ($now>$r) break;
		$result1=mysqli_query($conn,"SELECT * FROM Users WHERE UserName='".$row['Author']."'");
		if (!$result1||mysqli_num_rows($result1)==0)  
		{
			echo ReturnError(-400,"数据库查询错误");
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
		$info[]=array(
			"id"=>$row["num"],
			"name"=>$row['Title'],
			"author"=>$author,
			"release"=>$row['ReleaseTime'],
			'view'=>$row['WatchNum'],
			'link'=>$config["api-server-address"]."/".$config['notice-data']."/".$row["num"].".html"
		);
	}
	$information=array(
		"code"=>0,
		"message"=>0,
		"ttl"=>0,
		"data"=>array(
			"left"=>$l,
			"right"=>$r,
			"replies"=>$info,
		),
	);
	echo ReturnJSON($information);
	exit;
?>