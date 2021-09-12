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
	$page=trim($_POST["page"]);
	$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
	if (!$conn)
	{
		echo ReturnError(-500,"无法连接数据库");
		exit;
	}
	function get_real_ip()
	{
	    $ip=FALSE;
	    if(!empty($_SERVER["HTTP_CLIENT_IP"])){
	        $ip = $_SERVER["HTTP_CLIENT_IP"];
	    }
	    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	        $ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
	        if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
	        for ($i = 0; $i < count($ips); $i++) {
	            if (!eregi ("^(10│172.16│192.168).", $ips[$i])) {
	                $ip = $ips[$i];
	                break;
	            }
	        }
	    }
	    return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
	}
	$sql="INSERT INTO VisitData (time,ip,ua,page) VALUE (".time().",'".get_real_ip()."','".$_SERVER["HTTP_USER_AGENT"]."','".$config["$page-path"]."')";
	if (!$conn->query($sql)) 
	{
		echo ReturnError(-400,"数据库执行错误");
		exit;
	}
	$info=array(
		"code"=>0,
		"message"=>"",
		"ttl"=>1,
		"data"=>
		array(
			"upload"=>1,
			"time"=>time(),
			"ip"=>get_real_ip(),
			"ua"=>$_SERVER["HTTP_USER_AGENT"],
			"page"=>$config["$page-path"],
		)
	);
	echo ReturnJSON($info);
?>