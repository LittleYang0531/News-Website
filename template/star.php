<h2 style="width:100%;"><center><?php echo $language["star-intitle"];?></center></h2>
<div id="star-toolbar">
	<button style="display:<?php echo ($config["enable-article"]?"inline-block":"none")?>" onclick=locate_nogui(<?php echo "\"".$config["server"]."/".$config["star-path"]."&type=article&page=1"."\"";?>)><?php echo $language["star-article"];?></button>
	<button style="display:<?php echo ($config["enable-wikis"]?"inline-block":"none")?>" onclick=locate_nogui(<?php echo "\"".$config["server"]."/".$config["star-path"]."&type=wiki&page=1"."\"";?>)><?php echo $language["star-wikis"];?></button>
	<button onclick=clearx()><?php echo $language["star-clear"];?></button>
	<script>
		function strip_tags(html)
		{
		    var div=document.createElement("div");
		    div.innerHTML=html;
		    return (div.textContent||div.innerText||"");
		}
		function clearx()
		{
			layer.confirm(<?php echo "\"".$language["star-clear-make-sure"]."\"";?>, {
				btn: [<?php echo "\"".$language["star-clear-sure"]."\"";?>,<?php echo "\"".$language["star-clear-cancle"]."\"";?>] //按钮
			}, function(){
				$.ajax({
					type:"POST",
					url:<?php echo "\"".$config["api-server-address"]."/star/clear.php"."\"";?>,
					data:{type:<?php echo "\"".$_GET["type"]."\""?>},
					success:function(message)
					{
						var obj=JSON.parse(strip_tags(message));
						var code=obj["code"];
						if (code==0)
						{
							alert(<?php echo "\"".$language["star-clear-success"]."\"";?>);
							window.location.href=<?php echo "\"".$config["server"]."/".$config["star-path"]."&type=".$_GET["type"]."&page=1\"";?>
						}
						else
						{
							layer.alert(<?php echo "\"".$language["star-clear-failed"]."\"";?>);
							console.log(obj["message"]);
						}
					},
					error:function(jqXHR,textStatus,errorThrown) 
					{
						layer.alert(<?php echo "\"".$language["star-clear-error"]."\""?>);
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
	echo "<script>document.title='".$language["star-title"]." - ".$config["website-title"]."';</script>";
	$json=GetFromHTML($config["api-server-address"]."/star/$type.php?l=".(1+($page-1)*$config["star-$type-number"])."&r=".($page*$config["star-$type-number"]));
	$array=json_decode(strip_tags($json),true);
	if (count($array["data"]["replies"])==0) echo "<p><center>".$language["star-no-result"]."</center></p>";
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
					<p>".$language["star-article-writer"].": @<location onclick=locate('".$config["server"]."/".$config["profile-path"]."&uid=".$array["data"]["replies"][$i]["author"]["uid"]."')>".$array["data"]["replies"][$i]["author"]["name"]."</location></p>
					<p>".$language["star-article-like"].":".$array["data"]["replies"][$i]["like"]."&nbsp;&nbsp;&nbsp;&nbsp;".$language["star-article-view"].":".$array["data"]["replies"][$i]["view"]."</p>
					<p>".$language["star-article-release-time"].":".
					(($last<60)?$language["star-article-short-time"]:
					(($last<60*60)?round($last/60).$language["star-article-minute"]:
					(($last<60*60*24)?round($last/60/60).$language["star-article-hour"]:
					((date("Y",$release)!=date("Y",time()))?
					date("Y-m-d",$release):
					date("m-d H:i",$release)))))."</p>
				</div>
			</div>";
		}
		if ($type=="wiki") for ($i=0;$i<count($array["data"]["replies"]);$i++)
		{
			$last=time()-$array["data"]["replies"][$i]["opentime"];
			$release=$array["data"]["replies"][$i]["opentime"];
			$last2=time()-$array["data"]["replies"][$i]["latest"];
			$latest=$array["data"]["replies"][$i]["latest"];
			echo "
			<div class='wikis-element' id='wikis-element$i'>
				<div class='title' id='wikis-element-title$i'><a ".((count($array["data"]["replies"][$i]["history"])>0)?("onclick=locate('".$config["server"]."/".$config["main-path"]."&type=wiki&id=".$array["data"]["replies"][$i]["id"]."&version=".count($array["data"]["replies"][$i]["history"])."&page=1')"):"").">".$array["data"]["replies"][$i]["name"]."</a></div>
				<div class='info' id='wikis-element-info$i'>
					<p>".$language["star-wikis-version-number"].": v1.".count($array["data"]["replies"][$i]["history"])."&nbsp;&nbsp;&nbsp;&nbsp;".$language["star-wikis-view"].":".$array["data"]["replies"][$i]["view"]."</p>
					<p>".$language["star-wikis-release-time"].":".
					(($last<60)?$language["star-wikis-short-time"]:
					(($last<60*60)?round($last/60).$language["star-wikis-minute"]:
					(($last<60*60*24)?round($last/60/60).$language["star-wikis-hour"]:
					((date("Y",$release)!=date("Y",time()))?
					date("Y-m-d",$release):
					date("m-d H:i",$release)))))."</p>
					<p>".$language["star-wikis-latest-time"].":".
					(($latest==0)?"---":
					(($last2<60)?$language["star-wikis-short-time"]:
					(($last2<60*60)?round($last2/60).$language["star-wikis-minute"]:
					(($last2<60*60*24)?round($last2/60/60).$language["star-wikis-hour"]:
					((date("Y",$latest)!=date("Y",time()))?
					date("Y-m-d",$latest):
					date("m-d H:i",$latest))))))."</p>
				</div>
			</div>
			";
		}
		$ps=$config["star-$type-number"];
		$num_array=json_decode(strip_tags(GetFromHTML($config["api-server-address"]."/star/max.php?type=$type")),true);
		$num=$num_array["max"];
		CreatePageList($num,$ps,$config["star-path"]."&type=".$type);
	}
?>