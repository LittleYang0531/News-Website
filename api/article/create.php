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
	$title=trim($_POST["title"]);
	$content=trim($_POST["content"]);
	$column=trim($_POST["column"]);
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
	if ($column=='')
	{
		echo ReturnError(-404,"所属栏目不能为空");
		exit;
	}
	if (!preg_match('/^[0-9]*$/',$column,$matches))
	{
		echo ReturnError(-404,"所属栏目id无效，要求是一个整数");
		exit;
	}
	$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
	if (!$conn)
	{
		echo ReturnError(-500,"无法连接数据库");
		exit;
	}
	$result=mysqli_query($conn,"SELECT * FROM NewsColumn WHERE num=$column");
	if (mysqli_num_rows($result)==0)
	{
		echo ReturnError(-404,"所属栏目id不存在");
		exit;
	}
	$row=mysqli_fetch_assoc($result);
	if (time()>$row["overtime"])
	{
		echo ReturnError(-404,"栏目已停止征集文章");
		exit;
	}
	$result=mysqli_query($conn,"SELECT * FROM Article WHERE ColumnNum=$column");
	$num=1+mysqli_num_rows($result);
	$result=mysqli_query($conn,"SELECT * FROM Users WHERE UserId=".$_COOKIE["DedeUserId"]);
	$author=mysqli_fetch_assoc($result);
	if (!$conn->query("INSERT INTO Article (Title,Author,ColumnNum,num,WatchNum,ReleaseTime) VALUES ('$title','".$author["UserName"]."',$column,$num,0,".time().")"))
	{
		echo ReturnError(-500,"数据库执行错误".mysqli_error($conn));
		exit;
	}
	$createfile=fopen("../".$config['article-data']."/$column/$num.html", "w");
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
			"columnid"=>$column,
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
			'link'=>$config["api-server-address"]."/".$config['article-data']."/$column/$num.html"
		)
	);
	echo ReturnJSON($info);
	exit;
?>







