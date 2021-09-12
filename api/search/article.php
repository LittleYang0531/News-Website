<?php
	require "../config.php";
	require "../function.php";
	$l=$_GET["l"];
	$r=$_GET["r"];
	$keyword=$_GET["keyword"];
	$uid=$_GET["uid"];
	$column=$_GET["column"];
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
	$sql="SELECT * FROM Article";$used=0;
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
		$sql.=" WHERE Author='".$row["UserName"]."'";
	}
	if ($column!="")
	{
		$info=array();
		$sql1="SELECT * FROM NewsColumn WHERE num=$column";
		$result=mysqli_query($conn,$sql1);
		if (!$result)
		{
			echo ReturnError(-400,"数据库查询错误");
			exit;
		}
		if (mysqli_num_rows($result)==0)
		{
			echo ReturnError(-404,"此栏目不存在");
			exit;
		}
		$row=mysqli_fetch_assoc($result);
		if (!$used){$sql.=" WHERE ColumnNum=$column";$used=1;}
		else $sql.=" and ColumnNum=$column";
	}
	$info=array();
	$sql.=" order by ".($sort=="hot"?"WatchNum":"ReleaseTime")." DESC";
	$result1=mysqli_query($conn,$sql);
	if (!$result1)
	{
		echo ReturnError(-400,"数据库查询错误");
		exit;
	}
	$now=0;
	while ($row1=mysqli_fetch_assoc($result1))
	{
		if ($keyword!=""&&strpos($row1['Title'],$keyword)===FALSE) continue;
		if ($row1["banned"]==1) continue;
		$now++;
		if ($now<$l) continue;
		if ($now>$r) break;
		$sql="SELECT * FROM Users WHERE UserName='".$row1["Author"]."'";
		$result=mysqli_query($conn,$sql);
		if (!$result)
		{
			echo ReturnError(-400,"数据库查询错误".mysqli_error($conn));
			exit;
		}
		$row=mysqli_fetch_assoc($result);
		$author=array(
			"uid"=>$row['UserId'],
			"name"=>$row['UserName'],
			"birth"=>"****-**-**",
			'school'=>$row['School'],
			"class"=>$row['Class'],
			"grade"=>$row['Grade'],
			"title"=>$row['Title'],
			"mail"=>"**********",
			"QQ"=>"**********",
			"bili"=>($row['Bilibili']=="")?"":"//space.bilibili.com/".$row1['Bilibili']."/",
			"header"=>$config["api-server-address"]."/".$config['account-data']."/".$row['UserName']."/header.jpg",
			"background"=>$config["api-server-address"]."/".$config['account-data']."/".$row['UserName']."/background.jpg",
			"authority"=>$row['Authority'],
		);
		$sql = "SELECT * FROM LikeData where type='article' and id1=".$row1["ColumnNum"]." and id2=".$row1["num"];
		$result2 = mysqli_query($conn, $sql);
		if (!$result2) $like=0;
		else $like=mysqli_num_rows($result2);
		$sql = "SELECT * FROM Star where type='article' and id1=".$row1["ColumnNum"]." and id2=".$row1["num"];
		$result2 = mysqli_query($conn, $sql);
		if (!$result2) $star=0;
		else $star=mysqli_num_rows($result2);
		$sql = "SELECT * FROM Comment where columnid=".$row1["ColumnNum"]." and id=".$row1["num"];
		$result2 = mysqli_query($conn, $sql);
		if (!$result2) $comment=0;
		else $comment=mysqli_num_rows($result2);
		$info[]=array(
			"columnid"=>$row1["ColumnNum"],
			"id"=>$row1["num"],
			"name"=>$row1['Title'],
			"author"=>$author,
			'view'=>$row1['WatchNum'],
			'time'=>$row1['ReleaseTime'],
			'like'=>$like,
			'star'=>$star,
			'comment'=>$comment,
			'link'=>$config["api-server-address"]."/".$config['article-data']."/".$row1["ColumnNum"]."/".$row1["num"].".html",
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