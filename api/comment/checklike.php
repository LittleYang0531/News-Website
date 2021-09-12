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
	$id=$_GET["id"];
	$column=$_GET["column"];
	$cid=$_GET['cid'];
	if ($id=='')
	{
		echo ReturnError(-404,"传递参数错误");
		exit;
	}
	if (!preg_match('/^[0-9]*$/',$column,$matches))
	{
		echo ReturnError(-404,"传递参数错误");
		exit;
	}
	if (!preg_match('/^[0-9]*$/',$id,$matches))
	{
		echo ReturnError(-404,"传递参数错误");
		exit;
	}
	if (!preg_match('/^[0-9]*$/',$cid,$matches))
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
	$result=mysqli_query($conn,"SELECT * FROM Comment WHERE columnid=$column and id=$id and cid=$cid");
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
	$result=mysqli_query($conn,"SELECT * FROM LikeData WHERE type='comment' and id1=$column and id2=$id and id3=$cid and uid=".$_COOKIE["DedeUserId"]);
	if (!$result)
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
			"cid"=>$cid,
			"islike"=>(mysqli_num_rows($result))?1:0,
		)
	);
	echo ReturnJSON($info);
	exit;
?>