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
	$wiki=trim($_POST["wiki"]);
	$version=trim($_POST["version"]);
	if ($wiki=='')
	{
		echo ReturnError(-404,"词条id不能为空");
		exit;
	}
	if ($version=='')
	{
		echo ReturnError(-404,"版本号不能为空");
		exit;
	}
	if (!preg_match('/^[0-9]*$/',$wiki,$matches))
	{
		echo ReturnError(-404,"词条id无效，要求是一个整数");
		exit;
	}
	if (!preg_match('/^[0-9]*$/',$version,$matches))
	{
		echo ReturnError(-404,"版本号无效，要求是一个整数");
		exit;
	}
	$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
	if (!$conn)
	{
		echo ReturnError(-500,"无法连接数据库");
		exit;
	}
	$result=mysqli_query($conn,"SELECT * FROM wikis WHERE id=$wiki");
	if (!$result)
	{
		echo ReturnError(-400,"请求错误");
		exit;
	}
	if (mysqli_num_rows($result)==0)
	{
		echo ReturnError(-404,"词条id不存在");
		exit;
	}
	$wikis=mysqli_fetch_assoc($result);
	$result=mysqli_query($conn,"SELECT * FROM wiki WHERE id=$wiki and version=$version");
	if (!$result)
	{
		echo ReturnError(-400,"请求错误");
		exit;
	}
	if (mysqli_num_rows($result)==0)
	{
		echo ReturnError(-404,"此版本不存在");
		exit;
	}
	$article=mysqli_fetch_assoc($result);
	if ($article['banned']==1)
	{
		echo ReturnError(-102,"此版本已被删除");
		exit;
	}
	$result=mysqli_query($conn,"SELECT * FROM Users WHERE UserName='".$article['Author']."'");
	if (!$result)
	{
		echo ReturnError(-400,"请求错误");
		exit;
	}
	$author=mysqli_fetch_assoc($result);
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
		$mail->addAddress($author['Mail'],$author['UserName']);
		$mail->isHTML(true);
		$mail->Subject = '[育才新闻] 育才新闻文章审核';
		$mail->Body    = '
		<html>
			<body>
				<div class="Main">
					<div>
						<p>Hey '.$author['UserName'].'!</p>
						<br>
						<br>
						<p>您修改的词条"'.$wikis['Title'].'"(version:'.$article['version'].')已被审核员删除</p>
						<p>可能是由于您添加了一些不该出现在此处的内容</p>
						<p>如果没有上述情况,可发送一封申诉邮件至littleyang0531@outlook.com进行申诉</p>
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
		if (!$conn->query("UPDATE wiki SET banned=1 WHERE id=$wiki and version=$version"))
		{
			echo ReturnError(-400,"请求错误");
			exit;
		}
		$result=mysqli_query($conn,"SELECT * FROM wiki WHERE id=$wiki and version=$version");
		if (!$result)
		{
			echo ReturnError(-400,"请求错误");
			exit;
		}
		$article=mysqli_fetch_assoc($result);
		$info=array(
			"code"=>0,
			"message"=>"0",
			"ttl"=>1,
			"data"=>array(
				"wikiid"=>$wiki,
				"version"=>$version,
				"author"=>$author['UserName'],
				"operator"=>$operator['UserName'],
				"state"=>$article['banned']
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







