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
		echo "<script>alert('数据库查询错误!');window.history.back(-1);</script>";
		exit;
	}
	$row=mysqli_fetch_assoc($result);
	if(isset($_POST['setting-header-submit']))
	{
		if (!is_uploaded_file($_FILES["setting-header"]["tmp_name"]))
		{
			echo "图片不存在!";
			echo "<script>window.location.href='".$config["server"]."/".$config["setting-path"]."';</script>";exit;
		}
		if ($_FILES["setting-header"]["error"]>0)
		{
			$error_disc="";
			switch($_FILES["setting-header"]["error"])
			{
				case 1:$error_disc="File is too big!";break;
				case 2:$error_disc="File is too big!";break;
				case 3:$error_disc="File isn't upload completely!";break;
				case 4:$error_disc="No file was uploaded!";break;
				case 5:$error_disc="The file size was 0!";break;
				default:$error_disc="Unknown Error!";break;
			}
			echo "<script>alert('错误:".$error_disc."');window.history.back(-1);</script>";
			exit;
		}
		copy($_FILES["setting-header"]["tmp_name"],"../".$config["account-data"]."/".$row["UserName"]."/header.jpg");
		// echo $_FILES["setting-header"]["tmp_name"]."../".$config["account-data"]."/".$row["UserName"]."/header.jpg";
		// echo chmod("../".$config["account-data"]."/".$row["UserName"]."/header.jpg",0755);
		// var_dump($temp);
		echo "<script>alert('上传成功');</script>";
		echo "<script>window.location.href='".$config["server"]."/".$config["setting-path"]."';</script>";exit;
	}
?>