<?php
	require_once "config/common.php";
	echo "SYSTEM> 正在连接数据库...\n";
	$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
	if (!$conn)
	{
		echo "[".date("Y-m-d H:i:s",time())."][Error]: 无法连接数据库\n";
		echo "请检查数据库地址，账号，密码或数据库>名是否有误";
		exit;
	}
	echo "SYSTEM> 数据库连接成功!\n";

	"SYSTEM> 开始新建表Article:\n";
	if (!$conn->query("CREATE TABLE `Article` (`Title` varchar(1024) DEFAULT NULL,`Author` varchar(1024) DEFAULT NULL,`ColumnNum` int(255) DEFAULT NULL,`num` int(255) DEFAULT NULL,`WatchNum` int(255) DEFAULT NULL,`ReleaseTime` int(255) DEFAULT NULL,`banned` int(255) DEFAULT NULL)")) echo "[".date("Y-m-d H:i:s",time())."][Warning]: 无法修改数据库,".mysqli_error($conn)."\n";
	echo "SYSTEM> 表Article新建完毕\n";

	"SYSTEM> 开始新建表:Comment\n";
	if (!$conn->query("CREATE TABLE `Comment` (`columnid` int(255) DEFAULT NULL,`id` int(255) DEFAULT NULL,`root` int(255) DEFAULT NULL,`cid` int(255) DEFAULT NULL,`uid` int(255) DEFAULT NULL,`time` int(255) DEFAULT NULL,`content` text COLLATE utf8_unicode_ci,`banned` int(11) DEFAULT NULL)")) echo "[".date("Y-m-d H:i:s",time())."][Warning]: 无法修改数据库,".mysqli_error($conn)."\n";
	echo "SYSTEM> 表Comment修改完毕\n";

	"SYSTEM> 开始新建表:LikeData\n";
	if (!$conn->query("CREATE TABLE `LikeData` (`type` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,`id1` int(255) DEFAULT NULL,`id2` int(255) DEFAULT NULL,`id3` int(255) DEFAULT NULL,`uid` int(255) DEFAULT NULL,`time` int(255) DEFAULT NULL,`useragent` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;")) echo "[".date("Y-m-d H:i:s",time())."][Warning]: 无法修改数据库,".mysqli_error($conn)."\n";
	echo "SYSTEM> 表LikeData修改完毕\n";

	"SYSTEM> 开始新建表:LoginData\n";
	if (!$conn->query("CREATE TABLE `LoginData` (`uid` int(255) DEFAULT NULL,`SESSDATA` varchar(8192) COLLATE utf8_unicode_ci DEFAULT NULL,`CSRF` varchar(8192) COLLATE utf8_unicode_ci DEFAULT NULL,`time` int(255) DEFAULT NULL,`useragent` varchar(1024) COLLATE utf8_unicode_ci) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;")) echo "[".date("Y-m-d H:i:s",time())."][Warning]: 无法修改数据库,".mysqli_error($conn)."\n";
	echo "SYSTEM> 表LoginData修改完毕\n";

	"SYSTEM> 开始新建表:Message\n";
	if (!$conn->query("CREATE TABLE `Nessage` (`fromid` int(255) DEFAULT NULL,`toid` int(255) DEFAULT NULL,`time` int(255) DEFAULT NULL,`content` text COLLATE utf8_unicode_ci) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;")) echo "[".date("Y-m-d H:i:s",time())."][Warning]: 无法修改数据库,".mysqli_error($conn)."\n";
	echo "SYSTEM> 表Message修改完毕\n";

	"SYSTEM> 开始新建表:NewsColumn\n";
	if (!$conn->query("CREATE TABLE `NewsColumn` (`num` int(255) DEFAULT NULL,`name` varchar(1024) DEFAULT NULL,`overtime` int(255) DEFAULT NULL,`starttime` int(255) DEFAULT NULL,`WatchNum` int(255) DEFAULT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8;")) echo "[".date("Y-m-d H:i:s",time())."][Warning]: 无法修改数据库,".mysqli_error($conn)."\n";
	echo "SYSTEM> 表NewsColumn修改完毕\n";

	"SYSTEM> 开始新建表:notice\n";
	if (!$conn->query("CREATE TABLE `notice` (`Title` varchar(1024) DEFAULT NULL,`Author` varchar(1024) DEFAULT NULL,`ReleaseTime` int(255) DEFAULT NULL,`num` int(255) DEFAULT NULL,`WatchNum` int(255) DEFAULT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8;")) echo "[".date("Y-m-d H:i:s",time())."][Warning]: 无法修改数据库,".mysqli_error($conn)."\n";
	echo "SYSTEM> 表notice修改完毕\n";

	"SYSTEM> 开始新建表:Star\n";
	if (!$conn->query("CREATE TABLE `Star` (`type` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,`id1` int(255) DEFAULT NULL,`id2` int(255) DEFAULT NULL,`uid` int(255) DEFAULT NULL,`time` int(255) DEFAULT NULL,`useragent` varchar(8192) COLLATE utf8_unicode_ci DEFAULT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;")) echo "[".date("Y-m-d H:i:s",time())."][Warning]: 无法修改数据库,".mysqli_error($conn)."\n";
	echo "SYSTEM> 表Star修改完毕\n";
	
	"SYSTEM> 开始新建表:SysMessage\n";
	if (!$conn->query("CREATE TABLE `SysMessage` (`toid` int(255) DEFAULT NULL,`time` int(255) DEFAULT NULL,`title` text COLLATE utf8_unicode_ci,`content` text COLLATE utf8_unicode_ci) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;")) echo "[".date("Y-m-d H:i:s",time())."][Warning]: 无法修改数据库,".mysqli_error($conn)."\n";
	echo "SYSTEM> 表SysMessage修改完毕\n";

	"SYSTEM> 开始新建表temporary:\n";
	if (!$conn->query("CREATE TABLE `temporary` (`UserName` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL,`UserPassword` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL,`Num` varchar(8192) COLLATE utf8_unicode_ci DEFAULT NULL,`Mail` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;")) echo "[".date("Y-m-d H:i:s",time())."][Warning]: 无法修改数据库,".mysqli_error($conn)."\n";
	echo "SYSTEM> 表temporary修改完毕\n";

	"SYSTEM> 开始新建表Users:\n";
	if (!$conn->query("CREATE TABLE `Users` (`UserName` varchar(1024) DEFAULT NULL,`UserPassword` varchar(1024) DEFAULT NULL,`UserId` int(255) DEFAULT NULL,`RealName` varchar(1024) DEFAULT NULL,`School` varchar(1024) DEFAULT NULL,`Class` int(255) DEFAULT NULL,`Grade` int(255) DEFAULT NULL,`Birth` int(255) DEFAULT NULL,`Title` varchar(1024) DEFAULT NULL,`Authority` int(255) DEFAULT NULL,`Mail` varchar(1024) DEFAULT NULL,`Bilibili` varchar(255) DEFAULT NULL,`QQ` varchar(255) DEFAULT NULL,`banned` int(11)) ENGINE=MyISAM DEFAULT CHARSET=utf8;")) echo "[".date("Y-m-d H:i:s",time())."][Warning]: 无法修改数据库,".mysqli_error($conn)."\n";
	echo "SYSTEM> 表Users修改完毕\n";
	
	"SYSTEM> 开始新建表:verify_code\n";
	if (!$conn->query("CREATE TABLE `verify_code` (`username` varchar(8192) COLLATE utf8_unicode_ci DEFAULT NULL,`verifykey` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,`challenge` varchar(8192) COLLATE utf8_unicode_ci DEFAULT NULL,`time` int(255) DEFAULT NULL,`ua` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;")) echo "[".date("Y-m-d H:i:s",time())."][Warning]: 无法修改数据库,".mysqli_error($conn)."\n";
	echo "SYSTEM> 表verify_code修改完毕\n";

	"SYSTEM> 开始新建表:WatchHistory\n";
	if (!$conn->query("CREATE TABLE `WatchHistory` (`type` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,`id1` int(255) DEFAULT NULL,`id2` int(255) DEFAULT NULL,`uid` int(255) DEFAULT NULL,`time` int(255) DEFAULT NULL,`useragent` varchar(8192) COLLATE utf8_unicode_ci DEFAULT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;")) echo "[".date("Y-m-d H:i:s",time())."][Warning]: 无法修改数据库,".mysqli_error($conn)."\n";
	echo "SYSTEM> 表WatchHistory修改完毕\n";
	
	"SYSTEM> 开始新建表wiki:\n";
	if (!$conn->query("CREATE TABLE `wiki` (`Author` varchar(1024) DEFAULT NULL,`UpdateTime` int(255) DEFAULT NULL,`version` int(255) DEFAULT NULL,`id` int(255) DEFAULT NULL,`reason` varchar(8192) DEFAULT NULL,`WatchNum` int(255) DEFAULT NULL,`banned` int(255) DEFAULT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8;")) echo "[".date("Y-m-d H:i:s",time())."][Warning]: 无法修改数据库,".mysqli_error($conn)."\n";
	echo "SYSTEM> 表wiki修改完毕\n";

	"SYSTEM> 开始新建表wikis:\n";
	if (!$conn->query("CREATE TABLE `wikis` (`Title` varchar(1024) DEFAULT NULL,`id` int(255) DEFAULT NULL,`CreateTime` int(255) DEFAULT NULL,`WatchNum` int(255) DEFAULT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8;")) echo "[".date("Y-m-d H:i:s",time())."][Warning]: 无法修改数据库,".mysqli_error($conn)."\n";
	echo "SYSTEM> 表wikis修改完毕\n";
?>
