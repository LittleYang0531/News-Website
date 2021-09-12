<?php
	require "../config.php";
	require "../".$config["function-path"];
	
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
		echo ReturnError(-500,"数据库查询错误");
		exit;
	}
	if ($return!=1)
	{
		echo ReturnError(-650,"用户权限太低");
		exit;
	}
	$column=trim($_POST["column"]);
	$id=trim($_POST["id"]);
	if ($column=='')
	{
		echo ReturnError(-404,"栏目id不能为空");
		exit;
	}
	if ($id=='')
	{
		echo ReturnError(-404,"文章id不能为空");
		exit;
	}
	if (!preg_match('/^[0-9]*$/',$column,$matches))
	{
		echo ReturnError(-404,"栏目id无效，要求是一个整数");
		exit;
	}
	if (!preg_match('/^[0-9]*$/',$id,$matches))
	{
		echo ReturnError(-404,"文章id无效，要求是一个整数");
		exit;
	}
	$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
	if (!$conn)
	{
		echo ReturnError(-500,"无法连接数据库");
		exit;
	}
	$result=mysqli_query($conn,"SELECT * FROM Article WHERE ColumnNum=$column and num=$id");
	if (!$result)
	{
		echo ReturnError(-400,"数据库查询错误");
		exit;
	}
	if (mysqli_num_rows($result)==0)
	{
		echo ReturnError(-404,"文章不存在");
		exit;
	}
	$article=mysqli_fetch_assoc($result);
	if ($article['banned']==1)
	{
		echo ReturnError(-102,"此文章已被删除");
		exit;
	}
	$result=mysqli_query($conn,"SELECT * FROM Users WHERE UserName='".$article['Author']."'");
	if (!$result)
	{
		echo ReturnError(-400,"数据库查询错误");
		exit;
	}
	$author=mysqli_fetch_assoc($result);
	$result=mysqli_query($conn,"SELECT * FROM Users WHERE UserId=".$_COOKIE["DedeUserId"]);
	if (!$result)
	{
		echo ReturnError(-400,"数据库查询错误");
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
						<p>您的文章"'.$article['Title'].'"已被审核员删除</p>
						<p>可能是由于您的文章出现了一些不该出现的内容</p>
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
		if (!$conn->query("UPDATE Article SET banned=1 WHERE ColumnNum=$column and num=$id"))
		{
			echo ReturnError(-400,"数据库执行错误");
			exit;
		}
		$result=mysqli_query($conn,"SELECT * FROM Article WHERE ColumnNum=$column and num=$id");
		if (!$result)
		{
			echo ReturnError(-400,"数据库查询错误");
			exit;
		}
		$article=mysqli_fetch_assoc($result);
		$info=array(
			"code"=>0,
			"message"=>"0",
			"ttl"=>1,
			"data"=>array(
				"id"=>$id,
				"column"=>$column,
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
		echo ReturnError(-400,"邮件发送错误");
		exit;
	}
	echo ReturnJSON($info);
	exit;
?>







