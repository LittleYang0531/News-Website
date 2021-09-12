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
	if (CheckLogin())
	{
		echo ReturnError(-652,"账号已登录");
		exit;
	}
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
	// echo $password;
	// echo $config["rsa-private-key"]."\n".$password;
    if(($username=='')||($password==''))
    {
		echo ReturnError(-653,"用户名或密码不能为空");
        exit;
    }
	if ($_SERVER['HTTP_USER_AGENT']=="")
	{
		echo ReturnError(-404,"无效的UserAgent");
		exit;
	}
    $conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
    if(! $conn ){
    	echo ReturnError(-500,"无法连接数据库");
    	exit;
    }
    $sql = "SELECT UserName,UserPassword,UserId,Mail FROM Users";
	$result = mysqli_query($conn, $sql);
	if (!$result)
	{
		echo ReturnError(-400,"数据库查询错误");
		exit;
	}
	if (mysqli_num_rows($result) > 0)
	{
        while($row = mysqli_fetch_assoc($result))
        {
			openssl_private_decrypt(base64_decode($row["UserPassword"]),$realpass,$config["rsa-private-key"]);
			// echo $realpass."\n";
			openssl_private_decrypt(base64_decode($password),$checkpass,$config["rsa-private-key"]);
			// echo $checkpass."\n";
            if (($username==$row["Mail"]||$username==$row["UserName"])&&$realpass==$checkpass)
            {
				if ($row['banned']==1)
				{
					echo ReturnError(-102,"此账号已被封禁!");
					exit;
				}
				$SESSDATA=md5(GetRandStr(40,"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ/*,.\\/;'[]`~!@#$%^&*()-=_+{}:\"|<>?"));
				$yc_jct=md5(GetRandStr(40,"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"));
				$sql = "INSERT INTO LoginData (uid,SESSDATA,CSRF,time,useragent) VALUES (".$row['UserId'].",'$SESSDATA','$yc_jct',".time().",'".$_SERVER["HTTP_USER_AGENT"]."')";
				if (!$conn->query($sql))
				{
					echo ReturnError(-400,"数据库执行错误");
					exit;
				}
				$info=array(
					"code"=>0,
					"message"=>"0",
					"ttl"=>1,
					"data"=>array(
						"uid"=>$row['UserId'],
						"isLogin"=>true,
						"SESSDATA"=>$SESSDATA,
						"CSRF"=>$yc_jct,
						"DedeUserId__ckMd5"=>md5($row["UserId"]),
						"time"=>time(),
					),
				);
				echo ReturnJSON($info);
				exit;
            }
        }
    }
	echo ReturnError(-629,"用户名或密码错误");
	exit;
?>







