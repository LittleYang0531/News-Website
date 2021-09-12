<?php
	require "../config.php";
	require "../function.php";
	$id = trim($_POST['id']);
	$type = trim($_POST['type']);
	// $id = $_GET['id'];
	// $type = $_GET['type'];
	// $version = trim($_POST['version']);
	header("Content-Type:text/html;charset=utf-8");
	session_start();
	if ($_SERVER['REQUEST_METHOD']!='POST')
	{
		echo ReturnError(-405,"调用方法错误");
		exit;
	}
	// if ($type!="wikis"&&$type!="wiki")
	// {
	// 	echo ReturnError(-404,"传递参数错误");
	//     exit;
	// }
	if ($type!="wikis")
	{
		echo ReturnError(-404,"传递参数错误");
	    exit;
	}
	// if ($type==""||$id==""||($type=="wiki"&&$version==""))
	// {
	// 	echo ReturnError(-404,"传递参数错误");
	// 	exit;
	// }
	if ($type==""||$id=="")
	{
		echo ReturnError(-404,"传递参数错误");
		exit;
	}
	$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
	if(! $conn ){
		echo ReturnError(-500,"无法连接数据库");
		exit;
	}
	$sql = "";
	if ($type=="wikis") $sql = "SELECT * FROM wikis where id=$id";
	// if ($type=="wiki") $sql = "SELECT * FROM wiki where id=$id and version=$version";
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
		if ($type=="wikis"&&!$conn->query("UPDATE wikis SET WatchNum=$view WHERE id=$id"))
		{
			echo ReturnError(-400,"数据库执行错误");
			exit;
		}
		// else if ($type=="wiki"&&!$conn->query("UPDATE wiki SET WatchNum=$view WHERE id=$id and version=$version"))
		// {
		// 	echo ReturnError(-400,"数据库执行错误");
		// 	exit;
		// }
		if (CheckLogin())
		{
			// if ($type=="wiki"&&!$conn->query("DELETE FROM WatchHistory WHERE type='wiki' and id1=$id and id2=$version and uid=".$_COOKIE['DedeUserId']))
			// {
			// 	echo ReturnError(-400,"数据库执行错误");
			// 	exit;
			// }
			// else 
			if ($type=="wikis"&&!$conn->query("DELETE FROM WatchHistory WHERE type='wikis' and id1=$id and uid=".$_COOKIE['DedeUserId']))
			{
				echo ReturnError(-400,"数据库执行错误");
				exit;
			}
			if ($type=="wikis"&&!$conn->query("INSERT INTO WatchHistory (type,id1,uid,time,useragent) VALUE ('wikis',$id,".$_COOKIE['DedeUserId'].",".time().",'".$_SERVER['HTTP_USER_AGENT']."')"))
			{
				echo ReturnError(-400,"数据库执行错误");
				exit;
			}
			// else if ($type=="wiki"&&!$conn->query("INSERT INTO WatchHistory (type,id1,id2,uid,time,useragent) VALUE ('wiki',$id,$version,".$_COOKIE['DedeUserId'].",".time().",'".$_SERVER['HTTP_USER_AGENT']."')"))
			// {
			// 	echo ReturnError(-400,"数据库执行错误");
			// 	exit;
			// }
		}
		// if ($type=="wiki")
		// {
		// 	$information=array(
		// 		"code"=>0,
		// 		"message"=>"0",
		// 		"ttl"=>1,
		// 		"data"=>array(
		// 			"type"=>$type,
		// 			"id"=>$id,
		// 			"version"=>$version,
		// 			"updatetime"=>$row['UpdateTime'],
		// 			'view'=>$view,
		// 		)
		// 	);
		// 	echo ReturnJSON($information);
		// }
		// else
		// {
			$information=array(
				"code"=>0,
				"message"=>"0",
				"ttl"=>1,
				"data"=>array(
					"type"=>$type,
					"id"=>$id,
					"name"=>$row['Title'],
					"opentime"=>$row['CreateTime'],
					'view'=>$view,
				)
			);
			echo ReturnJSON($information);
		// }
	}
?>







