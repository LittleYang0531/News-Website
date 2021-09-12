<h2 style="width:100%;"><center><?php echo $language["history-intitle"];?></center></h2>
<div id="history-toolbar">
	<button style="display:<?php echo ($config["enable-article"]?"inline-block":"none")?>" onclick=locate_nogui(<?php echo "\"".$config["server"]."/".$config["history-path"]."&type=article&page=1"."\"";?>)><?php echo $language["history-article"];?></button>
	<button style="display:<?php echo ($config["enable-article"]?"inline-block":"none")?>" onclick=locate_nogui(<?php echo "\"".$config["server"]."/".$config["history-path"]."&type=column&page=1"."\"";?>)><?php echo $language["history-column"];?></button>
	<button style="display:<?php echo ($config["enable-wikis"]?"inline-block":"none")?>" onclick=locate_nogui(<?php echo "\"".$config["server"]."/".$config["history-path"]."&type=wikis&page=1"."\"";?>)><?php echo $language["history-wikis"];?></button>
	<button onclick=clearx()><?php echo $language["history-clear"];?></button>
	<script>
		function strip_tags(html)
		{
		    var div=document.createElement("div");
		    div.innerHTML=html;
		    return (div.textContent||div.innerText||"");
		}
		function clearx()
		{
			layer.confirm(<?php echo "\"".$language["history-clear-make-sure"]."\"";?>, {
				btn: [<?php echo "\"".$language["history-clear-sure"]."\"";?>,<?php echo "\"".$language["history-clear-cancle"]."\"";?>] //按钮
			}, function(){
				$.ajax({
					type:"POST",
					url:<?php echo "\"".$config["api-server-address"]."/history/clear.php"."\"";?>,
					data:{type:<?php echo "\"".$_GET["type"]."\""?>},
					success:function(message)
					{
						var obj=JSON.parse(strip_tags(message));
						var code=obj["code"];
						if (code==0)
						{
							alert(<?php echo "\"".$language["history-clear-success"]."\"";?>);
							window.location.href=<?php echo "\"".$config["server"]."/".$config["history-path"]."&type=".$_GET["type"]."&page=1\"";?>
						}
						else
						{
							layer.alert(<?php echo "\"".$language["history-clear-failed"]."\"";?>);
							console.log(obj["message"]);
						}
					},
					error:function(jqXHR,textStatus,errorThrown) 
					{
						layer.alert(<?php echo "\"".$language["history-clear-error"]."\""?>);
					    layer.alert(jqXHR.responseText);
					    layer.alert(jqXHR.status);
					    layer.alert(jqXHR.readyState);
					    layer.alert(jqXHR.statusText);
					    layer.alert(textStatus);
					    layer.alert(errorThrown);
					}
				});
			}, function(){
				
			});
		}
	</script>
