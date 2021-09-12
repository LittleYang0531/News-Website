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
	if ($id=='')
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
	$result=mysqli_query($conn,"SELECT * FROM wikis WHERE id=$id");
	if (!$result)
	{
		echo ReturnError(-400,"数据库查询错误");
		exit;
	}
	if (mysqli_num_rows($result)==0)
	{
		echo ReturnError(-404,"此词条不存在");
		exit;
	}
	$wiki=mysqli_fetch_assoc($result);
	if ($wiki['banned']==1)
	{
		echo ReturnError(-102,"此词条已被删除");
		exit;
	}
	$result=mysqli_query($conn,"SELECT * FROM Star WHERE type='wiki' and id1=$id and uid=".$_COOKIE["DedeUserId"]);
	if (!$result)
	{
		echo ReturnError(-400,"数据库查询错误");
		exit;
	}
	if (mysqli_num_rows($result))
	{
		if (!$conn->query("DELETE FROM Star WHERE type='wiki' and id1=$id and uid=".$_COOKIE['DedeUserId']))
		{
			echo ReturnError(-400,"数据库执行错误");
			exit;
		}
	}
	else 
	{
		if (!$conn->query("INSERT INTO Star (type,id1,uid,time,useragent) VALUES ('wiki',$id,".$_COOKIE['DedeUserId'].",".time().",'".$_SERVER['HTTP_USER_AGENT']."')"))
		{
			echo ReturnError(-400,"数据库执行错误");
			exit;
		}
	}
	$result=mysqli_query($conn,"SELECT * FROM Star WHERE type='wiki' and id1=$id");
	if (!$result)
	{
		echo ReturnError(-400,"数据库查询错误");
		exit;
	}
	$result1=mysqli_query($conn,"SELECT * FROM Star WHERE type='wiki' and id1=$id and uid=".$_COOKIE['DedeUserId']);
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
			"star"=>mysqli_num_rows($result),
			"isstar"=>((mysqli_num_rows($result1))?1:0),
		)
	);
	echo ReturnJSON($info);
	exit;
?>