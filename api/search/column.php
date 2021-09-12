<?php
	require "../config.php";
	require "../function.php";
	$l=$_GET["l"];
	$r=$_GET["r"];
	$keyword=$_GET["keyword"];
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
	$sql="SELECT * FROM NewsColumn order by ".($sort=="hot"?"WatchNum":($sort=="starttime"?"starttime":"overtime"))." DESC";
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
		if ($keyword!=""&&strpos($row['name'],$keyword)===FALSE) continue;
		$now++;
		if ($now<$l) continue;
		if ($now>$r) break;
		$article=array();
		$result2=mysqli_query($conn,"SELECT * FROM article WHERE ColumnNum=".$row["num"]);
		if (!$result2)
		{
			echo ReturnError(-400,"数据库查询错误");
			exit;
		}
		while ($row2=mysqli_fetch_assoc($result2))
		{
			if ($row2['banned']==1) continue;
			$sql = "SELECT * FROM LikeData where type='article' and id1=".$row["num"]." and id2=".$row2["num"];
			$result3 = mysqli_query($conn, $sql);
			if (!$result3) $like=0;
			else $like=mysqli_num_rows($result3);
			$sql = "SELECT * FROM Star where type='article' and id1=".$row["num"]." and id2=".$row2["num"];
			$result3 = mysqli_query($conn, $sql);
			if (!$result3) $star=0;
			else $star=mysqli_num_rows($result3);	
			$sql = "SELECT * FROM Comment where columnid=".$row["num"]." and id=".$row2["num"];
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
				"column"=>$row["num"],
				"id"=>$row2["num"],
				"name"=>$row2['Title'],
				"author"=>$author,
				'view'=>$row2['WatchNum'],
				'time'=>$row2['ReleaseTime'],
				'like'=>$like,
				'star'=>$star,
				'comment'=>$comment,
				'link'=>$config["api-server-address"]."/".$config['article-data']."/".$row["num"]."/".$row2["num"].".html",
			);
		}
		$info[]=array(
			"id"=>$row["num"],
			"name"=>$row['name'],
			"opentime"=>$row['starttime'],
			"overtime"=>$row['overtime'],
			'view'=>$row['WatchNum'],
			"article"=>$article
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