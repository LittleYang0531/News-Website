<?php
	require "../config.php";
	require "../function.php";
	$keyword=$_GET["keyword"];
	$uid=$_GET["uid"];
	$column=$_GET["column"];
	$sort=$_GET["sort"];
	header("Content-Type:text/html;charset=utf-8");
	session_start();
	if ($sort=="") $sort="hot";
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
		if ($keyword!=""&&!strpos($row1['Title'],$keyword)) continue;
		if ($row1["banned"]==1) continue;
		$now++;
	}
	$information=array(
		"code"=>0,
		"message"=>0,
		"ttl"=>0,
		"max"=>$now,
	);
	echo ReturnJSON($information);
	exit;
?>