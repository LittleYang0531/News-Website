<?php
	require "../config.php";
	require "../function.php";
	
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	
	require '../'.$config["extension-data"].'/PHPMailer/Exception.php';
	require '../'.$config["extension-data"].'/PHPMailer/PHPMailer.php';
	require '../'.$config["extension-data"].'/PHPMailer/SMTP.php';
	
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
		echo ReturnError(-500,"数据库查询失败");
		exit;
	}
	if ($return!=2)
	{
		echo ReturnError(-650,"用户权限太低");
		exit;
	}
	$uid=trim($_POST["uid"]);
	if ($uid=='')
	{
		echo ReturnError(-404,"用户id不能为空");
		exit;
	}
	if (!preg_match('/^[0-9]*$/',$uid,$matches))
	{
		echo ReturnError(-404,"所属用户id无效，要求是一个整数");
		exit;
	}
	$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
	if (!$conn)
	{
		echo ReturnError(-500,"无法连接数据库");
		exit;
	}
	$result=mysqli_query($conn,"SELECT * FROM Users WHERE UserId=$uid");
	if (!$result)
	{
		echo ReturnError(-400,"请求错误");
		exit;
	}
	if (mysqli_num_rows($result)==0)
	{
		echo ReturnError(-404,"用户不存在");
		exit;
	}
	$user=mysqli_fetch_assoc($result);
	if ($user['banned']==0)
	{
		echo ReturnError(-102,"此账号未被封停");
		exit;
	}
	$result=mysqli_query($conn,"SELECT * FROM Users WHERE UserId=".$_COOKIE["DedeUserId"]);
	if (!$result)
	{
		echo ReturnError(-400,"请求错误");
		exit;
	}
	$operator=mysqli_fetch_assoc($result);
	$mail=new PHPMailer(true);
	try {
		$mail->CharSet ="UTF-8";   
		$mail->isSMTP();             
		$mail->Host = $config["email-server"];          
		$mail->SMTPAuth = true;    
		$mail->Username = $config["email-account"];    
		$mail->Password = $config["email-password"];  
		$mail->SMTPSecure = $config["email-protocol"];
		$mail->Port = $config["email-port"]; 
	
		$mail->From         = $config["email-account"];
		$mail->FromName     = $config["email-from"];
		$mail->addAddress($user['Mail'],$user['UserName']);
		$mail->isHTML(true);
		$mail->Subject = '[育才新闻] 育才新闻账号审核';
		$mail->Body    = '
		<html>
			<body>
				<div class="Main">
					<div>
						<p>Hey '.$user['UserName'].'!</p>
						<br>
						<br>
						<p>您的帐号已被恢复</p>
						<p>可能是由于审核员审核时操作失误或其他原因</p>
						<p>为您带来不便,深感抱歉</p>
						<p></p>
						<br>
						<p>Tips:如果发现有审核员乱审核的现象</p>
						<p>欢迎发送举报邮件到littleyang0531@outlook.com</p>
						<p>审核员:'.$operator['UserName'].'</p>
						<p></p>
						<br>
						<p>谢谢!</p>
						<p>育才新闻团队</p>
					</div>
				</div>
			</body>
		</html>
		';
		$mail->AltBody = '如果邮件客户端不支持HTML则显示此内容';
		$mail->send();
		if (!$conn->query("UPDATE Users SET banned=0 WHERE UserId=$uid"))
		{
			echo ReturnError(-400,"请求错误");
			exit;
		}
		$result=mysqli_query($conn,"SELECT * FROM Users WHERE UserId=$uid");
		if (!$result)
		{
			echo ReturnError(-400,"请求错误");
			exit;
		}
		$user=mysqli_fetch_assoc($result);
		$info=array(
			"code"=>0,
			"message"=>"0",
			"ttl"=>1,
			"data"=>array(
				"id"=>$uid,
				"name"=>$user['UserName'],
				"operator"=>$operator['UserName'],
				"state"=>$user['banned']
			)
		);
		echo ReturnJSON($info);
		exit;
	}
	catch (Exception $e) 
	{
		echo ReturnError(-400,"请求错误");
		exit;
	}
	echo ReturnJSON($info);
	exit;
?>







