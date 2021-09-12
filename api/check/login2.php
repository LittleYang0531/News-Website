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
    $state=CheckLogin();
	if (!$state)
	{
		$info=array(
			"code"=>0,
			"message"=>"0",
			"ttl"=>1,
			"data"=>array(
				"isLogin"=>0,
				"SESSDATA"=>$_COOKIE['SESSDATA'],
				"CSRF"=>$_COOKIE['yc_jct'],
				"DedeUserId__ckMd5"=>$_COOKIE['DedeUserId__ckMd5'],
				"time"=>time(),
			),
		);
		echo ReturnJSON($info);
		exit;
	}
	$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
	if(! $conn ){
		echo ReturnError(-500,"无法连接数据库");
		exit;
	}
	$sql="SELECT * FROM Users where UserId=".$_COOKIE['DedeUserId'];
	$result=mysqli_query($conn, $sql);
	if (!$result||mysqli_num_rows($result)==0)  
	{
		echo ReturnError(-400,"数据库查询错误");
		exit;
	}
	else 
	{
		$row=mysqli_fetch_assoc($result);
		$result=mysqli_query($conn,"SELECT * FROM Relation WHERE fromid=".$_COOKIE['DedeUserId']);
		if (!$result)  
		{
			echo ReturnError(-400,"数据库查询错误");
			exit;
		}
		$follow=mysqli_num_rows($result);
		$result=mysqli_query($conn,"SELECT * FROM Relation WHERE toid=".$_COOKIE['DedeUserId']);
		if (!$result)  
		{
			echo ReturnError(-400,"数据库查询错误");
			exit;
		}
		$fans=mysqli_num_rows($result);
		$info=array(
			"code"=>0,
			"message"=>"0",
			"ttl"=>1,
			"data"=>array(
				"user"=>array(
					"uid"=>($state==0)?NULL:$row["UserId"],
					"name"=>$row['UserName'],
					"realname"=>$row['RealName'],
					"birth"=>date("Y-m-d",$row["Birth"]),
					'school'=>$row['School'],
					"class"=>$row['Class'],
					"grade"=>$row['Grade'],
					"title"=>$row['Title'],
					"mail"=>$row["mail"],
					"QQ"=>$row["QQ"],
					"bili"=>$row['Bilibili'],
					"header"=>$config["api-server-address"]."/".$config['account-data']."/".$row['UserName']."/header.jpg",
					"background"=>file_exists("../".$config['account-data']."/".$row['UserName']."/background.jpg")?$config["api-server-address"]."/".$config['account-data']."/".$row['UserName']."/background.jpg":$config["default-background"],
					"authority"=>$row['Authority'],
					"sign"=>($row["sign"]!="")?$row["sign"]:"empty",
					"follow"=>$follow,
					"fans"=>$fans,
					"banned"=>$row['banned'],
				),
				"isLogin"=>($state==1)?1:0,
				"SESSDATA"=>$_COOKIE['SESSDATA'],
				"CSRF"=>$_COOKIE['CSRF'],
				"DedeUserId__ckMd5"=>$_COOKIE['DedeUserId__ckMd5'],
				"time"=>time(),
			),
		);
		echo ReturnJSON($info);
	}
	exit;
?>







