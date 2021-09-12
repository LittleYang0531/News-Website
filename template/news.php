<h2 style="width:100%;"><center><?php echo $language["news-intitle"];?></center></h2>
<?php
	global $l,$r;
	$l=($_GET["page"]-1)*$config["news-column-number"]+1;
	$r=$_GET["page"]*$config["news-column-number"];
	$array=json_decode(strip_tags(GetFromHTML($config["api-server-address"]."/column/request.php?l=$l&r=$r&sort=createtime")),true);
	for ($i=0;$i<count($array["data"]["replies"]);$i++)
	{
		$last=time()-$array["data"]["replies"][$i]["opentime"];
		$release=$array["data"]["replies"][$i]["opentime"];
		$remain=$array["data"]["replies"][$i]["overtime"]-time();
		$over=$array["data"]["replies"][$i]["overtime"];
		echo "
		<div class='column-element' id='column-element$i'>
			<div class='title' id='column-element-title$i'><a onclick=locate('".$config["server"]."/".$config["main-path"]."&type=column&id=".$array["data"]["replies"][$i]["id"]."&page=1')>".$array["data"]["replies"][$i]["name"]."</a></div>
			<div class='info' id='column-element-info$i'>
				<p>".$language["news-article-number"].":".count($array["data"]["replies"][$i]["article"])."&nbsp;&nbsp;&nbsp;&nbsp;".$language["news-view"].":".$array["data"]["replies"][$i]["view"]."</p>
				<p>".$language["news-release-time"].":".
				(($last<60)?$language["news-short-time"]:
				(($last<60*60)?round($last/60).$language["news-minute"]:
				(($last<60*60*24)?round($last/60/60).$language["news-hour"]:
				((date("Y",$release)!=date("Y",time()))?
				date("Y-m-d",$release):
				date("m-d H:i",$release)))))."</p>
				<p>".$language["news-over-time"].":".
				(($remain>0)?
				(($remain<60)?$language["news-short-time-after"]:
				(($remain<60*60)?round($remain/60).$language["news-minute-after"]:
				(($remain<60*60*24)?round($remain/60/60).$language["news-hour-after"]:
				((date("Y",$over)!=date("Y",time()))?
				date("Y-m-d",$over):
				date("m-d H:i",$over))))):
				(($remain>-60)?$language["news-short-time"]:
				(($remain>-60*60)?round($remain/60).$language["news-minute"]:
				(($remain>-60*60*24)?round($remain/60/60).$language["news-hour"]:
				((date("Y",$over)!=date("Y",time()))?
				date("Y-m-d",$over):
				date("m-d H:i",$over)))))
				)."</p>
			</div>
		</div>
		";
	}
	$ps=$config["news-column-number"];
	$num_array=json_decode(strip_tags(GetFromHTML($config["api-server-address"]."/column/max.php")),true);
	$num=$num_array["max"];
	CreatePageList($num,$ps,$config["news-path"]);
?>