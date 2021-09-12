<h2 style="width:100%;"><center><?php echo $language["index-intitle"];?></center></h2>
<div class="textmain">
	<style>
		<?php
			if (!$config["enable-notice"]) echo "
				.right{
					width:0px;
				}
			";
		?>
	</style>
	<div class="left" id="left">
		<div class="article" id="article" style="display:<?php echo ($config["enable-article"]?"block":"none")?>">
			<h2 style="width:100%;font-size:20px;">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $language["index-article-title"];?></h2>
			<?php
				$json=GetFromHTML($config["api-server-address"]."/article/request.php?l=1&r=".$config["index-article-number"]."&sort=hot");
				$array=json_decode(strip_tags($json),true);
				for ($i=0;$i<count($array["data"]["replies"]);$i++)
				{
					$last=time()-$array["data"]["replies"][$i]["time"];
					$release=$array["data"]["replies"][$i]["time"];
					echo "
					<div class='article-element' id='article-element$i'>
						<img class='picture' id='article-element-picture$i' src='".$array["data"]["replies"][$i]["author"]["header"]."'/>
						<div class='info' id='article-element-info$i'>
							<a onclick=locate('".$config["server"]."/".$config["main-path"]."&type=article&column=".$array["data"]["replies"][$i]["columnid"]."&id=".$array["data"]["replies"][$i]["id"]."')>".$array["data"]["replies"][$i]["name"]."</a>
							<p>".$language["index-article-writer"].": @<location onclick=locate('".$config["server"]."/".$config["profile-path"]."&uid=".$array["data"]["replies"][$i]["author"]["uid"]."')>".$array["data"]["replies"][$i]["author"]["name"]."</location></p>
							<p>".$language["index-article-like"].":".$array["data"]["replies"][$i]["like"]."&nbsp;&nbsp;&nbsp;&nbsp;".$language["index-article-view"].":".$array["data"]["replies"][$i]["view"]."</p>
							<p>".$language["index-article-release-time"].":".
							(($last<60)?$language["index-article-short-time"]:
							(($last<60*60)?round($last/60).$language["index-article-minute"]:
							(($last<60*60*24)?round($last/60/60).$language["index-article-hour"]:
							((date("Y",$release)!=date("Y",time()))?
							date("Y-m-d",$release):
							date("m-d H:i",$release)))))."</p>
						</div>
					</div>
					";
				}
			?>
		</div>
		<div class="column" id="column" style="display:<?php echo ($config["enable-article"]?"block":"none")?>">
			<h2 style="width:100%;font-size:20px;">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $language["index-column-title"];?></h2>
			<?php
				$json=GetFromHTML($config["api-server-address"]."/column/request.php?l=1&r=".$config["index-column-number"]."&sort=hot");
				$array=json_decode(strip_tags($json),true);
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
							<p>".$language["index-column-article-number"].":".count($array["data"]["replies"][$i]["article"])."&nbsp;&nbsp;&nbsp;&nbsp;".$language["index-column-view"].":".$array["data"]["replies"][$i]["view"]."</p>
							<p>".$language["index-column-release-time"].":".
							(($last<60)?$language["index-column-short-time"]:
							(($last<60*60)?round($last/60).$language["index-column-minute"]:
							(($last<60*60*24)?round($last/60/60).$language["index-column-hour"]:
							((date("Y",$release)!=date("Y",time()))?
							date("Y-m-d",$release):
							date("m-d H:i",$release)))))."</p>
							<p>".$language["index-column-over-time"].":".
							(($remain>0)?
							(($remain<60)?$language["index-column-short-time-after"]:
							(($remain<60*60)?round($remain/60).$language["index-column-minute-after"]:
							(($remain<60*60*24)?round($remain/60/60).$language["index-column-hour-after"]:
							((date("Y",$over)!=date("Y",time()))?
							date("Y-m-d",$over):
							date("m-d H:i",$over))))):
							(($remain>-60)?$language["index-column-short-time"]:
							(($remain>-60*60)?round($remain/60).$language["index-column-minute"]:
							(($remain>-60*60*24)?round($remain/60/60).$language["index-column-hour"]:
							((date("Y",$over)!=date("Y",time()))?
							date("Y-m-d",$over):
							date("m-d H:i",$over)))))
							)."</p>
						</div>
					</div>
					";
				}
			?>
		</div>
		<div class="wikis" id="wikis" style="display:<?php echo ($config["enable-wikis"]?"block":"none")?>">
			<h2 style="width:100%;font-size:20px;">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $language["index-wikis-title"];?></h2>
			<?php
				$json=GetFromHTML($config["api-server-address"]."/wiki/request.php?l=1&r=".$config["index-wikis-number"]."&sort=hot");
				$array=json_decode(strip_tags($json),true);
				for ($i=0;$i<count($array["data"]["replies"]);$i++)
				{
					$last=time()-$array["data"]["replies"][$i]["opentime"];
					$release=$array["data"]["replies"][$i]["opentime"];
					$last2=time()-$array["data"]["replies"][$i]["latest"];
					$latest=$array["data"]["replies"][$i]["latest"];
					echo "
					<div class='wikis-element' id='wikis-element$i'>
						<div class='title' id='wikis-element-title$i'><a ".((count($array["data"]["replies"][$i]["history"])>0)?("onclick=locate('".$config["server"]."/".$config["main-path"]."&type=wiki&id=".$array["data"]["replies"][$i]["id"]."&version=".count($array["data"]["replies"][$i]["history"])."&page=1')"):"").">".$array["data"]["replies"][$i]["name"]."</a></div>
						<div class='info' id='wikis-element-info$i'>
							<p>".$language["index-wikis-version-number"].": v1.".count($array["data"]["replies"][$i]["history"])."&nbsp;&nbsp;&nbsp;&nbsp;".$language["index-wikis-view"].":".$array["data"]["replies"][$i]["view"]."</p>
							<p>".$language["index-wikis-release-time"].":".
							(($last<60)?$language["index-wikis-short-time"]:
							(($last<60*60)?round($last/60).$language["index-wikis-minute"]:
							(($last<60*60*24)?round($last/60/60).$language["index-wikis-hour"]:
							((date("Y",$release)!=date("Y",time()))?
							date("Y-m-d",$release):
							date("m-d H:i",$release)))))."</p>
							<p>".$language["index-wikis-latest-time"].":".
							(($latest==0)?"---":
							(($last2<60)?$language["index-wikis-short-time"]:
							(($last2<60*60)?round($last2/60).$language["index-wikis-minute"]:
							(($last2<60*60*24)?round($last2/60/60).$language["index-wikis-hour"]:
							((date("Y",$latest)!=date("Y",time()))?
							date("Y-m-d",$latest):
							date("m-d H:i",$latest))))))."</p>
						</div>
					</div>
					";
				}
			?>
		</div>
	</div>
	<div class="right" id="right">
		<div class="notice" id="notice" style="display:<?php echo ($config["enable-notice"]?"block":"none")?>">
			<h2 style="width:100%;font-size:20px;">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $language["index-notice-title"];?></h2>
			<?php
				$json=GetFromHTML($config["api-server-address"]."/notice/request.php?l=1&r=".$config["index-notice-number"]."&sort=hot");
				$array=json_decode(strip_tags($json),true);
				for ($i=0;$i<count($array["data"]["replies"]);$i++)
				{
					$last=time()-$array["data"]["replies"][$i]["release"];
					$release=$array["data"]["replies"][$i]["release"];
					echo "
					<div class='notice-element' id='notice-element$i'>
						<div class='title' id='notice-element-title$i'><a onclick=locate('".$config["server"]."/".$config["main-path"]."&type=notice&id=".$array["data"]["replies"][$i]["id"]."&page=1')>".$array["data"]["replies"][$i]["name"]."</a></div>
						<div class='info' id='notice-element-info$i'>
							<p>".$language["index-notice-writer"].": ".$array["data"]["replies"][$i]["author"]["name"]."</p>
							<p>".$language["index-notice-view"].":".$array["data"]["replies"][$i]["view"]."&nbsp;&nbsp;&nbsp;&nbsp;".$language["index-notice-release-time"].":".
							(($last<60)?$language["index-notice-short-time"]:
							(($last<60*60)?round($last/60).$language["index-notice-minute"]:
							(($last<60*60*24)?round($last/60/60).$language["index-notice-hour"]:
							((date("Y",$release)!=date("Y",time()))?
							date("Y-m-d",$release):
							date("m-d H:i",$release)))))."</p>
						</div>
					</div>
					";
				}
			?>
		</div>
	</div>
</div>