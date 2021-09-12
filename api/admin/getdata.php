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
		echo ReturnError(-500,"数据库查询错误");
		exit;
	}
	if ($return!=1)
	{
		echo ReturnError(-650,"用户权限太低");
		exit;
	}
	$mode=trim($_POST["mode"]);
	$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
	if (!$conn)
	{
		echo ReturnError(-500,"无法连接数据库");
		exit;
	}
	$sql="SELECT * FROM VisitData order by time DESC";
	$info=array();
	$result=mysqli_query($conn,$sql); 
	if (!$result) 
	{
		echo ReturnError(-400,"数据库查询错误");
		exit;
	}
	while ($row=mysqli_fetch_assoc($result))
	{
		if ($mode!="js") $info[]=$row;
		else 
		{
			$tmp=array();
			$tmp["ip"]=$row["ip"];
			$tmp["time"]=$row["time"];
			$tmp["browser"]=DecodeBrowser($row["ua"]);
			$tmp["os"]=DecodeOS($row["ua"]);
			$tmp["page"]=$row["page"];
			$info[]=$tmp;
		}
	}
	$ret=array(
		"code"=>0,
		"message"=>"",
		"ttl"=>1,
		"data"=>$info,
	);
	echo ReturnJSON($ret);
?>