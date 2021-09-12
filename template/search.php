<?php
	$keyword=$_GET["key"];
	$type=$_GET["type"];
	$column=$_GET["column"];
	$uid=$_GET["uid"];
	$sort=$_GET["sort"];
	$page=($_GET["page"]==""||$_GET["page"]<=0)?1:$_GET["page"];
	$_GET["page"]=$page;
	if ($type!="column"&&$sort=="overtime") $sort="hot";
?>
<script>
	document.getElementById("toolbar-search-box").value=<?php echo "\"".$_GET['key']."\"";?>;
</script>
<div class="search-empty-element" style="display:<?php echo (($_GET["key"]!=""||$_GET["mode"]=="advance")?"none":"block");?>">
	<style>
		#search-icon{background-image:url("<?php echo $config["sign-addr"]?>");}
		<?php
			if ($_GET["key"]==""&&$_GET["mode"]!="advance") echo "
				.main
				{
					width:380px;
					margin:auto;
					min-height:300px;
					margin-top:180px;
					margin-bottom:180px;
					padding-left:40px;
					padding-right:40px;
					padding-top:50px;
				}
				
				@media screen and (min-width: 1160px) {
				    .main
				    {
						margin-top:255px;
						margin-bottom:255px;
				    }
				}
				@media screen and (max-width: 1160px) {
				    .main
				    {
						margin-top:225px;
						margin-bottom:225px;
				    }
				}
				.main > h2
				{
					margin:0px;
					margin-top:5px;
					margin-bottom:20px;
				}
			";
		?>
	</style>
	<div style="display:inline-block;" id="website-logo">
		<div class="search-icon" id="search-icon" style="display:inline-block"></div>
		<p id="website-name" style="display:inline-block"><?php echo $config["website-name"];?></p>
	</div>
	<h2 style="width:100%;"><?php echo $language["search-empty-intitle"];?></h2>
	<div>
		<p class="input-p" id="type-p" style="line-height:32px;"><?php echo $language["search-type-input"];?>:&nbsp
			<select class="input" id="search-type-input" style="height:29px;">
				<option value="article" style="display:<?php echo (($config["enable-article"])?"block":"none");?>"><?php echo $language["search-select-type-article"]?></option>
				<option value="column" style="display:<?php echo (($config["enable-article"])?"block":"none");?>"><?php echo $language["search-select-type-column"]?></option>
				<option value="wikis" style="display:<?php echo (($config["enable-wikis"])?"block":"none");?>"><?php echo $language["search-select-type-wikis"]?></option>
				<option value="notice" style="display:<?php echo (($config["enable-notice"])?"block":"none");?>"><?php echo $language["search-select-type-notice"]?></option>
				<!-- <option value="user"><?php echo $language["search-select-type-user"]?></option> -->
			<select>
		</p>
		<p class="input-p" id="keyword-p"><?php echo $language["search-keyword-input"];?>:&nbsp;<input type="text" placeholder="<?php echo $language["search-keyword-description"];?>" class="input" id="search-keyword-input"></p>
		<div class="superlink"><a href="<?php echo $config["server"]."/".$config["search-path"]."&mode=advance"?>"><?php echo $language["search-advance"]?></a></div>
	</div>
	<br>
	<button id="submit" onclick=submit()><strong><?php echo $language["search-submit"];?></strong></button>
	<script>
		function submit()
		{
			var k=document.getElementById("search-keyword-input").value;
			var type=document.getElementById("search-type-input").value;
			if (k!="") window.location.href=<?php echo "\"".$config["server"]."/".$config["search-path"]."&type=\"+type+\"&page=1&key=\"+k"?>;
		}
	</script>
