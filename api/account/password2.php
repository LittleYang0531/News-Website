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
	$email=trim($_POST["email"]);
	$challenge=trim($_POST["challenge"]);
	$email_code=trim($_POST["ecode"]);
	$new = trim($_POST['password']);
	if($email=='')
	{
		echo ReturnError(-653,"原邮箱不能为空");
	    exit;
	}
	if($email_code=='')
	{
		echo ReturnError(-653,"原邮箱验证码不能为空");
	    exit;
	}
	$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
	if(!$conn){
		echo ReturnError(-500,"无法连接数据库");
		exit;
	}
	$result=mysqli_query($conn,"SELECT * FROM Users WHERE Mail='".$email."'");
	if (!$result)
	{
		echo ReturnError(-400,"数据库查询错误");
		exit;
	}
	// echo ReturnError(-400,$email);
	// exit;
	$account=mysqli_fetch_assoc($result);
	$result=mysqli_query($conn,"SELECT * FROM verify_code WHERE username='$email' and challenge='$challenge' and ua='".$_SERVER['HTTP_USER_AGENT']."'");
	if (!$result) 
	{
		echo ReturnError(-400,"数据库查询错误");
		exit;
	}
	if (mysqli_num_rows($result)==0) 
	{
		echo ReturnError(-404,"原邮箱验证码challenge无效");
		exit;
	}
	$verify=mysqli_fetch_assoc($result);
	if ($verify["verifykey"]!=$email_code)
	{
		echo ReturnError(-629,"原邮箱验证码错误");
		exit;
	}
	if (!$conn->query("UPDATE Users SET UserPassword='$new' WHERE UserId=".$account['UserId']))
	{
		echo ReturnError(-400,"数据库执行错误");
		exit;
	}
	if (!$conn->query("DELETE FROM LoginData WHERE uid=".$account['UserId']))
	{
		echo ReturnError(-400,"数据库执行错误");
		exit;
	}
	$result=mysqli_query($conn,"SELECT * FROM Users WHERE UserId=".$account['UserId']);
	if (!$result)
	{
		echo ReturnError(-400,"数据库查询错误");
		exit;
	}
	$account=mysqli_fetch_assoc($result);
	$info=array(
		"code"=>0,
		"message"=>"0",
		"ttl"=>1,
		"data"=>array(
			"uid"=>$account['UserId'],
			"isLogin"=>false,
			"password"=>$account["UserPassword"],
			"email"=>$account["Mail"],
		),
	);
	echo ReturnJSON($info);
	exit;
?>















