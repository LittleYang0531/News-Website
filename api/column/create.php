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
	$return=GetAuthority();
	if ($return==-2)
	{
		echo ReturnError(-500,"无法连接数据库");
		exit;
	}
	if ($return==-3)
	{
		echo ReturnError(-500,"数据库查询失败");
		exit;
	}
	if ($return!=2)
	{
		echo ReturnError(-650,"用户权限太低");
		exit;
	}
	$title=trim($_POST["title"]);
	$overtime=trim($_POST["overtime"]);
	if ($title=='')
	{
		echo ReturnError(-404,"标题不能为空");
		exit;
	}
	if ($overtime=='')
	{
		echo ReturnError(-404,"过期时间不能为空");
		exit;
	}
	if (!preg_match('/^[0-9]*$/',$overtime,$matches))
	{
		echo ReturnError(-404,"过期时间无效，要求是一个整数");
		exit;
	}
	$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
	if (!$conn)
	{
		echo ReturnError(-500,"无法连接数据库");
		exit;
	}
	$result=mysqli_query($conn,"SELECT * FROM NewsColumn");
	$num=1+mysqli_num_rows($result);
	if (!$conn->query("INSERT INTO NewsColumn ( num,name,overtime,starttime,WatchNum ) VALUES ($num,'$title','$overtime','".time()."',0)"))
	{
		echo ReturnError(-500,"数据入库失败");
		exit;
	}
	mkdir("../".$config['article-data']."/$num",777);
	$info=array();
	$info=array(
		"code"=>0,
		"message"=>"0",
		"ttl"=>1,
		"data"=>array(
			"id"=>$num,
			"name"=>$title,
			"opentime"=>time(),
			"overtime"=>$overtime,
		)
	);
	echo ReturnJSON($info);
	exit;
?>







