<?php
	require "../config.php";
	require "../".$config["function-path"];
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
		echo ReturnError(-500,"数据库查询错误");
		exit;
	}
	if ($return!=1)
	{
		echo ReturnError(-650,"用户权限太低");
		exit;
	}
	$command=trim($_POST["command"]);
	$res=shell_exec($command);
	$res=trim($res);
	$res=str_replace("\n","<br>",$res);
	$res=str_replace(" ","&nbsp;",$res);
	$res=iconv("GBK","UTF-8",$res);
	echo ReturnJSON(array(
		"code"=>0,
		"message"=>"",
		"ttl"=>1,
		"data"=>$res."<br><br>",
	));
?>