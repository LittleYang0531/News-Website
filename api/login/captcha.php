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
	if (CheckLogin())
	{
		echo ReturnError(-652,"账号已登录");
		exit;
	}
	$goal=trim($_POST['purpose']);
    $email=trim($_POST['email']);
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
	if ($goal=='GET'||$goal=='get')
	{
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
			$mail->Subject = '[育才新闻] 育才新闻登录验证';
			$mail->Body    = '
			<html>
				<body>
					<div class="Main">
						<p>Hey '.$username.'!</p>
						<br>
						<br>
						<p>登录操作需要验证。要完成登录，请在请求登录的设备上输入正确的验证码。</p>
						<p>此验证码的有效期为5分钟，5分钟后请重新申请验证</p>
						<p></p>
						<br>
						<p>登录设备: '.DecodeOS($_SERVER['HTTP_USER_AGENT']).'</p>
						<p>登录浏览器: '.DecodeBrowser($_SERVER['HTTP_USER_AGENT']).'</p>
						<p>登录验证码: '.$code.'</p>
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
					"username"=>$username,
					"email"=>$email,
					"challenge"=>$challenge,
				),
			);
			echo ReturnJSON($info);
			exit;
		}
		catch (Exception $e) 
		{
			echo ReturnError(-400,"邮箱发送错误");
			exit;
		}
	}
	if ($goal=='VERIFY'||$goal=='verify')
	{
		$challenge=trim($_POST['challenge']);
		$key=trim($_POST['key']);
		if ($challenge=='')
		{
			echo ReturnError(-653,"challenge值不能为空");
			exit;
		}
		if ($key=='')
		{
			echo ReturnError(-653,"验证码不能为空");
			exit;
		}
		$sql="SELECT * FROM verify_code WHERE username='$email' and ua='".$_SERVER['HTTP_USER_AGENT']."' and challenge='$challenge'";
		$result=mysqli_query($conn,$sql);
		if (!$result)
		{
			echo ReturnError(-400,"数据库查询错误");
			exit;
		}
		if (mysqli_num_rows($result)==0)
		{
			echo ReturnError(-404,"此challenge值无效!");
			exit;
		}
		while($row=mysqli_fetch_assoc($result))
		{
			if ($row['time']+5*60>=time()&&$row['verifykey']==$key)
			{
				$sql="DELETE FROM verify_code WHERE username='$username' and ua='".$_SERVER['HTTP_USER_AGENT']."' and challenge='$challenge'";
				if (!$conn->query($sql))
				{
					echo ReturnError(-400,"数据库执行错误");
					exit;
				}
				$SESSDATA=md5(GetRandStr(40,"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ/*,.\\/;'[]`~!@#$%^&*()-=_+{}:\"|<>?"));
				$yc_jct=md5(GetRandStr(40,"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"));
				$sql="INSERT INTO LoginData (uid,SESSDATA,CSRF,time,useragent) VALUES (".$user["UserId"].",'$SESSDATA','$yc_jct',".time().",'".$_SERVER['HTTP_USER_AGENT']."')";
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
						"uid"=>$user['UserId'],
						"isLogin"=>true,
						"SESSDATA"=>$SESSDATA,
						"CSRF"=>$yc_jct,
						"DedeUserId__ckMd5"=>md5($user["UserId"]),
						"time"=>time(),
					),
				);
				echo ReturnJSON($info);
				exit;
			}
		}
		echo ReturnError(-629,"验证码错误");
	}
	else
 	{
		echo ReturnError(-653,"调用方法无效");
	    exit;
	}
	exit;
?>







