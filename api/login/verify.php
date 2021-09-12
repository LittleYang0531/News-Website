<?php
	require "../config.php";
	require "../function.php";
    header("Content-Type:text/html;charset=utf-8");
    session_start();
	$mode=$_GET["mode"];
	$username=$_GET['user'];
	$code=$_GET['code'];
	if ($mode=="") $mode="GUI";
	if (CheckLogin())
	{
		if ($mode!="GUI") echo ReturnError(-652,"账号已登录");
		else echo "<script>alert('账号已登录')</script>";
		exit;
	}
    if(($username=='')||($code==''))
    {
		if ($mode!="GUI") echo ReturnError(-404,"用户名或验证密钥不能为空");
		else echo "<script>alert('用户名或验证密钥不能为空')</script>";
        exit;
    }
    $conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
    if(!$conn){
    	if ($mode!="GUI") echo ReturnError(-500,"无法连接数据库");
		else echo "<script>alert('无法连接数据库')</script>";
    	exit;
    }
	$sql="SELECT * FROM Temporary WHERE UserName='$username' and Num='$code'"; 
	$result=mysqli_query($conn,$sql);
	if (!$result)
	{
		if ($mode!="GUI") echo ReturnError(-400,"数据库查询错误");
		else echo "<script>alert('数据库查询错误')</script>";
		exit;
	}
	if (mysqli_num_rows($result)==0)
	{
		if ($mode!="GUI") echo ReturnError(-404,"无效的用户名或验证密钥");
		else echo "<script>alert('无效的用户名或验证密钥')</script>";
		exit;
	}
	$row=mysqli_fetch_assoc($result);
	$password=$row['UserPassword'];$mail=$row['Mail'];
	$sql="SELECT * FROM Users order by UserId DESC";
	$result=mysqli_query($conn,$sql);
	if (!$result)
	{
		if ($mode!="GUI") echo ReturnError(-400,"请求错误");
		else echo "<script>alert('请求错误')</script>";
		exit;
	}
	$row=mysqli_fetch_assoc($result);
	$uid=$row["UserId"]+1;
	$sql="DELETE FROM Temporary where UserName='$username' and Num='$code'";
	if (!$conn->query($sql))
	{
		if ($mode!="GUI") echo ReturnError(-400,"数据库执行错误");
		else echo "<script>alert('数据库执行错误')</script>";
		exit;
	}
	$sql="INSERT INTO Users (UserName,UserPassword,Mail,UserId,banned) VALUES ('$username','$password','$mail',$uid,0);";
	if (!$conn->query($sql))
	{
		if ($mode!="GUI") echo ReturnError(-400,"数据库执行错误");
		else echo "<script>alert('数据库执行错误')</script>";
		exit;
	}
	if (!file_exists("../".$config['account-data']."/$username")) mkdir("../".$config['account-data']."/$username");
	if (file_exists("../".$config['account-data']."/$username/header.jpg")) unlink("../".$config['account-data']."/$username/header.jpg");
	copy("../".$config['account-data']."/default/header.jpg","../".$config['account-data']."/$username/header.jpg");
	$SESSDATA=md5(GetRandStr(40,"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ/*,.\\/;'[]`~!@#$%^&*()-=_+{}:\"|<>?"));
	$yc_jct=md5(GetRandStr(40,"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"));
	$sql = "INSERT INTO LoginData (uid,SESSDATA,CSRF,time,useragent) VALUES (".$uid.",'$SESSDATA','$yc_jct',".time().",'".$_SERVER["HTTP_USER_AGENT"]."')";
	if (!$conn->query($sql))
	{
		if ($mode!="GUI") echo ReturnError(-400,"数据库执行错误");
		else echo "<script>alert('数据库执行错误')</script>";
		exit;
	}
	$info=array(
		"code"=>0,
		"message"=>"0",
		"ttl"=>1,
		"data"=>array(
			"uid"=>$uid,
			"isLogin"=>true,
			"SESSDATA"=>$SESSDATA,
			"CSRF"=>$yc_jct,
			"DedeUserId__ckMd5"=>md5($uid),
			"time"=>time(),
		),
	);
	if ($mode!="GUI") echo ReturnJSON($info);
	else echo "<script>alert('邮箱验证成功!请到登录界面验证您的账号!');window.location.href='".$config["server"]."/".$config["index-path"]."'</script>";
?>