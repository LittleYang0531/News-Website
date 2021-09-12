<?php
	require "../config.php";
	require "../function.php";
	$l = $_GET['l'];
	$r = $_GET['r'];
	header("Content-Type:text/html;charset=utf-8");
	session_start();
	if($l==""||$r=="")
	{
		echo ReturnError(-400,"传递参数错误");
		exit;
	}
	if (!CheckLogin())
	{
		echo ReturnError(-101,"账号未登录");
		exit;
	}
	$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
	if(! $conn ){
		echo ReturnError(-500,"无法连接数据库");
		exit;
	}
	$sql="SELECT * FROM SysMessage WHERE toid=".$_COOKIE["DedeUserId"]." order by time DESC";
	$result=mysqli_query($conn, $sql);
	if (!$result)  
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
				$info[]=array(
					"toid"=>$row["toid"],
					"title"=>$row["title"],
					'content'=>$row["content"],
					'time'=>$row['time'],
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