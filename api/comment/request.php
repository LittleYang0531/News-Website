<?php
	require "../config.php";
	require "../function.php";
	function quickSort($arr)
	{
	    $count=count($arr);
	    if ($count<=1) return $arr;
	    $index=$arr[0];
	    $left=[];
	    $right=[];
	    for ($i=1;$i<$count;$i++) 
		{
			if ($arr[$i]["like"]==$index["like"])
			{
				if ($arr[$i]["time"]<$index["time"]) $left[]=$arr[$i];
				else $right[]=$arr[$i];
			}
	        else 
			{
				if ($arr[$i]["like"]>$index["like"]) $left[]=$arr[$i];
				else $right[]=$arr[$i];
			}
	    }
	    $left=quickSort($left);
	    $right=quickSort($right);
	    return array_merge($left,[$index],$right);
	}
	$column=$_GET["column"];
	$id=$_GET["id"];
	$sort=$_GET['sort'];
	function DFS($root,$column,$id,$sort,$rootauthor,$top)
	{
		require "../config.php";
		$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
		if(!$conn){
			echo ReturnError(-500,"无法连接数据库");
			exit;
		}
		$sql="SELECT * FROM Comment WHERE columnid=$column and id=$id and root=$root order by time DESC";
		$result=mysqli_query($conn,$sql);
		if (!$result)  
		{
			echo ReturnError(-400,"数据库查询错误");
			exit;
		}
		$info=array();
		while ($row=mysqli_fetch_assoc($result))
		{
			if ($row['banned']==1) continue;
			$sql = "SELECT * FROM LikeData where type='comment' and id1=$column and id2=$id and id3=".$row['cid'];
			$result2 = mysqli_query($conn, $sql);
			if (!$result2) $like=0;
			else $like=mysqli_num_rows($result2);
			$sql = "SELECT * FROM Users where UserId='".$row["uid"]."'";
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
			$info[]=array(
				"column"=>$row["columnid"],
				"id"=>$row["id"],
				"cid"=>$row["cid"],
				"root"=>$row["root"],
				"rootauthor"=>$rootauthor,
				"author"=>$author,
				'time'=>$row['time'],
				'content'=>$row["content"],
				'like'=>$like,
				'comment'=>null,
			);
			$res=DFS($row["cid"],$column,$id,$sort,$author,0);
			if (!$top) $info=array_merge($info,$res);
			else $info[count($info)-1]["comment"]=$res;
		}
		if ($sort=="hot") $info=quickSort($info);
		return $info;
	}
	header("Content-Type:text/html;charset=utf-8");
	session_start();
	if ($sort=="") $sort="hot";
	if($column==""||$id=="")
	{
		echo ReturnError(-400,"传递参数错误");
		exit;
	}
	$conn=mysqli_connect($config["mysql-address"],$config["mysql-user"],$config["mysql-password"],$config["mysql-database"]);
	if(! $conn ){
		echo ReturnError(-500,"无法连接数据库");
		exit;
	}
	$sql="SELECT * FROM Comment WHERE columnid=$column and id=$id and root=0 order by time DESC";
	$result=mysqli_query($conn,$sql);
	if (!$result)  
	{
		echo ReturnError(-400,"数据库查询错误");
		exit;
	}
	else 
	{
		$info=DFS(0,$column,$id,$sort,null,1);
		$information=array(
			"code"=>0,
			"message"=>0,
			"ttl"=>0,
			"data"=>$info,
		);
		echo ReturnJSON($information);
		exit;
	}
?>