</div>
<div class="search-not-empty-element" style="display:<?php echo (($_GET["key"]==""||$_GET["mode"]=="advance")?"none":"block");?>">
	<h2 style="width:100%;"><center><?php echo $language["search-intitle"];?></center></h2>
	<div id="search-type-element">
		<button id="search-article-element" class="search-element" onclick="change('article')" style="display:<?php echo (($config["enable-article"])?"inline-block":"none");?>"><?php echo $language["search-type-article"];?></button>
		<button id="search-column-element" class="search-element" onclick="change('column')" style="display:<?php echo (($config["enable-article"])?"inline-block":"none");?>"><?php echo $language["search-type-column"];?></button>
		<button id="search-wikis-element" class="search-element" onclick="change('wikis')" style="display:<?php echo (($config["enable-wikis"])?"inline-block":"none");?>"><?php echo $language["search-type-wikis"];?></button>
		<button id="search-notice-element" class="search-element" onclick="change('notice')" style="display:<?php echo (($config["enable-notice"])?"inline-block":"none");?>"><?php echo $language["search-type-notice"];?></button>
		<button id="search-notice-element" class="search-element" onclick="locate_nogui('<?php echo $config["server"]."/".$config["search-path"]."&mode=advance&key=$keyword&column=$column&type=$type&uid=$uid&sort=$sort"?>')"><?php echo $language["search-type-advance"];?></button>
		<!-- <button id="search-user-element" class="search-element" onclick="change('user')"><?php echo $language["search-type-user"];?></button> -->
	</div>
	<br>
	<div id="search-article-result" style="display:<?php echo ($_GET["type"]=="article"?"block":"none")?>">
		<?php
			$url=$config["api-server-address"]."/search/article.php?l=".(1+($page-1)*$config["search-article-number"])."&r=".($page*$config["search-article-number"]);
			if ($column!="") $url.="&column=$column";
			if ($uid!="") $url.="&url=$url";
			if ($sort!="") $url.="&sort=$sort";
			$url.="&keyword=$keyword";
			$json=GetFromHTML($url);
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
						<p>".$language["search-article-writer"].": @<location onclick=locate('".$config["server"]."/".$config["profile-path"]."&uid=".$array["data"]["replies"][$i]["author"]["uid"]."')>".$array["data"]["replies"][$i]["author"]["name"]."</location></p>
						<p>".$language["search-article-like"].":".$array["data"]["replies"][$i]["like"]."&nbsp;&nbsp;&nbsp;&nbsp;".$language["search-article-view"].":".$array["data"]["replies"][$i]["view"]."</p>
						<p>".$language["search-article-release-time"].":".
						(($last<60)?$language["search-article-short-time"]:
						(($last<60*60)?round($last/60).$language["search-article-minute"]:
						(($last<60*60*24)?round($last/60/60).$language["search-article-hour"]:
						((date("Y",$release)!=date("Y",time()))?
						date("Y-m-d",$release):
						date("m-d H:i",$release)))))."</p>
					</div>
				</div>";
			}
			if (count($array["data"]["replies"])==0) echo "<p><center>".$language["search-article-no-result"]."</center></p>";
			$url=$config["api-server-address"]."/search/maxarticle.php?keyword=$keyword";
			if ($column!="") $url.="&column=$column";
			if ($uid!="") $url.="&url=$url";
			if ($sort!="") $url.="&sort=$sort";
			$json=GetFromHTML($url);
			$num=json_decode(strip_tags($json),true)["max"];
			$ps=$config["search-article-number"];
			$url=$config["search-path"]."&type=$type&keyword=$keyword";
			if ($column!="") $url.="&column=$column";
			if ($uid!="") $url.="&url=$url";
			if ($sort!="") $url.="&sort=$sort";
			if (count($array["data"]["replies"])!=0) CreatePageList($num,$ps,$url);
		?>
	</div>
	<div id="search-column-result" style="display:<?php echo ($_GET["type"]=="column"?"block":"none")?>">
		<?php
			$url=$config["api-server-address"]."/search/column.php?l=1&r=".$config["search-column-number"]."&keyword=$keyword";
			if ($sort!="") $url.="&sort=$sort";
			$json=GetFromHTML($url);
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
						<p>".$language["search-column-article-number"].":".count($array["data"]["replies"][$i]["article"])."&nbsp;&nbsp;&nbsp;&nbsp;".$language["search-column-view"].":".$array["data"]["replies"][$i]["view"]."</p>
						<p>".$language["search-column-release-time"].":".
						(($last<60)?$language["search-column-short-time"]:
						(($last<60*60)?round($last/60).$language["search-column-minute"]:
						(($last<60*60*24)?round($last/60/60).$language["search-column-hour"]:
						((date("Y",$release)!=date("Y",time()))?
						date("Y-m-d",$release):
						date("m-d H:i",$release)))))."</p>
						<p>".$language["search-column-over-time"].":".
						(($remain>0)?
						(($remain<60)?$language["search-column-short-time-after"]:
						(($remain<60*60)?round($remain/60).$language["search-column-minute-after"]:
						(($remain<60*60*24)?round($remain/60/60).$language["search-column-hour-after"]:
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
			if (count($array["data"]["replies"])==0) echo "<p><center>".$language["search-column-no-result"]."</center></p>";
			$url=$config["api-server-address"]."/search/maxcolumn.php?keyword=$keyword";
			$json=GetFromHTML($url);
			$num=json_decode(strip_tags($json),true)["max"];
			$ps=$config["search-column-number"];
			$url=$config["search-path"]."&type=$type&keyword=$keyword";
			if (count($array["data"]["replies"])!=0) CreatePageList($num,$ps,$url);
		?>
	</div>
	<div id="search-wikis-result" style="display:<?php echo ($_GET["type"]=="wikis"?"block":"none")?>">
		<?php
			$url=$config["api-server-address"]."/search/wikis.php?l=1&r=".$config["search-wikis-number"]."&keyword=$keyword";
			if ($sort!="") $url.="&sort=$sort";
			$json=GetFromHTML($url);
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
						<p>".$language["search-wikis-version-number"].": v1.".count($array["data"]["replies"][$i]["history"])."&nbsp;&nbsp;&nbsp;&nbsp;".$language["search-wikis-view"].":".$array["data"]["replies"][$i]["view"]."</p>
						<p>".$language["search-wikis-release-time"].":".
						(($last<60)?$language["search-wikis-short-time"]:
						(($last<60*60)?round($last/60).$language["search-wikis-minute"]:
						(($last<60*60*24)?round($last/60/60).$language["search-wikis-hour"]:
						((date("Y",$release)!=date("Y",time()))?
						date("Y-m-d",$release):
						date("m-d H:i",$release)))))."</p>
						<p>".$language["search-wikis-latest-time"].":".
						(($latest==0)?"---":
						(($last2<60)?$language["search-wikis-short-time"]:
						(($last2<60*60)?round($last2/60).$language["search-wikis-minute"]:
						(($last2<60*60*24)?round($last2/60/60).$language["search-wikis-hour"]:
						((date("Y",$latest)!=date("Y",time()))?
						date("Y-m-d",$latest):
						date("m-d H:i",$latest))))))."</p>
					</div>
				</div>
				";
			}
			if (count($array["data"]["replies"])==0) echo "<p><center>".$language["search-wikis-no-result"]."</center></p>";
			$url=$config["api-server-address"]."/search/maxwikis.php?keyword=$keyword";
			$json=GetFromHTML($url);
			$num=json_decode(strip_tags($json),true)["max"];
			$ps=$config["search-wikis-number"];
			$url=$config["search-path"]."&type=$type&keyword=$keyword";
			if (count($array["data"]["replies"])!=0) CreatePageList($num,$ps,$url);
		?>
	</div>
	<div id="search-notice-result" style="display:<?php echo ($_GET["type"]=="notice"?"block":"none")?>">
		<?php
			$url=$config["api-server-address"]."/search/notice.php?l=1&r=".$config["search-notice-number"]."&keyword=$keyword";
			if ($sort!="") $url.="&sort=$sort";
			$json=GetFromHTML($url);
			$array=json_decode(strip_tags($json),true);
			for ($i=0;$i<count($array["data"]["replies"]);$i++)
			{
				$last=time()-$array["data"]["replies"][$i]["release"];
				$release=$array["data"]["replies"][$i]["release"];
				echo "
				<div class='notice-element' id='notice-element$i'>
					<div class='title' id='notice-element-title$i'><a onclick=locate('".$config["server"]."/".$config["main-path"]."&type=notice&id=".$array["data"]["replies"][$i]["id"]."&page=1')>".$array["data"]["replies"][$i]["name"]."</a></div>
					<div class='info' id='notice-element-info$i'>
						<p>".$language["search-notice-writer"].": ".$array["data"]["replies"][$i]["author"]["name"]."</p>
						<p>".$language["search-notice-view"].":".$array["data"]["replies"][$i]["view"]."&nbsp;&nbsp;&nbsp;&nbsp;".$language["search-notice-release-time"].":".
						(($last<60)?$language["search-notice-short-time"]:
						(($last<60*60)?round($last/60).$language["search-notice-minute"]:
						(($last<60*60*24)?round($last/60/60).$language["search-notice-hour"]:
						((date("Y",$release)!=date("Y",time()))?
						date("Y-m-d",$release):
						date("m-d H:i",$release)))))."</p>
					</div>
				</div>
				";
			}
			if (count($array["data"]["replies"])==0) echo "<p><center>".$language["search-notice-no-result"]."</center></p>";
			$url=$config["api-server-address"]."/search/maxnotice.php?keyword=$keyword";
			$json=GetFromHTML($url);
			$num=json_decode(strip_tags($json),true)["max"];
			$ps=$config["search-notice-number"];
			$url=$config["search-path"]."&type=$type&keyword=$keyword";
			if (count($array["data"]["replies"])!=0) CreatePageList($num,$ps,$url);
		?>
	</div>
<!-- 	<div id="search-user-result" style="display:<?php echo ($_GET["type"]=="user"?"block":"none")?>">
		
	</div> -->
</div>
<div class="search-advance-element" style="display:<?php echo ($_GET["mode"]=="advance")?"block":"none";?>">
	<style>
		#search-advance-icon{background-image:url("<?php echo $config["sign-addr"]?>");}
		<?php
			if ($_GET["mode"]=="advance") echo "
				.main
				{
					width:440px;
					margin:auto;
					min-height:380px;
					margin-top:180px;
					margin-bottom:180px;
					padding-left:40px;
					padding-right:40px;
					padding-top:50px;
				}
				
				@media screen and (min-width: 1160px) {
				    .main
				    {
						margin-top:215px;
						margin-bottom:215px;
				    }
				}
				@media screen and (max-width: 1160px) {
				    .main
				    {
						margin-top:185px;
						margin-bottom:185px;
				    }
				}
				.main > h2
				{
					margin:0px;
					margin-top:5px;
					margin-bottom:20px;
				}
			";
		?>
	</style>
	<div style="display:inline-block;" id="website-logo">
		<div class="search-advance-icon" id="search-advance-icon" style="display:inline-block"></div>
		<p id="website-name2" style="display:inline-block"><?php echo $config["website-name"];?></p>
	</div>
	<h2 style="width:100%;"><?php echo $language["search-advance-intitle"];?></h2>
	<p class="input-p" id="search-advance-type-p" style="line-height:32px;"><?php echo $language["search-advance-type-input"];?>:&nbsp
		<select class="input" id="search-advance-type-input" style="height:29px;">
			<option value="article" style="display:<?php echo (($config["enable-article"])?"block":"none");?>"><?php echo $language["search-advance-select-type-article"]?></option>
			<option value="column" style="display:<?php echo (($config["enable-article"])?"block":"none");?>"><?php echo $language["search-advance-select-type-column"]?></option>
			<option value="wikis" style="display:<?php echo (($config["enable-wikis"])?"block":"none");?>"><?php echo $language["search-advance-select-type-wikis"]?></option>
			<option value="notice" style="display:<?php echo (($config["enable-notice"])?"block":"none");?>"><?php echo $language["search-advance-select-type-notice"]?></option>
			<!-- <option value="user"><?php echo $language["search-advance-select-type-user"]?></option> -->
		<select>
	</p>
	<p class="input-p" id="search-advance-keyword-p"><?php echo $language["search-advance-keyword-input"];?>:&nbsp;<input type="text" placeholder="<?php echo $language["search-advance-keyword-description"];?>" class="input" id="search-advance-keyword-input"></p>
	<p class="input-p" id="search-advance-column-p"><?php echo $language["search-advance-column-input"];?>:&nbsp;<input type="text" placeholder="<?php echo $language["search-advance-column-description"];?>" class="input" id="search-advance-column-input"></p>
	<p class="input-p" id="search-advance-uid-p"><?php echo $language["search-advance-uid-input"];?>:&nbsp;<input type="text" placeholder="<?php echo $language["search-advance-uid-description"];?>" class="input" id="search-advance-uid-input"></p>
	<p class="input-p" id="search-advance-sort-p" style="line-height:32px;"><?php echo $language["search-advance-type-input"];?>:&nbsp
		<select class="input" id="search-advance-sort-input" style="height:29px;">
			<option value="hot"><?php echo $language["search-advance-select-sort-hot"]?></option>
			<option value="starttime"><?php echo $language["search-advance-select-sort-starttime"]?></option>
			<option value="overtime"><?php echo $language["search-advance-select-sort-overtime"]?></option>
			<!-- <option value="user"><?php echo $language["search-advance-select-sort-user"]?></option> -->
		<select>
	</p>
	<button id="advance-submit" onclick=advance_submit()><strong><?php echo $language["search-advance-submit"];?></strong></button>
	<script>
		<?php if ($keyword!="") echo "document.getElementById(\"search-advance-keyword-input\").value=\"$keyword\""?>;
		<?php if ($column!="") echo "document.getElementById(\"search-advance-column-input\").value=\"$column\""?>;
		<?php if ($uid!="") echo "document.getElementById(\"search-advance-uid-input\").value=\"$uid\""?>;
		<?php if ($sort!="") echo "document.getElementById(\"search-advance-sort-input\").value=\"$sort\""?>;
		<?php if ($type!="") echo "document.getElementById(\"search-advance-type-input\").value=\"$type\""?>;
		function advance_submit()
		{
			var k=document.getElementById("search-advance-keyword-input").value;
			var type=document.getElementById("search-advance-type-input").value;
			var column=document.getElementById("search-advance-column-input").value;
			var uid=document.getElementById("search-advance-uid-input").value;
			var sort=document.getElementById("search-advance-sort-input").value;
			var url=<?php echo "\"".$config["server"]."/".$config["search-path"]."\""?>;
			if (k!="") url+="&key="+k;
			if (type!="") url+="&type="+type;
			if (column!="") url+="&column="+column;
			if (uid!="") url+="&uid="+uid;
			if (sort!="") url+="&sort="+sort;
			if (k!="") window.location.href=url+"&page=1";
		}
	</script>
</div>
<script>
	function change(type)
	{
		url=<?php echo "\"".$config["server"]."/".$config["search-path"]."&type=\"+type+\"&page=1&key=$keyword\""?>;
		url+=<?php echo "\"";if ($column!="") echo "&column=$column";echo "\"";?>;
		url+=<?php echo "\"";if ($uid!="") echo "&uid=$uid";echo "\"";?>;
		url+=<?php echo "\"";if ($sort!="") echo "&sort=$sort";echo "\"";?>;
		locate_nogui(url);
	}
	$(document).keypress(function(event){
		var keynum=(event.keyCode?event.keyCode:event.which);  
		if(keynum=='13'){  
			document.getElementById(<?php echo "\"".($_GET["mode"]=="advance"?"advance-submit":"submit")."\""?>).click();
		}  
	});
</script>