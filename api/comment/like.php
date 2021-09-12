<?php
	require "../config.php";
	require "../".$config["function-path"];
	
    header("Content-Type:text/html;charset=utf-8");
    session_start();
	if (!CheckLogin())
	{
		echo ReturnError(-101,"账号未登录");
		exit;
	}
	if ($_SERVER['REQUEST_METHOD']!='POST')
	{
		echo ReturnError(-405,"调用方法错误");
		exit;
	}
	$id=trim($_POST["id"]);
	$column=trim($_POST["column"]);
	$comment=trim($_POST["comment"]);
	if ($id==''||$column==''||$comment=='')
	{
		echo ReturnError(-404,"传递参数错误");
		exit;
	}
	$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
	if (!$conn)
	{
		echo ReturnError(-500,"无法连接数据库");
		exit;
	}
	$result=mysqli_query($conn,"SELECT * FROM Comment WHERE columnid=$column and id=$id and cid=$comment");
	if (!$result)
	{
		echo ReturnError(-400,"数据库查询错误");
		exit;
	}
	if (mysqli_num_rows($result)==0)
	{
		echo ReturnError(-404,"此评论不存在");
		exit;
	}
	$article=mysqli_fetch_assoc($result);
	if ($article['banned']==1)
	{
		echo ReturnError(-102,"此评论已被删除");
		exit;
	}
	$result=mysqli_query($conn,"SELECT * FROM LikeData WHERE type='comment' and id1=$column and id2=$id and id3=$comment and uid=".$_COOKIE["DedeUserId"]);
	if (!$result)
	{
		echo ReturnError(-400,"数据库查询错误");
		exit;
	}
	if (mysqli_num_rows($result))
	{
		if (!$conn->query("DELETE FROM LikeData WHERE type='comment' and id1=$column and id2=$id and id3=$comment and uid=".$_COOKIE['DedeUserId']))
		{
			echo ReturnError(-400,"数据库执行错误");
			exit;
		}
	}
	else 
	{
		if (!$conn->query("INSERT INTO LikeData (type,id1,id2,id3,uid,time,useragent) VALUES ('comment',$column,$id,$comment,".$_COOKIE['DedeUserId'].",".time().",'".$_SERVER['HTTP_USER_AGENT']."')"))
		{
			echo ReturnError(-400,"数据库执行错误");
			exit;
		}
	}
	$result=mysqli_query($conn,"SELECT * FROM LikeData WHERE type='comment' and id1=$column and id2=$id and id3=$comment");
	if (!$result)
	{
		echo ReturnError(-400,"数据库查询错误");
		exit;
	}
	$result1=mysqli_query($conn,"SELECT * FROM LikeData WHERE type='comment' and id1=$column and id2=$id and id3=$comment and uid=".$_COOKIE['DedeUserId']);
	if (!$result1)
	{
		echo ReturnError(-400,"数据库查询错误");
		exit;
	}
	$info=array(
		"code"=>0,
		"message"=>"0",
		"ttl"=>1,
		"data"=>array(
			"id"=>$id,
			"column"=>$column,
			"cid"=>$comment,
			"time"=>time(),
			"like"=>mysqli_num_rows($result),
			"islike"=>((mysqli_num_rows($result1))?1:0),
		)
	);
	echo ReturnJSON($info);
	exit;
?>