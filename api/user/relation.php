<?php
	require "../config.php";
	require "../function.php";
	$uid = $_GET['uid'];
	$l=$_GET["l"];
	$r=$_GET["r"];
	$type=$_GET["type"];
	$sort=$_GET["sort"];
	$sort="time";
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
	$row = mysqli_fetch_assoc($result);
	$result=mysqli_query($conn,"SELECT * FROM Relation WHERE ".($type=='follow'?"fromid":"toid")."=$uid order by $sort DESC");
	if (!$result)  
	{
		echo ReturnError(-400,"数据库查询错误");
		exit;
	}
	$user=array();
	$now=1;
	while ($row=mysqli_fetch_assoc($result))
	{
		if ($now>=$l)
		{
			$result1=mysqli_query($conn,"SELECT * FROM Users WHERE UserId=".$row[($type=='follow'?"toid":"fromid")]."");
			if (!$result1)  
			{
				echo ReturnError(-400,"数据库查询错误");
				exit;
			}
			if (mysqli_num_rows($result1)==0)
			{
				$user[]=array();
				$now++;
				if ($now>$r) break;
				continue;
			}
			$row1=mysqli_fetch_assoc($result1);
			$user[]=array(
				"from"=>$row["fromid"],
				"to"=>$row["toid"],
				"time"=>$row["time"],
				"user"=>array(
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
					"sign"=>($row1["sign"]=="")?"empty":$row1["sign"],
				)
			);
		}
		$now++;
		if ($now>$r) break;
	}
	$information=array(
		"code"=>0,
		"message"=>0,
		"ttl"=>0,
		"data"=>array(
			"left"=>$l,
			"right"=>$r,
			"replies"=>$user,
		),
	);
	echo ReturnJSON($information);
?>