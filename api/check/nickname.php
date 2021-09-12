<?php
	require "../config.php";
	require "../function.php";
	$state=CheckNameExist($_GET['nickName']);
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
		echo ReturnError(-652,"重复的用户名");
		exit;
	}
	$info=array();
	$info=array(
		"code"=>0,
	);
	echo ReturnJSON($info);
?>







