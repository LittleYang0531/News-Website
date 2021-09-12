<?php
	require_once "config/common.php";
	echo "SYSTEM> 正在连接数据库...\n";
	$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
	if (!$conn)
	{
		echo "[".date("Y-m-d H:i:s",time())."][Error]: 无法连接数据库\n";
		echo "请检查数据库地址，账号，密码或数据库名是否有误";
		exit;
	}
	echo "SYSTEM> 数据库连接成功!\n";
	
	echo "SYSTEM> 开始修改表Article:\n";
	echo "SYSTEM> 正在将表Article中字段ReleaseTime的类型改为varchar...\n";
	if (!$conn->query("alter table Article modify ReleaseTime varchar(8192)"))
	{
		echo "[".date("Y-m-d H:i:s",time())."][Error]: 无法修改数据库,".mysqli_error($conn)."\n";
		exit;
	}
	$result=mysqli_query($conn,"SELECT * FROM Article");$i=0;
	if (!$result)
	{
		echo "数据库查询错误";
		exit;
	}
	while ($row=mysqli_fetch_assoc($result))
	{
		$isMatched=preg_match('/^\d{4}-\d{1,2}-\d{1,2}/',$row["ReleaseTime"],$matches);
		if (!$isMatched) echo "[".date("Y-m-d H:i:s",time())."][Warning]: 第".++$i."行的ReleaseTime数据不合法，已自动跳过处理\n";
		else 
		{
			$now_time=strtotime($row['ReleaseTime']);
			echo "SYSTEM> 正在将第".++$i."行中字段ReleaseTime的数据从".$row['ReleaseTime']."改为".$now_time."\n";
			if (!$conn->query("UPDATE Article SET ReleaseTime=".$now_time." WHERE Title='".$row['Title']."'"))
			{
				echo "[".date("Y-m-d H:i:s",time())."][Error]: 无法修改数据库,".mysqli_error($conn)."\n";
				exit;
			}	
		}
	}
	echo "SYSTEM> 正在将表Article中字段ReleaseTime的类型改为int...\n";
	if (!$conn->query("alter table Article modify ReleaseTime int(255)"))
	{
		echo "[".date("Y-m-d H:i:s",time())."][Error]: 无法修改数据库,".mysqli_error($conn)."\n";
		exit;
	}
	echo "SYSTEM> 正在表Article中添加字段banned...\n";
	if (!$conn->query("alter table Article add column banned int(11) ;"))  echo "[".date("Y-m-d H:i:s",time())."][Warning]: banned字段似乎已被添加，已自动跳过处理\n";
	echo "SYSTEM> 表Article修改完毕!\n";
	// sleep(2);
	
	echo "SYSTEM> 开始修改表NewsColumn:\n";
	echo "SYSTEM> 正在将表NewsColumn中字段starttime的类型改为varchar...\n";
	if (!$conn->query("alter table NewsColumn modify starttime varchar(8192)"))
	{
		echo "[".date("Y-m-d H:i:s",time())."][Error]: 无法修改数据库,".mysqli_error($conn)."\n";
		exit;
	}
	echo "SYSTEM> 正在将表NewsColumn中字段overtime的类型改为varchar...\n";
	if (!$conn->query("alter table NewsColumn modify overtime varchar(8192)"))
	{
		echo "[".date("Y-m-d H:i:s",time())."][Error]: 无法修改数据库,".mysqli_error($conn)."\n";
		exit;
	}
	$result=mysqli_query($conn,"SELECT * FROM NewsColumn");$i=0;
	if (!$result)
	{
		echo "数据库查询错误";
		exit;
	}
	while ($row=mysqli_fetch_assoc($result))
	{
		$isMatched=preg_match('/^\d{4}-\d{1,2}-\d{1,2}/',$row["starttime"],$matches);
		if (!$isMatched) echo "[".date("Y-m-d H:i:s",time())."][Warning]: 第".++$i."行的starttime数据不合法，已自动跳过处理\n";
		else 
		{
			$starttime=strtotime($row['starttime']);
			echo "SYSTEM> 正在将第".++$i."行中字段starttime的数据从".$row['starttime']."改为".$starttime."\n";
			if (!$conn->query("UPDATE NewsColumn SET starttime=".$starttime." WHERE name='".$row['name']."'"))
			{
				echo "[".date("Y-m-d H:i:s",time())."][Error]: 无法修改数据库,".mysqli_error($conn)."\n";
				exit;
			}	
		}
		$isMatched=preg_match('/^\d{4}-\d{1,2}-\d{1,2}/',$row["overtime"],$matches);
		if (!$isMatched) echo "[".date("Y-m-d H:i:s",time())."][Warning]: 第".$i."行的overtime数据不合法，已自动跳过处理\n";
		else 
		{
			$overtime=strtotime($row['overtime']);
			echo "SYSTEM> 正在将第".$i."行中字段overtime的数据从".$row['overtime']."改为".$overtime."\n";
			if (!$conn->query("UPDATE NewsColumn SET overtime=".$overtime." WHERE name='".$row['name']."'"))
			{
				echo "[".date("Y-m-d H:i:s",time())."][Error]: 无法修改数据库,".mysqli_error($conn)."\n";
				exit;
			}	
		}
	}
	echo "SYSTEM> 正在将表NewsColumn中字段starttime的类型改为int...\n";
	if (!$conn->query("alter table NewsColumn modify starttime int(255)"))
	{
		echo "[".date("Y-m-d H:i:s",time())."][Error]: 无法修改数据库,".mysqli_error($conn)."\n";
		exit;
	}
	echo "SYSTEM> 正在将表NewsColumn中字段overtime的类型改为int...\n";
	if (!$conn->query("alter table NewsColumn modify overtime int(255)"))
	{
		echo "[".date("Y-m-d H:i:s",time())."][Error]: 无法修改数据库,".mysqli_error($conn)."\n";
		exit;
	}
	echo "SYSTEM> 表NewsColumn修改完毕!\n";
	// sleep(2);
	
	echo "SYSTEM> 开始修改表wiki:\n";
	echo "SYSTEM> 正在将表wiki中字段UpdateTime的类型改为varchar...\n";
	if (!$conn->query("alter table wiki modify UpdateTime varchar(8192)"))
	{
		echo "[".date("Y-m-d H:i:s",time())."][Error]: 无法修改数据库,".mysqli_error($conn)."\n";
		exit;
	}
	$result=mysqli_query($conn,"SELECT * FROM wiki");$i=0;
	if (!$result)
	{
		echo "数据库查询错误";
		exit;
	}
	while ($row=mysqli_fetch_assoc($result))
	{
		$isMatched=preg_match('/^\d{4}-\d{1,2}-\d{1,2}/',$row["UpdateTime"],$matches);
		if (!$isMatched) echo "[".date("Y-m-d H:i:s",time())."][Warning]: 第".++$i."行的UpdateTime数据不合法，已自动跳过处理\n";
		else 
		{
			$now_time=strtotime($row['UpdateTime']);
			echo "SYSTEM> 正在将第".++$i."行中字段UpdateTime的数据从".$row['UpdateTime']."改为".$now_time."\n";
			if (!$conn->query("UPDATE wiki SET UpdateTime=".$now_time." WHERE version=".$row['version']." and id=".$row['id']))
			{
				echo "[".date("Y-m-d H:i:s",time())."][Error]: 无法修改数据库,".mysqli_error($conn)."\n";
				exit;
			}	
		}
	}
	echo "SYSTEM> 正在将表wiki中字段UpdateTime的类型改为int...\n";
	if (!$conn->query("alter table wiki modify UpdateTime int(255)"))
	{
		echo "[".date("Y-m-d H:i:s",time())."][Error]: 无法修改数据库,".mysqli_error($conn)."\n";
		exit;
	}
	echo "SYSTEM> 正在表wiki中添加字段WatchNum...\n";
	if (!$conn->query("alter table wiki add column WatchNum int(255) ;")) echo "[".date("Y-m-d H:i:s",time())."][Warning]: WatchNum字段似乎已被添加，已自动跳过处理\n";
	echo "SYSTEM> 正在表wiki中添加字段banned...\n";
	if (!$conn->query("alter table wiki add column banned int(11) ;"))  echo "[".date("Y-m-d H:i:s",time())."][Warning]: banned字段似乎已被添加，已自动跳过处理\n";
	echo "SYSTEM> 表wiki修改完毕!\n";
	// sleep(2);
	
	echo "SYSTEM> 开始修改表wikis:\n";
	echo "SYSTEM> 正在将表wikis中字段CreateTime的类型改为varchar...\n";
	if (!$conn->query("alter table wikis modify CreateTime varchar(8192)"))
	{
		echo "[".date("Y-m-d H:i:s",time())."][Error]: 无法修改数据库,".mysqli_error($conn)."\n";
		exit;
	}
	$result=mysqli_query($conn,"SELECT * FROM wikis");$i=0;
	if (!$result)
	{
		echo "数据库查询错误";
		exit;
	}
	while ($row=mysqli_fetch_assoc($result))
	{
		$isMatched=preg_match('/^\d{4}-\d{1,2}-\d{1,2}/',$row["CreateTime"],$matches);
		if (!$isMatched) echo "[".date("Y-m-d H:i:s",time())."][Warning]: 第".++$i."行的CreateTime数据不合法，已自动跳过处理\n";
		else 
		{
			$now_time=strtotime($row['CreateTime']);
			echo "SYSTEM> 正在将第".++$i."行中字段CreateTime的数据从".$row['CreateTime']."改为".$now_time."\n";
			if (!$conn->query("UPDATE wikis SET CreateTime=".$now_time." WHERE id=".$row['id']))
			{
				echo "[".date("Y-m-d H:i:s",time())."][Error]: 无法修改数据库,".mysqli_error($conn)."\n";
				exit;
			}	
		}
	}
	echo "SYSTEM> 正在将表wikis中字段CreateTime的类型改为int...\n";
	if (!$conn->query("alter table wikis modify CreateTime int(255)"))
	{
		echo "[".date("Y-m-d H:i:s",time())."][Error]: 无法修改数据库,".mysqli_error($conn)."\n";
		exit;
	}
	echo "SYSTEM> 表wikis修改完毕!\n";
	// sleep(2);
	
	echo "SYSTEM> 开始修改表notice:\n";
	echo "SYSTEM> 正在将表notice中字段ReleaseTime的类型改为varchar...\n";
	if (!$conn->query("alter table notice modify ReleaseTime varchar(8192)"))
	{
		echo "[".date("Y-m-d H:i:s",time())."][Error]: 无法修改数据库,".mysqli_error($conn)."\n";
		exit;
	}
	$result=mysqli_query($conn,"SELECT * FROM notice");$i=0;
	if (!$result)
	{
		echo "数据库查询错误";
		exit;
	}
	while ($row=mysqli_fetch_assoc($result))
	{
		$isMatched=preg_match('/^\d{4}-\d{1,2}-\d{1,2}/',$row["ReleaseTime"],$matches);
		if (!$isMatched) echo "[".date("Y-m-d H:i:s",time())."][Warning]: 第".++$i."行的ReleaseTime数据不合法，已自动跳过处理\n";
		else 
		{
			$now_time=strtotime($row['ReleaseTime']);
			echo "SYSTEM> 正在将第".++$i."行中字段ReleaseTime的数据从".$row['ReleaseTime']."改为".$now_time."\n";
			if (!$conn->query("UPDATE notice SET ReleaseTime=".$now_time." WHERE Title='".$row['Title']."'"))
			{
				echo "[".date("Y-m-d H:i:s",time())."][Error]: 无法修改数据库,".mysqli_error($conn)."\n";
				exit;
			}	
		}
	}
	echo "SYSTEM> 正在将表notice中字段ReleaseTime的类型改为int...\n";
	if (!$conn->query("alter table notice modify ReleaseTime int(255)"))
	{
		echo "[".date("Y-m-d H:i:s",time())."][Error]: 无法修改数据库,".mysqli_error($conn)."\n";
		exit;
	}
	echo "SYSTEM> 表notice修改完毕!\n";
	// sleep(2);
	
	echo "SYSTEM> 开始修改表temporary:\n";
	echo "SYSTEM> 正在将表temporary中字段Num的类型改为varchar...\n";
	if (!$conn->query("alter table temporary modify Num varchar(8192)"))
	{
		echo "[".date("Y-m-d H:i:s",time())."][Error]: 无法修改数据库,".mysqli_error($conn)."\n";
		exit;
	}
	echo "SYSTEM> 表temporary修改完毕!\n";
	// sleep(2);
	
	echo "SYSTEM> 开始新建表verify_code:\n";
	if (!$conn->query("CREATE TABLE verify_code (username varchar(8192),verifykey varchar(100),challenge varchar(8192),time int(255),useragent varchar(1024))"))
	{
		echo "[".date("Y-m-d H:i:s",time())."][Warning]: verify_code已存在，已自动跳过处理\n";
	}
	echo "SYSTEM> 表verify_code修改完毕!\n";
	// sleep(2);
	
	echo "SYSTEM> 开始修改表Users:\n";
	echo "SYSTEM> 正在将表Users中字段Birth的类型改为varchar...\n";
	if (!$conn->query("alter table Users modify Birth varchar(8192)"))
	{
		echo "[".date("Y-m-d H:i:s",time())."][Error]: 无法修改数据库,".mysqli_error($conn)."\n";
		exit;
	}
	$result=mysqli_query($conn,"SELECT * FROM Users");$i=0;
	if (!$result)
	{
		echo "数据库查询错误";
		exit;
	}
	while ($row=mysqli_fetch_assoc($result))
	{
		$isMatched=preg_match('/^\d{4}-\d{1,2}-\d{1,2}/',$row["Birth"],$matches);
		if ($row['Birth']=='-62170013143'||$row['Birth']==null)
		{
			echo "SYSTEM> 正在将第".++$i."行中字段Birth的数据从".$row['Birth']."改为0\n";
			if (!$conn->query("UPDATE Users SET Birth='0' WHERE UserId=".$row['UserId']))
			{
				echo "[".date("Y-m-d H:i:s",time())."][Error]: 无法修改数据库,".mysqli_error($conn)."\n";
				exit;
			}
		}
		else if (!$isMatched) echo "[".date("Y-m-d H:i:s",time())."][Warning]: 第".++$i."行的Birth数据不合法，已自动跳过处理\n";
		else 
		{
			$now_time=strtotime($row['Birth']);
			echo "SYSTEM> 正在将第".++$i."行中字段Birth的数据从".$row['Birth']."改为".$now_time."\n";
			if (!$conn->query("UPDATE Users SET Birth='".$now_time."' WHERE UserId=".$row['UserId']))
			{
				echo "[".date("Y-m-d H:i:s",time())."][Error]: 无法修改数据库,".mysqli_error($conn)."\n";
				exit;
			}
		}
		$decrypted='';$encrypted='';$from="";
		openssl_private_decrypt(base64_decode($row["UserPassword"]),$decrypted,$config["rsa-private-key"]);
		if ($decrypted!=''){ openssl_public_encrypt($decrypted,$encrypted,$config["rsa-public-key"]);$from=$decrypted; }
		else{ openssl_public_encrypt($row["UserPassword"],$encrypted,$config["rsa-public-key"]);$from=$row["UserPassword"]; }
		echo "SYSTEM> 正在将第".$i."行中字段UserPassword的数据从".$row['UserPassword']."改为".base64_encode($encrypted)."\n";
		if (!$conn->query("UPDATE Users SET UserPassword='".base64_encode($encrypted)."' WHERE UserId=".$row['UserId']))
		{
			echo "[".date("Y-m-d H:i:s",time())."][Error]: 无法修改数据库,".mysqli_error($conn)."\n";
			exit;
		}
		$new_authority=(($row["Authority"]==2)?1:0);
		echo "SYSTEM> 正在将第".$i."行中字段Authority的数据从".$row['Authority']."改为".$new_authority."\n";
		if (!$conn->query("UPDATE Users SET Authority='$new_authority' WHERE UserId=".$row['UserId']))
		{
			echo "[".date("Y-m-d H:i:s",time())."][Error]: 无法修改数据库,".mysqli_error($conn)."\n";
			exit;
		}
	}
	echo "SYSTEM> 正在将表Users中字段Birth的类型改为int...\n";
	if (!$conn->query("alter table Users modify Birth int(255)"))
	{
		echo "[".date("Y-m-d H:i:s",time())."][Error]: 无法修改数据库,".mysqli_error($conn)."\n";
		exit;
	}
	echo "SYSTEM> 正在表Users中添加字段banned...\n";
	if (!$conn->query("alter table Users add column banned int(11);"))  echo "[".date("Y-m-d H:i:s",time())."][Warning]: banned字段似乎已被添加，已自动跳过处理\n";
	echo "SYSTEM> 正在表Users中添加字段sign...\n";
	if (!$conn->query("alter table Users add column sign text;"))  echo "[".date("Y-m-d H:i:s",time())."][Warning]: sign字段似乎已被添加，已自动跳过处理\n";
	echo "SYSTEM> 表Users修改完毕!\n";
	
	echo "SYSTEM> 开始修改表LikeData:\n";
	if (!mysqli_num_rows(mysqli_query($conn,"SHOW COLUMNS FROM LikeData LIKE 'type'")))
	{
		echo "SYSTEM> 正在删除表LikeData...\n";
		if (!$conn->query("drop table LikeData"))
		{
			echo "[".date("Y-m-d H:i:s",time())."][Error]: 无法修改数据库,".mysqli_error($conn)."\n";
			exit;
		}
		echo "SYSTEM> 正在重建表LikeData...\n";
		if (!$conn->query("create table LikeData (type varchar(128),id1 int(255),id2 int(255),id3 int(255),uid int(255),time int(255),useragent varchar(1024))"))
		{
			echo "[".date("Y-m-d H:i:s",time())."][Error]: 无法修改数据库,".mysqli_error($conn)."\n";
			exit;
		}
	}
	else echo "SYSTEM> LikeData已被重建!\n";
	echo "SYSTEM> 表LoginData修改完毕\n";
	
	echo "SYSTEM> 开始修改表LoginData:\n";
	echo "SYSTEM> 正在新建字段useragent...\n";
	if (!$conn->query("alter table LoginData add column useragent varchar(1024);")) echo "[".date("Y-m-d H:i:s",time())."][Warning]: 无法修改数据库,".mysqli_error($conn)."\n";
	echo "SYSTEM> 表LoginData修改完毕\n";
	
	echo "SYSTEM> 开始新建表WatchHistory:\n";
	if (!$conn->query("CREATE TABLE WatchHistory (type varchar(128),id1 int(255),id2 int(255),uid int(255),time int(255),useragent varchar(8192))")) echo "[".date("Y-m-d H:i:s",time())."][Warning]: 无法修改数据库,".mysqli_error($conn)."\n";
	echo "SYSTEM> 表WatchHistory修改完毕\n";
	
	echo "SYSTEM> 开始新建表Star:\n";
	if (!$conn->query("CREATE TABLE Star (type varchar(128),id1 int(255),id2 int(255),uid int(255),time int(255),useragent varchar(8192))")) echo "[".date("Y-m-d H:i:s",time())."][Warning]: 无法修改数据库,".mysqli_error($conn)."\n";
	echo "SYSTEM> 表Star修改完毕\n";
	
	echo "SYSTEM> 开始新建表Comment:\n";
	if (!$conn->query("CREATE TABLE Comment (columnid int(255),id int(255),root int(255),cid int(255),uid int(255),time int(255),content text,banned int(11))")) echo "[".date("Y-m-d H:i:s",time())."][Warning]: 无法修改数据库,".mysqli_error($conn)."\n";
	echo "SYSTEM> 表Comment修改完毕\n";
	
	echo "SYSTEM> 开始新建表Message:\n";
	if (!$conn->query("CREATE TABLE Message (fromid int(255),toid int(255),time int(255),content text)")) echo "[".date("Y-m-d H:i:s",time())."][Warning]: 无法修改数据库,".mysqli_error($conn)."\n";
	echo "SYSTEM> 表Message修改完毕\n";
	
	echo "SYSTEM> 开始新建表SysMessage:\n";
	if (!$conn->query("CREATE TABLE SysMessage (toid int(255),time int(255),title text,content text)")) echo "[".date("Y-m-d H:i:s",time())."][Warning]: 无法修改数据库,".mysqli_error($conn)."\n";
	echo "SYSTEM> 表SysMessage修改完毕\n";
	
	echo "SYSTEM> 开始新建表Relation:\n";
	if (!$conn->query("CREATE TABLE Relation (fromid int(255),toid int(255),time int(255))")) echo "[".date("Y-m-d H:i:s",time())."][Warning]: 无法修改数据库,".mysqli_error($conn)."\n";
	echo "SYSTEM> 表Relation修改完毕\n";
	
	echo "SYSTEM> 开始新建表VisitData:\n";
	if (!$conn->query("CREATE TABLE VisitData (ip varchar(1024),time int(255),ua varchar(8192),page varchar(255))")) echo "[".date("Y-m-d H:i:s",time())."][Warning]: 无法修改数据库,".mysqli_error($conn)."\n";
	echo "SYSTEM> 表VisitData修改完毕\n";
?>

<?php
	// require_once "function.php";
	// require_once "config/common.php";
	// $conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
	// if (!$conn->query("CREATE TABLE device_id (id varchar(1024),ua varchar(1024),ip varchar(1024),banned int(10))")) exit;
	// echo "*";
?>