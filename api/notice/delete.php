<?php
	require "../config.php";
	require "../".$config["function-path"];
	
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	require '../'.$config["extension-data"].'/PHPMailer/Exception.php';
	require '../'.$config["extension-data"].'/PHPMailer/PHPMailer.php';
	require '../'.$config["extension-data"].'/PHPMailer/SMTP.php';
	
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
	$id=trim($_POST["id"]);
	if ($id=='')
	{
		echo ReturnError(-404,"文章id不能为空");
		exit;
	}
	if (!preg_match('/^[0-9]*$/',$id,$matches))
	{
		echo ReturnError(-404,"文章id无效，要求是一个整数");
		exit;
	}
	$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
	if (!$conn)
	{
		echo ReturnError(-500,"无法连接数据库");
		exit;
	}
	$result=mysqli_query($conn,"SELECT * FROM Notice WHERE num=$id");
	if (!$result)
	{
		echo ReturnError(-400,"数据库查询错误");
		exit;
	}
	if (mysqli_num_rows($result)==0)
	{
		echo ReturnError(-404,"公告不存在");
		exit;
	}
	$article=mysqli_fetch_assoc($result);
	// if ($article['banned']==1)
	// {
	// 	echo ReturnError(-102,"此文章已被删除");
	// 	exit;
	// }
	$result=mysqli_query($conn,"SELECT * FROM Users WHERE UserName='".$article['Author']."'");
	if (!$result)
	{
		echo ReturnError(-400,"数据库查询错误");
		exit;
	}
	$author=mysqli_fetch_assoc($result);
	if ($author["Authority"]!=1)
	{
		echo ReturnError(-650,"用户无权限");
		exit;
	}
	if (!$conn->query("DELETE FROM Notice WHERE num=$id"))
	{
		echo ReturnError(-400,"数据库执行错误");
		exit;
	}
	$article=mysqli_fetch_assoc($result);
	$info=array(
		"code"=>0,
		"message"=>"0",
		"ttl"=>1,
		"data"=>array(
			"id"=>$id,
			"state"=>1
		)
	);
	echo ReturnJSON($info);
	exit;
?>







