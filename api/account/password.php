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
	$old = trim($_POST['old']);
	$old_email=trim($_POST["oldemail"]);
	$old_challenge=trim($_POST["oldchallenge"]);
	$old_email_code=trim($_POST["oldecode"]);
	$new = trim($_POST['new']);
	$new_email=trim($_POST["newemail"]);
	$new_challenge=trim($_POST["newchallenge"]);
	$new_email_code=trim($_POST["newecode"]);
	if (!CheckLogin())
	{
		echo ReturnError(-652,"账号未登录");
		exit;
	}
	openssl_private_decrypt(base64_decode($old),$oldpass,$config["rsa-private-key"]);
	openssl_private_decrypt(base64_decode($new),$newpass,$config["rsa-private-key"]);
	if($oldpass=='')
	{
		echo ReturnError(-653,"原密码不能为空");
	    exit;
	}
	if($old_email=='')
	{
		echo ReturnError(-653,"原邮箱不能为空");
	    exit;
	}
	if($old_email_code=='')
	{
		echo ReturnError(-653,"原邮箱验证码不能为空");
	    exit;
	}
	$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
	if(!$conn){
		echo ReturnError(-500,"无法连接数据库");
		exit;
	}
	$result=mysqli_query($conn,"SELECT * FROM Users WHERE UserId=".$_COOKIE['DedeUserId']);
	if (!$result)
	{
		echo ReturnError(-400,"数据库查询错误");
		exit;
	}
	$account=mysqli_fetch_assoc($result);
	openssl_private_decrypt(base64_decode($account["UserPassword"]),$realpass,$config["rsa-private-key"]);
	if ($realpass!=$oldpass)
	{
		echo ReturnError(-629,"原密码错误");
		exit;
	}
	if ($account["Mail"]!=$old_email) 
	{
		echo ReturnError(-629,"原邮箱错误");
		exit;
	}
	$result=mysqli_query($conn,"SELECT * FROM verify_code WHERE username='$old_email' and challenge='$old_challenge' and ua='".$_SERVER['HTTP_USER_AGENT']."'");
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
	if ($verify["verifykey"]!=$old_email_code)
	{
		echo ReturnError(-629,"原邮箱验证码错误");
		exit;
	}
	if ($newpass!="") if (!$conn->query("UPDATE Users SET UserPassword='$new' WHERE UserId=".$account['UserId']))
	{
		echo ReturnError(-400,"数据库执行错误");
		exit;
	}
	if ($new_email!="")
	{
		$result=mysqli_query($conn,"SELECT * FROM Users WHERE Mail='$new_email'");
		if (!$result)
		{
			echo ReturnError(-400,"数据库查询错误");
			exit;
		}
		if (mysqli_num_rows($result)) 
		{
			echo ReturnError(-404,"新邮箱已被使用");
			exit;
		}
		$result=mysqli_query($conn,"SELECT * FROM verify_code WHERE username='$new_email' and challenge='$new_challenge' and ua='".$_SERVER['HTTP_USER_AGENT']."'");
		if (!$result)
		{
			echo ReturnError(-400,"数据库查询错误");
			exit;
		}
		if (mysqli_num_rows($result)==0) 
		{
			echo ReturnError(-404,"新邮箱验证码challenge无效");
			exit;
		}
		$verify=mysqli_fetch_assoc($result);
		if ($new_email_code=="") 
		{
			echo ReturnError(-653,"新邮箱验证码不能为空");
			exit;
		}
		if ($verify["verifykey"]!=$new_email_code)
		{
			echo ReturnError(-629,"新邮箱验证码错误");
			exit;
		}
		if (!$conn->query("UPDATE Users SET Mail='$new_email' WHERE UserId=".$account['UserId']))
		{
			echo ReturnError(-400,"数据库执行错误");
			exit;
		}
	}
	if (!$conn->query("DELETE FROM LoginData WHERE uid=".$account['UserId']))
	{
		echo ReturnError(-400,"数据库执行错误");
		exit;
	}
	$result=mysqli_query($conn,"SELECT * FROM Users WHERE UserId=".$_COOKIE['DedeUserId']);
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















