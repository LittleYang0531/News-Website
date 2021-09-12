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
	$state=CheckLogin();
	if (!$state)
	{
		echo "<script>alert('请先登录!');window.history.back(-1);</script>";
		exit;
	}
	$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
	$result=mysqli_query($conn,"SELECT * FROM Users WHERE UserId=".$_COOKIE['DedeUserId']);
	if (!$result)
	{
		echo ReturnError(-400,"请求错误");
		exit;
	}
	$row=mysqli_fetch_assoc($result);
	if(isset($_POST['setting-background-submit']))
	{
		$temp=explode(".",$_FILES["setting-background"]["name"]);
		if ($_FILES["setting-background"]["error"]>0)
		{
			echo "<script>alert('错误:".$_FILES["setting-background"]["error"]."');window.history.back(-1);</script>";
			exit;
		}
		copy($_FILES["setting-background"]["tmp_name"],"../".$config["account-data"]."/".$row["UserName"]."/background.jpg");
		// echo $temp."../".$config["account-data"]."/".$row["UserName"]."/header.jpg";
		// chmod("../".$config["account-data"]."/".$row["UserName"]."/background.jpg",755);
		echo "<script>alert('上传成功');window.location.href='".$config["server"]."/".$config["setting-path"]."';</script>";exit;
	}
?>