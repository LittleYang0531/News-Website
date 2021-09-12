<?php
	require "../config.php";
	require "../function.php";
	$l = $_GET['l'];
	$r = $_GET['r'];
	$sort = $_GET['sort'];
	header("Content-Type:text/html;charset=utf-8");
	session_start();
	if ($sort=="") $sort="hot";
	if($l==""||$r==""||$sort=="")
	{
		echo ReturnError(-400,"传递参数错误");
		exit;
	}
	$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
	if(! $conn ){
		echo ReturnError(-500,"无法连接数据库");
		exit;
	}
	$sql = "";
	if ($sort=='createtime') $sql = "SELECT * FROM wikis order by CreateTime DESC";
	// else if ($sort=='updatetime') $sql = "SELECT * FROM wikis order by UpdateTime DESC";
	else if ($sort=='hot') $sql = "SELECT * FROM wikis order by WatchNum DESC";
	else
	{
		echo ReturnError(-400,"传递参数错误");
		exit;
	}
	$result = mysqli_query($conn, $sql);
	if (!$result||mysqli_num_rows($result)==0)  
	{
		echo ReturnError(-400,"数据库查询错误");
		exit;
	}
	else 
	{
		$now=1;
		$info=array();
		while ($row=mysqli_fetch_assoc($result))
		{
			if ($now>=$l)
			{
				$version=array();
				$sql="SELECT * FROM LikeData WHERE type='wikis' and id1=".$row["id"];
				$result5=mysqli_query($conn,$sql);
				if (!$result5)
				{
					echo ReturnError(-400,"数据库查询错误");
					exit;
				}
				$star=mysqli_num_rows($result5);
				$sql="SELECT * FROM Star WHERE type='wikis' and id1=".$row["id"];
				$result5=mysqli_query($conn,$sql);
				if (!$result5)
				{
					echo ReturnError(-400,"数据库查询错误");
					exit;
				}
				$like=mysqli_num_rows($result5);
				$sql="SELECT * FROM wiki WHERE id=".$row["id"]." order by UpdateTime DESC";
				$result5=mysqli_query($conn,$sql);
				if (!$result5)
				{
					echo ReturnError(-400,"数据库查询错误");
					exit;
				}
				$latest=0;
				while ($row5=mysqli_fetch_assoc($result5))
				{
					if ($row5['banned']==1) continue;
					$sql = "SELECT * FROM Users where UserName='".$row5["Author"]."'";
					$result1 = mysqli_query($conn, $sql);
					$row1 = mysqli_fetch_assoc($result1);
					$author=array(
						"uid"=>$row1['UserId'],
						"name"=>$row1['UserName'],
						"birth"=>"****-**-**",
						'school'=>$row1['School'],
						"class"=>$row1['Class'],
						"grade"=>$row1['Grade'],
						"title"=>$row1['Title'],
						"mail"=>"**********",
						"QQ"=>"**********",
						"bili"=>($row1['Bilibili']=="")?"":"//space.bilibili.com/".$row1['Bilibili']."/",
						"header"=>$config["api-server-address"]."/".$config['account-data']."/".$row1['UserName']."/header.jpg",
						"background"=>$config["api-server-address"]."/".$config['account-data']."/".$row1['UserName']."/background.jpg",
						"authority"=>$row1['Authority'],
					);
					$version[]=array(
						"author"=>$author,
						"opentime"=>$row5["UpdateTime"],
						"version"=>$row5["version"],
						"wikiid"=>$row5["id"],
						"reason"=>$row5["reason"],
						"watch"=>$row5["WatchNum"],
						"link"=>$config["api-server-address"]."/".$config['wiki-data']."/".$row5["id"]."/".$row5["version"].".html",
					);
					$latest=max($latest,$row5["UpdateTime"]);
				}
				$info[]=array(
					"id"=>$row["id"],
					"name"=>$row['Title'],
					"opentime"=>$row["CreateTime"],
					"latest"=>$latest,
					'view'=>$row['WatchNum'],
					"like"=>$like,
					"star"=>$star,
					"history"=>$version,
				);
			}
			$now++;
			if ($now>$r) break;
		}
		$information=array(
			"code"=>0,
			"message"=>0,
			"ttl"=>0,
			"data"=>array(
				"left"=>$l,
				"right"=>$r,
				"replies"=>$info,
			),
		);
		echo ReturnJSON($information);
		exit;
	}
?>







