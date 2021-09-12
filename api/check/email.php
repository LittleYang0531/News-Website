<?php
	require "../config.php";
	require "../function.php";
	if (!preg_match('/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/',$_GET['email']))
	{
		echo ReturnError(-404,"无效的邮箱格式");
		exit;
	}
	$state=CheckEmailExist($_GET['email']);
	if ($state==-1)
	{
		echo ReturnError(-500,"无法连接数据库");
		exit;
	}
	else if ($state==-2)
	{
		echo ReturnError(-400,"请求错误");
		exit;
	}
	else if ($state)
	{
		echo ReturnError(-652,"重复的邮箱");
		exit;
	}
	$info=array();
	$info=array(
		"code"=>0,
	);
	echo ReturnJSON($info);
?>







