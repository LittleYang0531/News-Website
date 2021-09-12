<h2 style="width:100%;"><center><?php echo $language["notice-intitle"];?></center></h2>
<?php
	global $l,$r;
	$l=($_GET["page"]-1)*$config["notice-page-number"]+1;
	$r=$_GET["page"]*$config["notice-page-number"];
	$json=GetFromHTML($config["api-server-address"]."/notice/request.php?l=$l&r=$r&sort=createtime");
	$array=json_decode(strip_tags($json),true);
	for ($i=0;$i<count($array["data"]["replies"]);$i++)
	{
		$last=time()-$array["data"]["replies"][$i]["release"];
		$release=$array["data"]["replies"][$i]["release"];
		echo "
		<div class='notice-element' id='notice-element$i'>
			<div class='title' id='notice-element-title$i'><a onclick=locate('".$config["server"]."/".$config["main-path"]."&type=notice&id=".$array["data"]["replies"][$i]["id"]."')>".$array["data"]["replies"][$i]["name"]."</a></div>
			<div class='info' id='notice-element-info$i'>
				<p>".$language["notice-writer"].": ".$array["data"]["replies"][$i]["author"]["name"]."</p>
				<p>".$language["notice-view"].":".$array["data"]["replies"][$i]["view"]."&nbsp;&nbsp;&nbsp;&nbsp;".$language["notice-release-time"].":".
				(($last<60)?$language["notice-short-time"]:
				(($last<60*60)?round($last/60).$language["notice-minute"]:
				(($last<60*60*24)?round($last/60/60).$language["notice-hour"]:
				((date("Y",$release)!=date("Y",time()))?
				date("Y-m-d",$release):
				date("m-d H:i",$release)))))."</p>
			</div>
		</div>
		";
	}
	$ps=$config["notice-page-number"];
	$num_array=json_decode(strip_tags(GetFromHTML($config["api-server-address"]."/notice/max.php")),true);
	$num=$num_array["max"];
	CreatePageList($num,$ps,$config["notice-path"]);
?>