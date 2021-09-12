<?php
	require "../config.php";
	require "../function.php";
	$keyword=$_GET["keyword"];
	$sort=$_GET["sort"];
	header("Content-Type:text/html;charset=utf-8");
	session_start();
	if ($sort=="") $sort="hot";
	$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
	if(! $conn ){
		echo ReturnError(-500,"无法连接数据库");
		exit;
	}
	$sql="SELECT * FROM wikis order by ".($sort=="hot"?"WatchNum":"CreateTime")." DESC";
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