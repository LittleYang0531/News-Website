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
	$content=trim($_POST["content"]);
	$root=trim($_POST["root"]);
	if ($id==''||$column==''||$content==''||$root=="")
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
	$result=mysqli_query($conn,"SELECT * FROM Article WHERE ColumnNum=$column and num=$id");
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
	$article=mysqli_fetch_assoc($result);
	if ($article['banned']==1)
	{
		echo ReturnError(-102,"此文章已被删除");
		exit;
	}
	$result=mysqli_query($conn,"SELECT * FROM Comment WHERE columnid=$column and id=$id and cid=$root");
	if (!$result)
	{
		echo ReturnError(-400,"数据库查询错误");
		exit;
	}
	if (mysqli_num_rows($result)==0&&$root!=0)
	{
		echo ReturnError(-404,"此评论根节点不存在");
		exit;
	}
	$result=mysqli_query($conn,"SELECT * FROM Comment WHERE columnid=$column and id=$id");
	if (!$result)
	{
		echo ReturnError(-400,"数据库查询错误");
		exit;
	}
	$cid=mysqli_num_rows($result)+1;
	if (!$conn->query("INSERT INTO Comment (columnid,id,root,cid,uid,time,content) VALUES ($column,$id,$root,$cid,".$_COOKIE['DedeUserId'].",".time().",'$content')"))
	{
		echo ReturnError(-400,"数据库执行错误");
		exit;
	}
	$sql = "SELECT * FROM Users where UserId=".$_COOKIE['DedeUserId'];
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
	$info=array(
		"code"=>0,
		"message"=>"0",
		"ttl"=>1,
		"data"=>array(
			"id"=>$id,
			"column"=>$column,
			"cid"=>$cid,
			"root"=>$root,
			"time"=>time(),
			"author"=>$author,
		)
	);
	echo ReturnJSON($info);
	exit;
?>