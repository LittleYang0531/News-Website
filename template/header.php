<?php
	$array=json_decode(strip_tags(GetFromHTML($config["api-server-address"]."/check/login.php")),true);
	$background="";
	if ($array["data"]["isLogin"]==0) $background=$config["default-background"];
	else $background=$array["data"]["user"]["background"];
?>
<meta charset="utf-8">
<style>
	body{background-image:url("<?php echo $background;?>");}
	#toolbar-sign{background-image:url("<?php echo $config["sign-addr"]?>");}
	#toolbar-news{display:<?php echo ($config["enable-article"])?"block":"none"?>}
	#toolbar-wiki{display:<?php echo ($config["enable-wikis"])?"block":"none"?>}
	#toolbar-notice{display:<?php echo ($config["enable-notice"])?"block":"none"?>}
	#toolbar-document{display:<?php echo ($config["enable-document"])?"block":"none"?>}
	#toolbar-drawing-bed{display:
	<?php 
		$url=$config["api-server-address"]."/check/login.php";
		$islogin=json_decode(strip_tags(GetFromHTML($url)),true)["data"]["isLogin"];
		$url=$config["api-server-address"]."/user/info.php?uid=".$_COOKIE["DedeUserId"];
		$isadmin=json_decode(strip_tags(GetFromHTML($url)),true)["data"]["authority"];
		echo (($isadmin||$config["enable-all-upload"])&&$config["enable-drawing-bed"])?"block":"none";
	?>
	}
/* 	#toolbar-video{display:<?php echo ($config["enable-video"])?"block":"none"?>}
	#toolbar-music{display:<?php echo ($config["enable-music"])?"block":"none"?>} 
	#toolbar-social{display:<?php echo ($config["enable-society"])?"block":"none"?>} */
</style>
<style>@import url("<?php echo $config["style-css-path"]?>")</style>
<script src="<?php echo $config["extension-data"]."/jQuery/jQuery.js"?>"></script>
<script src="<?php echo $config["extension-data"]."/layer/layer.js"?>"></script>
<script src="<?php echo $config["extension-data"]."/JsEncrypt/jsencrypt.js"?>"></script>
<link rel="stylesheet" href="<?php echo $config["extension-data"]."/Share.js/css/share.min.css"?>">
<script src="<?php echo $config["extension-data"]."/Share.js/js/share.min.js"?>"></script>
<script src="<?php echo $config["extension-data"]."/Share.js/js/qrcode.js"?>"></script>
<script src="<?php echo $config["extension-data"]."/wangEditor/wangEditor.min.js"?>"></script>
<script src="<?php echo $config["extension-data"]."/highlight.js/highlight.min.js"?>"></script>
<link rel="stylesheet" href="<?php echo $config["extension-data"]."/highlight.js/styles/github-dark.min.css"?>">
<link rel="shortcut icon" href="<?php echo $config["icon-addr"]?>" />