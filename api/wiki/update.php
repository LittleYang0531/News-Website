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
	$content=trim($_POST["content"]);
	$wiki=trim($_POST["wiki"]);
	$reason=trim($_POST["reason"]);
	if ($content=='')
	{
		echo ReturnError(-404,"文章内容不能为空");
		exit;
	}
	if ($wiki=='')
	{
		echo ReturnError(-404,"所属词条不能为空");
		exit;
	}
	if ($reason=='')
	{
		echo ReturnError(-404,"修改原因不能为空");
		exit;
	}
	if (!preg_match('/^[0-9]*$/',$wiki,$matches))
	{
		echo ReturnError(-404,"所属词条id无效，要求是一个整数");
		exit;
	}
	$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
	if (!$conn)
	{
		echo ReturnError(-500,"无法连接数据库");
		exit;
	}
	$result=mysqli_query($conn,"SELECT * FROM wikis WHERE id=$wiki");
	if (mysqli_num_rows($result)==0)
	{
		echo ReturnError(-404,"所属词条id不存在");
		exit;
	}
	$result=mysqli_query($conn,"SELECT * FROM wiki WHERE id=$wiki");
	$num=1+mysqli_num_rows($result);
	$result=mysqli_query($conn,"SELECT * FROM Users WHERE UserId=".$_COOKIE["DedeUserId"]);
	$author=mysqli_fetch_assoc($result);
	if (!$conn->query("INSERT INTO wiki (Author,UpdateTime,version,id,reason,WatchNum) VALUES ('".$author["UserName"]."',".time().",$num,$wiki,'$reason',0)"))
	{
		echo ReturnError(-500,"数据库执行错误".mysqli_error($conn));
		exit;
	}
	$createfile=fopen("../".$config['wiki-data']."/$wiki/$num.html", "w");
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
			"opentime"=>time(),
			"version"=>$num,
			"wikiid"=>$wiki,
			"reason"=>$reason,
			"link"=>$config["api-server-address"]."/".$config['wiki-data']."/$wiki/$num.html",
		)
	);
	echo ReturnJSON($info);
	exit;
?>







