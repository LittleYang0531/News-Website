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
	$sql="SELECT * FROM Comment order by time DESC";
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
			$sql="SELECT * FROM Article WHERE ColumnNum=".$row["columnid"]." and num=".$row["id"];
			$result1=mysqli_query($conn, $sql);
			if (!$result1)  
			{
				echo ReturnError(-400,"数据库查询错误");
				exit;
			}
			if (mysqli_num_rows($result1)==0) continue;
			$row2=mysqli_fetch_assoc($result1);
			if ($row2['banned']==1) continue;
			$sql = "SELECT * FROM LikeData where type='article' and id1=".$row["columnid"]." and id2=".$row["id"];
			$result2 = mysqli_query($conn, $sql);
			if (!$result2) $like=0;
			else $like=mysqli_num_rows($result2);
			$sql = "SELECT * FROM Star where type='article' and id1=".$row["columnid"]." and id2=".$row["id"];
			$result2 = mysqli_query($conn, $sql);
			if (!$result2) $star=0;
			else $star=mysqli_num_rows($result2);
			$sql = "SELECT * FROM Comment where columnid=".$row["columnid"]." and id=".$row["id"];
			$result2 = mysqli_query($conn, $sql);
			if (!$result2) $comment=0;
			else $comment=mysqli_num_rows($result2);
			if ($row["root"]==0) $sql = "SELECT * FROM Users where UserName='".$row2["Author"]."'";
			else $sql = "SELECT * FROM Users where UserId='".$row["uid"]."'";
			$result1 = mysqli_query($conn, $sql);
			$row1 = mysqli_fetch_assoc($result1);
			$sql = "SELECT * FROM Comment where columnid=".$row["columnid"]." and id=".$row["id"]." and cid=".$row["root"];
			$result1 = mysqli_query($conn, $sql);
			if (mysqli_num_rows($result1))
			{
				$row3 = mysqli_fetch_assoc($result1);
				if ($row3["uid"]!=$_COOKIE["DedeUserId"]) continue;
			}
			if ($row1["UserId"]!=$_COOKIE["DedeUserId"]&&$row["root"]==0) continue;
			$sql = "SELECT * FROM Users where UserId='".$row["uid"]."'";
			$result1 = mysqli_query($conn, $sql);
			$row1 = mysqli_fetch_assoc($result1);
			if ($now>=$l)
			{
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
					"column"=>$row["columnid"],
					"id"=>$row["id"],
					"cid"=>$row["cid"],
					"root"=>$row["root"],
					"author"=>$author,
					'time'=>$row['time'],
					'content'=>$row["content"],
					'like'=>$like,
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







