<?php
	$res=json_decode(strip_tags(GetFromHTML($config["api-server-address"]."/check/login.php")),true);
	// var_dump($res);
	// echo ReturnCookieString();
?>
<div class="toolbar-left">
	<div class="toolbar-left-element" id="toolbar-sign" onclick=locate_nogui("<?php echo $config["server"]."/".$config["index-path"]?>")></div>	
	<div class="toolbar-left-element" id="toolbar-news"><p onclick=locate_nogui("<?php echo $config["server"]."/".$config["news-path"]."&page=1"?>")><?php echo $language["toolbar-news"]?></p></div>
	<div class="toolbar-left-element" id="toolbar-wiki"><p onclick=locate_nogui("<?php echo $config["server"]."/".$config["wiki-path"]."&page=1"?>")><?php echo $language["toolbar-wiki"]?></p></div>
	<div class="toolbar-left-element" id="toolbar-notice"><p onclick=locate_nogui("<?php echo $config["server"]."/".$config["notice-path"]."&page=1"?>")><?php echo $language["toolbar-notice"]?></p></div>
	<!-- <div class="toolbar-left-element" id="toolbar-document"><p onclick=locate_nogui("<?php echo $config["server"]."/".$config["document-path"]."&page=1"?>")><?php echo $language["toolbar-document"]?></p></div> -->
	<div class="toolbar-left-element" id="toolbar-drawing-bed"><p onclick=locate_nogui("<?php echo $config["server"]."/".$config["drawing-bed-path"]?>")><?php echo $language["toolbar-picture-bed"]?></p></div>
<!--<div class="toolbar-left-element" id="toolbar-video"><p onclick=locate_nogui("<?php echo $config["server"]."/".$config["video-path"]."&page=1"?>")><?php echo $language["toolbar-video"]?></p></div>
	<div class="toolbar-left-element" id="toolbar-music"><p onclick=locate_nogui("<?php echo $config["server"]."/".$config["music-path"]."&page=1"?>")><?php echo $language["toolbar-music"]?></p></div>
	<div class="toolbar-left-element" id="toolbar-society"><p onclick=locate_nogui("<?php echo $config["server"]."/".$config["society-path"]."&page=1"?>")><?php echo $language["toolbar-society"]?></p></div> -->
</div>
<div class="toolbar-search" id="toolbar-search">
	<input type="text" placeholder="<?php echo $language["toolbar-search-placeholder"]?>" id="toolbar-search-box">
	<button onclick="search()" id="toolbar-search-button"><?php echo $language["toolbar-search"]?></button>
</div>
<div class="toolbar-right">
	<div class="toolbar-right-element" id="toolbar-search-element"><p onclick=locate_nogui("<?php echo $config["server"]."/".$config["search-path"]."&page=1"?>")><?php echo $language["toolbar-search"]?></p></div>
	<div class="toolbar-right-element" id="toolbar-history"><p onclick=locate_nogui("<?php echo $config["server"]."/".((!$res["data"]["isLogin"])?$config["login-path"]."&return=".str_replace("&","%26",$config["server"]."/".$config["history-path"]):$config["history-path"]."&page=1")?>")><?php echo $language["toolbar-history"]?></p></div>
	<div class="toolbar-right-element" id="toolbar-message"><p onclick=locate_nogui("<?php echo $config["server"]."/".((!$res["data"]["isLogin"])?$config["login-path"]."&return=".str_replace("&","%26",$config["server"]."/".$config["message-path"]):$config["message-path"])?>")><?php echo $language["toolbar-message"]?></p></div>
	<div class="toolbar-right-element" id="toolbar-star"><p onclick=locate_nogui("<?php echo $config["server"]."/".((!$res["data"]["isLogin"])?$config["login-path"]."&return=".str_replace("&","%26",$config["server"]."/".$config["star-path"]."&page=1"):$config["star-path"]."&page=1")?>")><?php echo $language["toolbar-star"]?></p></div>
	<div class="toolbar-right-element" id="toolbar-upload"><p onclick=locate_nogui("<?php echo $config["server"]."/".((!$res["data"]["isLogin"])?$config["login-path"]."&return=".str_replace("&","%26",$config["server"]."/".$config["upload-path"]."&page=1"):$config["upload-path"])?>")><?php echo $language["toolbar-upload"]?></p></div>
	<div class="toolbar-right-element" id="toolbar-login" style="display:<?php echo ((!$res["data"]["isLogin"])?"block":"none");?>"><p onclick=locate_nogui("<?php echo $config["server"]."/".$config["login-path"]."&return=".(($_GET["method"]=="login")?str_replace("&","%26",$_GET["return"]):$config['server'].$_SERVER['PHP_SELF'].'?'.str_replace("&","%26",$_SERVER['QUERY_STRING']))?>")><?php echo $language["toolbar-login"]?></p></div>
	<div class="toolbar-right-element" id="toolbar-header" style="width:52px;display:<?php echo (($res["data"]["isLogin"])?"block":"none");?>"><p style="margin:0px;" onclick=locate_nogui("<?php echo $config["server"]."/".$config["setting-path"];?>")><img src="<?php echo $res["data"]["user"]["header"];?>" class="toolbar-header"/></p></div>	
</div>
<script>
	adjust_toolbar();adjust_toolbar();adjust_toolbar();
	function adjust_toolbar()
	{
		document.getElementById("toolbar-search").style.display="block";
		document.getElementById("toolbar-search-element").style.display="none";
		if (document.getElementById("toolbar-search-box").offsetWidth<100)
		{
			document.getElementById("toolbar-search").style.display="none";
			document.getElementById("toolbar-search-element").style.display="block";
		}
	}
	window.onresize=function()
	{
		adjust_toolbar();adjust_toolbar();adjust_toolbar();
	}
	function search()
	{
		var k=document.getElementById("toolbar-search-box").value;
		if (k=="") window.location.href=<?php echo "\"".$config["server"]."/".$config["search-path"]."\""?>;
		else window.location.href=<?php echo "\"".$config["server"]."/".$config["search-path"]."&type=article&page=1&key=\"+k"?>;
	}
	$("#toolbar-search-box").keypress(function(event){
		var keynum=(event.keyCode?event.keyCode:event.which);  
		if(keynum=='13'){  
			document.getElementById("toolbar-search-button").click();
		}  
	});
</script>