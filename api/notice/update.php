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
	if ($return==0)
	{
		echo ReturnError(-650,"用户权限太低");
		exit;
	}
	$title=trim($_POST["title"]);
	$content=trim($_POST["content"]);
	$id=trim($_POST["id"]);
	if ($title=='')
	{
		echo ReturnError(-404,"标题不能为空");
		exit;
	}
	if ($content=='')
	{
		echo ReturnError(-404,"文章内容不能为空");
		exit;
	}
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
	if (mysqli_num_rows($result)==0)
	{
		echo ReturnError(-404,"公告不存在");
		exit;
	}
	$row=mysqli_fetch_assoc($result);
	$result=mysqli_query($conn,"SELECT * FROM Users WHERE UserId=".$_COOKIE["DedeUserId"]);
	$author=mysqli_fetch_assoc($result);
	if (!$conn->query("UPDATE Notice SET Title='$title' WHERE num=$id"))
	{
		echo ReturnError(-500,"数据库执行错误");
		exit;
	}
	$createfile=fopen("../".$config['notice-data']."/$id.html", "w");
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
				"bili"=>($author['Bilibili']=="")?"":"https://space.bilibili.com/".$author['Bilibili']."/",
				"header"=>$config["api-server-address"]."/".$config['account-data']."/".$author['UserName']."/header.jpg",
				"background"=>$config["api-server-address"]."/".$config['account-data']."/".$author['UserName']."/background.jpg",
				"authority"=>$author['Authority'],
			),
			'time'=>time(),
			'link'=>$config["api-server-address"]."/".$config['notice-data']."/$id.html"
		)
	);
	echo ReturnJSON($info);
	exit;
?>







