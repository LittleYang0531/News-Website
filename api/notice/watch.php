<?php
	require "../config.php";
	require "../function.php";
	$id = trim($_POST['id']);
	header("Content-Type:text/html;charset=utf-8");
	session_start();
	if ($_SERVER['REQUEST_METHOD']!='POST')
	{
		echo ReturnError(-405,"调用方法错误");
		exit;
	}
	if ($id=="")
	{
		echo ReturnError(-404,"传递参数错误");
		exit;
	}
	$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
	if(! $conn ){
		echo ReturnError(-500,"无法连接数据库");
		exit;
	}
	$sql = "SELECT * FROM Notice where num=$id";
	$result = mysqli_query($conn, $sql);
	if (!$result)  
	{
		echo ReturnError(-400,"数据库查询错误");
		exit;
	}
	if (mysqli_num_rows($result)==0)
	{
		echo ReturnError(-404,"传递参数错误");
		exit;
	}
	else 
	{
		$row=mysqli_fetch_assoc($result);$article=array();
		$view=$row['WatchNum']+1;
		if (!$conn->query("UPDATE Notice SET WatchNum=$view WHERE num=$id"))
		{
			echo ReturnError(-400,"数据库执行错误");
			exit;
		}
		$information=array(
			"code"=>0,
			"message"=>"0",
			"ttl"=>1,
			"data"=>array(
				"id"=>$id,
				"name"=>$row['Title'],
				"opentime"=>$row['ReleaseTime'],
					'view'=>$view,
			)
		);
		echo ReturnJSON($information);
	}
?>







