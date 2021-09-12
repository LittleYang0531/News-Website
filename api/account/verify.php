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
    $email=trim($_POST['email']);
	$check=trim($_POST['check']);
	if ($check&&!CheckLogin())
	{
		echo ReturnError(-652,"账号未登录");
		exit;
	}
    if($email=='')
    {
		echo ReturnError(-653,"邮箱不能为空");
        exit;
    }
	if ($_SERVER['HTTP_USER_AGENT']=="")
	{
		echo ReturnError(-404,"无效的UserAgent");
		exit;
	}
    $conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
    if(!$conn){
    	echo ReturnError(-500,"无法连接数据库");
    	exit;
    }
	$sql="SELECT * FROM Users WHERE Mail='$email'";
	$result=mysqli_query($conn,$sql); 
	if (!$result)
	{
		echo ReturnError(-400,"数据库查询错误");
		exit;
	}
	$row=mysqli_fetch_assoc($result);
	$username=$row['UserName'];
	if ($check) {
		$sql="SELECT * FROM Users WHERE Mail='$email'";
		$result=mysqli_query($conn,$sql); 
		if (!$result)
		{
			echo ReturnError(-400,"数据库查询错误");
			exit;
		}
		if (mysqli_num_rows($result)==0)
		{
			echo ReturnError(-626,"此邮箱没有创建账号!");
			exit;
		}
		$row=mysqli_fetch_assoc($result);
		if ($row['banned']==1)
		{
			echo ReturnError(-102,"此账号已被封禁!");
			exit;
		}
		$username=$row['UserName'];
		$user=$row;
		if ($user['UserId']!=$_COOKIE["DedeUserId"]) {
			echo ReturnError(-629,"邮箱与账户不对应");
			exit;
		}
	}
	$code=GetRandStr(6,"0123456789");
	$challenge=GetRandStr(64,"0123456789QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm");
	$sql="INSERT INTO verify_code (username,verifykey,challenge,time,ua) VALUES ('$email',$code,'$challenge',".time().",'".$_SERVER['HTTP_USER_AGENT']."')";
	if (!$conn->query($sql))
	{
		echo ReturnError(-400,"数据库执行错误");
		exit;
	}
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
		$mail->Subject = '[育才新闻] 育才新闻操作警告&验证';
		$mail->Body    = '
		<html>
			<body>
				<div class="Main">
					<p>Hey '.$username.'!</p>
					<br>
					<br>
					<p>您接下来对账户的操作需要验证。要完成操作，请在请求的设备上输入正确的验证码</p>
					<p>该操作可能为以下操作之一:修改登录密码 与 修改绑定邮箱</p>
					<p>警告:一旦执行以上操作，全平台清空登录态<p>
					<p>若以上操作非您本人操作，请忽略该邮件并不要把该验证码给任何人</p>
					<p>若由于您的操作失误所造成的损失，请自行承担其后果</p>
					<p>此验证码的有效期为5分钟，5分钟后请重新申请验证</p>
					<p></p>
					<br>
					<p>请求设备: '.DecodeOS($_SERVER['HTTP_USER_AGENT']).'</p>
					<p>请求浏览器: '.DecodeBrowser($_SERVER['HTTP_USER_AGENT']).'</p>
					<p>请求验证码: '.$code.'</p>
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
		$info=array(
			"code"=>0,
			"message"=>"0",
			"ttl"=>1,
			"data"=>array(
				"email"=>$email,
				"challenge"=>$challenge,
			),
		);
		echo ReturnJSON($info);
		exit;
	}
	catch (Exception $e) 
	{
		echo ReturnError(-400,"邮箱发送错误".$mail->ErrorInfo);
		exit;
	}
	exit;
?>