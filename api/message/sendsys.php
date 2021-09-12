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
	if (GetAuthority()!=1)
	{
		echo ReturnError(-650,"用户权限太低");
		exit;
	}
	if ($_SERVER['REQUEST_METHOD']!='POST')
	{
		echo ReturnError(-405,"调用方法错误");
		exit;
	}
	$uid=trim($_POST["uid"]);
	$title=trim($_POST["title"]);
	$content=trim($_POST["content"]);
	if ($uid==""||$content=="")
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
	$sql="INSERT INTO SysMessage (toid,time,title,content) VALUE ($uid,".time().",'$title','$content')";\
	if (!$conn->query($sql))
	{
		echo ReturnError(-400,"数据库执行错误");
		exit;
	}
	$sql = "SELECT * FROM Users where UserId=$uid";
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
			"from"=>$_COOKIE["DedeUserId"],
			"to"=>$uid,
			"time"=>time(),
			"content"=>$content,
		)
	);
	echo ReturnJSON($info);
	exit;
?>