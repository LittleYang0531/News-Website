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
	if ($return!=1)
	{
		echo ReturnError(-650,"用户权限太低");
		exit;
	}
	$title=trim($_POST["title"]);
	$content=trim($_POST["content"]);
	if ($title=='')
	{
		echo ReturnError(-404,"标题不能为空");
		exit;
	}
	if ($content=='')
	{
		echo ReturnError(-404,"内容不能为空");
		exit;
	}
	$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
	if (!$conn)
	{
		echo ReturnError(-500,"无法连接数据库");
		exit;
	}
	$result=mysqli_query($conn,"SELECT * FROM Notice");
	$num=1+mysqli_num_rows($result);
	$result=mysqli_query($conn,"SELECT * FROM Users WHERE UserId=".$_COOKIE["DedeUserId"]);
	$author=mysqli_fetch_assoc($result);
	if (!$conn->query("INSERT INTO Notice (Title,Author,ReleaseTime,num,WatchNum) VALUES ('$title','".$author['UserName']."',".time().",$num,0)"))
	{
		echo ReturnError(-500,"数据入库失败");
		exit;
	}
	$createfile=fopen("../".$config['notice-data']."/$num.html", "w");
	if (!$createfile)
	{
		echo ReturnError(-500,"写入文件失败");
		exit;
	}
	fwrite($createfile,"<meta charset=\"utf-8\" />".$content);
	$info=array();
	$info=array(
		"code"=>0,
		"message"=>"0",
		"ttl"=>1,
		"data"=>array(
			"id"=>$num,
			"name"=>$title,
			"author"=>array(
				"uid"=>$author['UserId'],
				"name"=>$author['UserName'],
				"birth"=>"****-**-**",
				'school'=>$author['School'],
				"class"=>$author['Class'],
				"grade"=>$author['Grade'],
				"title"=>$author['Title'],
				"mail"=>"**********",
				"QQ"=>"**********",
				"bili"=>($author['Bilibili']=="")?"":"//space.bilibili.com/".$author['Bilibili']."/",
				"header"=>$config["api-server-address"]."/".$config['account-data']."/".$author['UserName']."/header.jpg",
				"background"=>$config["api-server-address"]."/".$config['account-data']."/".$author['UserName']."/background.jpg",
				"authority"=>$author['Authority'],
			),
			"release"=>time(),
			'link'=>$config["api-server-address"]."/".$config['notice-data']."/$num.html"
		)
	);
	echo ReturnJSON($info);
	exit;
?>







