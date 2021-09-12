<?php
	require "../config.php";
	require "../function.php";
    header("Content-Type:text/html;charset=utf-8");
    session_start();
	if ($_SERVER['REQUEST_METHOD']!='POST')
	{
		echo ReturnError(-405,"调用方法错误");
		exit;
	}
	if (!CheckLogin())
	{
		echo ReturnError(-101,"账号未登录");
		exit;
	}
	$title=trim($_POST["title"]);
	if ($title=='')
	{
		echo ReturnError(-404,"标题不能为空");
		exit;
	}
	$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
	if (!$conn)
	{
		echo ReturnError(-500,"无法连接数据库");
		exit;
	}
	$result=mysqli_query($conn,"SELECT * FROM wikis");
	$num=1+mysqli_num_rows($result);
	if (!$conn->query("INSERT INTO wikis (Title,id,CreateTime,WatchNum) VALUES ('$title',$num,".time().",0)"))
	{
		echo ReturnError(-500,"数据库执行错误");
		exit;
	}
	mkdir("../".$config['wiki-data']."/$num",777);
	$info=array();
	$info=array(
		"code"=>0,
		"message"=>"0",
		"ttl"=>1,
		"data"=>array(
			"id"=>$num,
			"name"=>$title,
			"opentime"=>time(),
		)
	);
	echo ReturnJSON($info);
	exit;
?>







