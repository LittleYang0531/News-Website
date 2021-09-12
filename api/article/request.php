<?php
	require "../config.php";
	require "../function.php";
	$l = $_GET['l'];
	$r = $_GET['r'];
	$sort = $_GET['sort'];
	$withban=$_GET['ban'];
	if ($withban=='') $withban=0;
	header("Content-Type:text/html;charset=utf-8");
	session_start();
	if ($sort=="") $sort="hot";
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
	if ($sort=='createtime') $sql = "SELECT * FROM Article order by ReleaseTime DESC";
	else if ($sort=='hot') $sql = "SELECT * FROM Article order by WatchNum DESC";
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
				if ($row['banned']==1&&$withban)
				{
					$now--;
					continue;
				}
				$sql = "SELECT * FROM LikeData where type='article' and id1=".$row["ColumnNum"]." and id2=".$row["num"];
				$result2 = mysqli_query($conn, $sql);
				if (!$result2) $like=0;
				else $like=mysqli_num_rows($result2);
				$sql = "SELECT * FROM Star where type='article' and id1=$column and id2=$id";
				$result2 = mysqli_query($conn, $sql);
				if (!$result2) $star=0;
				else $star=mysqli_num_rows($result2);
				$sql = "SELECT * FROM Comment where columnid=$column and id=$id";
				$result2 = mysqli_query($conn, $sql);
				if (!$result2) $comment=0;
				else $comment=mysqli_num_rows($result2);
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
				$info[]=array(
					"columnid"=>$row["ColumnNum"],
					"id"=>$row["num"],
					"name"=>$row['Title'],
					"author"=>$author,
					'view'=>$row['WatchNum'],
					'time'=>$row['ReleaseTime'],
					'like'=>$like,
					'star'=>$star,
					'comment'=>$comment,
					'link'=>$config["api-server-address"]."/".$config['article-data']."/".$row["ColumnNum"]."/".$row["num"].".html",
					'banned'=>$row["banned"],
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







