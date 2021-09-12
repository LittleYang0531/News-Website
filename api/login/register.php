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
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $email = trim($_POST['email']);
    $url = trim($_POST['verify']);
	if (CheckLogin())
	{
		echo ReturnError(-652,"账号已登录");
		exit;
	}
    if(($username=='')||($password=='')||($email==''))
    {
		echo ReturnError(-404,"用户名,密码或邮箱不能为空");
        exit;
    }
	if(($url==""))
	{
		echo ReturnError(-404,"验证地址不能为空");
	    exit;
	}
    $conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
    if(!$conn){
    	echo ReturnError(-500,"无法连接数据库");
    	exit;
    }
	$state=CheckNameExist($username);
	if ($state==-1)
	{
    	echo ReturnError(-500,"无法连接数据库");
		exit;
	}
	else if ($state==-2)
	{
		echo ReturnError(-400,"请求错误");
		exit;
	}
    else if ($state)
	{
		echo ReturnError(-652,"重复的用户名");
		exit;
	}
	if (!preg_match('/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/',$email))
	{
		echo ReturnError(-404,"无效的邮箱格式");
		exit;
	}
	$state=CheckEmailExist($email);
	if ($state==-1)
	{
		echo ReturnError(-500,"无法连接数据库");
		exit;
	}
	else if ($state==-2)
	{
		echo ReturnError(-400,"请求错误");
		exit;
	}
	else if ($state)
	{
		echo ReturnError(-652,"重复的邮箱");
		exit;
	}
	$code=GetRandStr(100,"QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm0123456789");
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
		$mail->addAddress($email,$username);
		$mail->isHTML(true);
		$mail->Subject = '[育才新闻] 育才新闻邮箱验证';
		$mail->Body    = '
		<html>
			<body>
				<div class="Main">
					<p>Hey '.$username.'!</p>
					<br>
					<br>
					<p>您的帐号即将注册完成,</p>
					<p>请进入以下地址完成邮箱验证:</p>
					<a href="'.$url.'?user='.$username.'&code='.$code.'">'.$url.'</a>
					<p></p>
					<br>
					<p>谢谢!</p>
					<p>育才新闻团队</p>
				</div>
			</body>
		</html>
		';
		$mail->AltBody = '如果邮件客户端不支持HTML则显示此内容';
		$mail->send();
		$encrypted='';
		openssl_public_encrypt($password,$encrypted,$config["rsa-public-key"]);
		$encrypted=base64_encode($encrypted);
		$sql = "INSERT INTO Temporary (UserName,UserPassword,Num,Mail) VALUES ('$username','$encrypted','$code','$email')";
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
				"username"=>$username,
				"email"=>$email,
				"varify"=>$url,
			),
		);
		echo ReturnJSON($info);
		exit;
	}
	catch (Exception $e) 
	{
		echo ReturnError(-400,"邮件发送错误".$mail->ErrorInfo);
		exit;
	}
	exit;
?>







