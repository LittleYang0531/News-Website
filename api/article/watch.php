<?php
	require "../config.php";
	require "../function.php";
	$id = trim($_POST['id']);
	$column = trim($_POST['column']);
	header("Content-Type:text/html;charset=utf-8");
	session_start();
	if ($_SERVER['REQUEST_METHOD']!='POST')
	{
		echo ReturnError(-405,"调用方法错误");
		exit;
	}
	if ($id==""||$column=="")
	{
		echo ReturnError(-404,"传递参数错误");
		exit;
	}
	$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
	if(! $conn ){
		echo ReturnError(-500,"无法连接数据库");
		exit;
	}
	$sql = "SELECT * FROM Article where num=$id and ColumnNum=$column";
	$result = mysqli_query($conn, $sql);
	if (!$result)  
	{
		echo ReturnError(-400,"数据库查询错误");
		exit;
	}
	if (mysqli_num_rows($result)==0)
	{
		echo ReturnError(-404,"此文章不存在");
		exit;
	}
	else 
	{
		$row=mysqli_fetch_assoc($result);$article=array();
		$view=$row['WatchNum']+1;
		if (!$conn->query("UPDATE Article SET WatchNum=$view WHERE num=$id and ColumnNum=$column"))
		{
			echo ReturnError(-400,"数据库执行错误");
			exit;
		}
		if (CheckLogin())
		{
			if (!$conn->query("DELETE FROM WatchHistory WHERE type='article' and id1=$column and id2=$id and uid=".$_COOKIE['DedeUserId']))
			{
				echo ReturnError(-400,"数据库执行错误");
				exit;
			}
			if (!$conn->query("INSERT INTO WatchHistory (type,id1,id2,uid,time,useragent) VALUE ('article',$column,$id,".$_COOKIE['DedeUserId'].",".time().",'".$_SERVER['HTTP_USER_AGENT']."')"))
			{
				echo ReturnError(-400,"数据库执行错误");
				exit;
			}
		}
		$information=array(
			"code"=>0,
			"message"=>"0",
			"ttl"=>1,
			"data"=>array(
				"column"=>$column,
				"id"=>$id,
				"name"=>$row['Title'],
				"author"=>$row['Author'],
				"opentime"=>$row['ReleaseTime'],
				'view'=>$view,
				"time"=>time(),
			)
		);
		echo ReturnJSON($information);
	}
?>