</div>
<br/>
<?php
	$type=$_GET['type'];$page=$_GET["page"];
	echo "<script>document.title='".$language["history-title"]." - ".$config["website-title"]."';</script>";
	$json=GetFromHTML($config["api-server-address"]."/history/$type.php?l=".(1+($page-1)*$config["history-$type-number"])."&r=".($page*$config["history-$type-number"]));
	$array=json_decode(strip_tags($json),true);
	if (count($array["data"]["replies"])==0) echo "<p><center>".$language["history-no-result"]."</center></p>";
	else 
	{
		if ($type=="article") for ($i=0;$i<count($array["data"]["replies"]);$i++)
		{
			$last=time()-$array["data"]["replies"][$i]["time"];
			$release=$array["data"]["replies"][$i]["time"];
			echo "
			<div class='article-element' id='article-element$i'>
				<img class='picture' id='article-element-picture$i' src='".$array["data"]["replies"][$i]["author"]["header"]."'/>
				<div class='info' id='article-element-info$i'>
					<a onclick=locate('".$config["server"]."/".$config["main-path"]."&type=article&column=".$array["data"]["replies"][$i]["columnid"]."&id=".$array["data"]["replies"][$i]["id"]."')>".$array["data"]["replies"][$i]["name"]."</a>
					<p>".$language["history-article-writer"].": @<location onclick=locate('".$config["server"]."/".$config["profile-path"]."&uid=".$array["data"]["replies"][$i]["author"]["uid"]."')>".$array["data"]["replies"][$i]["author"]["name"]."</location></p>
					<p>".$language["history-article-like"].":".$array["data"]["replies"][$i]["like"]."&nbsp;&nbsp;&nbsp;&nbsp;".$language["history-article-view"].":".$array["data"]["replies"][$i]["view"]."</p>
					<p>".$language["history-article-release-time"].":".
					(($last<60)?$language["history-article-short-time"]:
					(($last<60*60)?round($last/60).$language["history-article-minute"]:
					(($last<60*60*24)?round($last/60/60).$language["history-article-hour"]:
					((date("Y",$release)!=date("Y",time()))?
					date("Y-m-d",$release):
					date("m-d H:i",$release)))))."</p>
				</div>
			</div>";
		}
		if ($type=="column") for ($i=0;$i<count($array["data"]["replies"]);$i++)
		{
			$last=time()-$array["data"]["replies"][$i]["opentime"];
			$release=$array["data"]["replies"][$i]["opentime"];
			$remain=$array["data"]["replies"][$i]["overtime"]-time();
			$over=$array["data"]["replies"][$i]["overtime"];
			echo "
			<div class='column-element' id='column-element$i'>
				<div class='title' id='column-element-title$i'><a onclick=locate('".$config["server"]."/".$config["main-path"]."&type=column&id=".$array["data"]["replies"][$i]["id"]."&page=1')>".$array["data"]["replies"][$i]["name"]."</a></div>
				<div class='info' id='column-element-info$i'>
					<p>".$language["history-column-article-number"].":".count($array["data"]["replies"][$i]["article"])."&nbsp;&nbsp;&nbsp;&nbsp;".$language["history-column-view"].":".$array["data"]["replies"][$i]["view"]."</p>
					<p>".$language["history-column-release-time"].":".
					(($last<60)?$language["history-column-short-time"]:
					(($last<60*60)?round($last/60).$language["history-column-minute"]:
					(($last<60*60*24)?round($last/60/60).$language["history-column-hour"]:
					((date("Y",$release)!=date("Y",time()))?
					date("Y-m-d",$release):
					date("m-d H:i",$release)))))."</p>
					<p>".$language["history-column-over-time"].":".
					(($remain>0)?
					(($remain<60)?$language["history-column-short-time-after"]:
					(($remain<60*60)?round($remain/60).$language["history-column-minute-after"]:
					(($remain<60*60*24)?round($remain/60/60).$language["history-column-hour-after"]:
					((date("Y",$over)!=date("Y",time()))?
					date("Y-m-d",$over):
					date("m-d H:i",$over))))):
					(($remain>-60)?$language["history-column-short-time"]:
					(($remain>-60*60)?round($remain/60).$language["history-column-minute"]:
					(($remain>-60*60*24)?round($remain/60/60).$language["history-column-hour"]:
					((date("Y",$over)!=date("Y",time()))?
					date("Y-m-d",$over):
					date("m-d H:i",$over)))))
					)."</p>
				</div>
			</div>
			";
		}
		if ($type=="wikis") for ($i=0;$i<count($array["data"]["replies"]);$i++)
		{
			$last=time()-$array["data"]["replies"][$i]["opentime"];
			$release=$array["data"]["replies"][$i]["opentime"];
			$last2=time()-$array["data"]["replies"][$i]["latest"];
			$latest=$array["data"]["replies"][$i]["latest"];
			echo "
			<div class='wikis-element' id='wikis-element$i'>
				<div class='title' id='wikis-element-title$i'><a ".((count($array["data"]["replies"][$i]["history"])>0)?("onclick=locate('".$config["server"]."/".$config["main-path"]."&type=wiki&id=".$array["data"]["replies"][$i]["id"]."&version=".count($array["data"]["replies"][$i]["history"])."&page=1')"):"").">".$array["data"]["replies"][$i]["name"]."</a></div>
				<div class='info' id='wikis-element-info$i'>
					<p>".$language["history-wikis-version-number"].": v1.".count($array["data"]["replies"][$i]["history"])."&nbsp;&nbsp;&nbsp;&nbsp;".$language["history-wikis-view"].":".$array["data"]["replies"][$i]["view"]."</p>
					<p>".$language["history-wikis-release-time"].":".
					(($last<60)?$language["history-wikis-short-time"]:
					(($last<60*60)?round($last/60).$language["history-wikis-minute"]:
					(($last<60*60*24)?round($last/60/60).$language["history-wikis-hour"]:
					((date("Y",$release)!=date("Y",time()))?
					date("Y-m-d",$release):
					date("m-d H:i",$release)))))."</p>
					<p>".$language["history-wikis-latest-time"].":".
					(($latest==0)?"---":
					(($last2<60)?$language["history-wikis-short-time"]:
					(($last2<60*60)?round($last2/60).$language["history-wikis-minute"]:
					(($last2<60*60*24)?round($last2/60/60).$language["history-wikis-hour"]:
					((date("Y",$latest)!=date("Y",time()))?
					date("Y-m-d",$latest):
					date("m-d H:i",$latest))))))."</p>
				</div>
			</div>
			";
		}
		$ps=$config["history-$type-number"];
		$num_array=json_decode(strip_tags(GetFromHTML($config["api-server-address"]."/history/max.php?type=$type")),true);
		$num=$num_array["max"];
		CreatePageList($num,$ps,$config["history-path"]."&type=".$type);
	}
?>