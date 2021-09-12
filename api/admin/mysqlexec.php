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
	$database=trim($_POST["database"]);
	$command=trim($_POST["command"]);
	$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$database);
	if (!$conn)
	{
		echo ReturnError(-500,"Can't connect to the database!");
		exit;
	}
	$result=mysqli_query($conn,$command);
	if (!$result) 
	{
		echo ReturnError(mysqli_errno($conn),mysqli_error($conn));
		exit;
	}
	if (is_bool($result)) 
	{
		echo ReturnJSON(array(
			"code"=>0,
			"message"=>"",
			"ttl"=>1,
			"data"=>"Query OK.<br><br>",
		));
		exit;
	}
	else 
	{
		if (!mysqli_num_rows($result))
		{
			echo ReturnJSON(array(
				"code"=>0,
				"message"=>"",
				"ttl"=>1,
				"data"=>"Empty Set.<br><br>",
			));
			exit;
		}
		$res="<table border=1 width=100% >";
		$row=mysqli_fetch_assoc($result) ;
		$res.="<tr>";
		foreach ($row as $key=>$value) $res.="<th>".$key."</th>";
		$res.="</tr><tr>";
		foreach ($row as $key=>$value) $res.="<td>".$value."</td>";
		$res.="</tr>";
		while ($row=mysqli_fetch_assoc($result)) 
		{
			$res.="<tr>";
			foreach ($row as $key=>$value) $res.="<td>".$value."</td>";
			$res.="</tr>";
		}
		$res.="</table><br>";
		echo ReturnJSON(array(
			"code"=>0,
			"message"=>"",
			"ttl"=>1,
			"data"=>$res,
		));
	}
?>