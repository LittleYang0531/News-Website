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
	$uid=trim($_POST["uid"]);
	if ($uid=='')
	{
		echo ReturnError(-404,"用户id不能为空");
		exit;
	}
	if (!preg_match('/^[0-9]*$/',$uid,$matches))
	{
		echo ReturnError(-404,"所属用户id无效，要求是一个整数");
		exit;
	}
	if ($uid==$_COOKIE["DedeUserId"])
	{
		echo ReturnError(-404,"不能关注自己");
		exit;
	}
	$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
	if (!$conn)
	{
		echo ReturnError(-500,"无法连接数据库");
		exit;
	}
	$result=mysqli_query($conn,"SELECT * FROM Users WHERE UserId=$uid");
	if (!$result)
	{
		echo ReturnError(-400,"数据库查询错误");
		exit;
	}
	if (mysqli_num_rows($result)==0)
	{
		echo ReturnError(-404,"用户不存在");
		exit;
	}
	$user=mysqli_fetch_assoc($result);
	if ($user['banned']==1)
	{
		echo ReturnError(-102,"此账号已被封停");
		exit;
	}
	$sql="SELECT * FROM Relation WHERE fromid=".$_COOKIE["DedeUserId"]." and toid=$uid";
	$result=mysqli_query($conn,$sql);
	if (!$result)
	{
		echo ReturnError(-400,"数据库查询错误");
		exit;
	}
	$new_state=!mysqli_num_rows($result);
	if ($new_state) 
		if (!$conn->query("INSERT INTO Relation (fromid,toid,time) VALUES (".$_COOKIE["DedeUserId"].",$uid,".time().");"))
		{
			echo ReturnError(-400,"数据库执行错误");
			exit;
		}
		else ;
	else 
		if (!$conn->query("DELETE FROM Relation WHERE fromid=".$_COOKIE["DedeUserId"]." and toid=$uid"))
		{
			echo ReturnError(-400,"数据库执行错误");
			exit;
		}
	$info=array(
		"code"=>0,
		"message"=>"0",
		"ttl"=>1,
		"data"=>array(
			"from"=>$_COOKIE["DedeUserId"],
			"to"=>$uid,
			"state"=>$new_state,
		)
	);
	echo ReturnJSON($info);
	exit;
?>