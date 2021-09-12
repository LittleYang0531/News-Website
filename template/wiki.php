<h2 style="width:100%;"><center><?php echo $language["wiki-intitle"];?></center></h2>
<?php
	global $l,$r;
	$l=($_GET["page"]-1)*$config["wiki-page-number"]+1;
	$r=$_GET["page"]*$config["wiki-page-number"];
	$json=GetFromHTML($config["api-server-address"]."/wiki/request.php?sort=hot&l=$l&r=$r&sort=createtime");
	$array=json_decode(strip_tags($json),true);
	for ($i=0;$i<count($array["data"]["replies"]);$i++)
	{
		$last=time()-$array["data"]["replies"][$i]["opentime"];
		$release=$array["data"]["replies"][$i]["opentime"];
		$last2=time()-$array["data"]["replies"][$i]["latest"];
		$latest=$array["data"]["replies"][$i]["latest"];
		echo "
		<div class='wiki-element' id='wiki-element$i'>
			<div class='title' id='wiki-element-title$i'><a ".((count($array["data"]["replies"][$i]["history"])>0)?("onclick=locate('".$config["server"]."/".$config["main-path"]."&type=wiki&id=".$array["data"]["replies"][$i]["id"]."&version=".count($array["data"]["replies"][$i]["history"])."')"):"").">".$array["data"]["replies"][$i]["name"]."</a></div>
			<div class='info' id='wiki-element-info$i'>
				<p>".$language["wiki-version-number"].": v1.".count($array["data"]["replies"][$i]["history"])."&nbsp;&nbsp;&nbsp;&nbsp;".$language["wiki-view"].":".$array["data"]["replies"][$i]["view"]."</p>
				<p>".$language["wiki-release-time"].":".
				(($last<60)?$language["wiki-short-time"]:
				(($last<60*60)?round($last/60).$language["wiki-minute"]:
				(($last<60*60*24)?round($last/60/60).$language["wiki-hour"]:
				((date("Y",$release)!=date("Y",time()))?
				date("Y-m-d",$release):
				date("m-d H:i",$release)))))."</p>
				<p>".$language["wiki-latest-time"].":".
				(($latest==0)?"---":
				(($last2<60)?$language["wiki-short-time"]:
				(($last2<60*60)?round($last2/60).$language["wiki-minute"]:
				(($last2<60*60*24)?round($last2/60/60).$language["wiki-hour"]:
				((date("Y",$latest)!=date("Y",time()))?
				date("Y-m-d",$latest):
				date("m-d H:i",$latest))))))."</p>
			</div>
		</div>
		";
	}
	$ps=$config["wiki-page-number"];
	$num_array=json_decode(strip_tags(GetFromHTML($config["api-server-address"]."/wiki/maxwiki.php")),true);
	$num=$num_array["max"];
	CreatePageList($num,$ps,$config["wiki-path"]);
?>