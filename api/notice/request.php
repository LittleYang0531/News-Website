<?php
	require "../config.php";
	require "../function.php";
	$l = $_GET['l'];
	$r = $_GET['r'];
	$sort = $_GET['sort'];
	header("Content-Type:text/html;charset=utf-8");
	session_start();
	if ($sort=="") $sort="creatatime";
	if($l==""||$r==""||$sort=="")
	{
		echo ReturnError(-400,"传递参数错误");
		exit;
	}
	$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
	if(! $conn ){
		echo ReturnError(-500,"无法连接数据库");
		exit;
	}
	$sql = "";
	if ($sort=='createtime') $sql = "SELECT * FROM Notice order by ReleaseTime DESC";
	else if ($sort=='hot') $sql = "SELECT * FROM Notice order by WatchNum DESC";
	else
	{
		echo ReturnError(-400,"传递参数错误");
		exit;
	}
	$result = mysqli_query($conn, $sql);
	if (!$result||mysqli_num_rows($result)==0)  
	{
		echo ReturnError(-400,"数据库查询错误");
		exit;
	}
	else 
	{
		$now=1;
		$info=array();
		while ($row=mysqli_fetch_assoc($result))
		{
			if ($now>=$l)
			{
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
				"replies"=>$info,
			),
		);
		echo ReturnJSON($information);
		exit;
	}
?>







