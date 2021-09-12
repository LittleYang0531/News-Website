<?php 
	require_once "config/index.php";
	require_once $config["function-path"];
	$language_name="";
	for ($i=0;$i<count($config["language-source"]);$i++) if ($config["language-source"][$i]["code"]==$config["language"]) $language_name=$config["language-source"][$i]["path"];
	$language_path=$config["language-data"]."/".$language_name;
	require_once $language_path;
	date_default_timezone_set($config["time-zone"]);
?>
<!DOCTYPE html>
<html>
	<head>
		<?php require_once $config["header-path"];?>
		<style>@import url("<?php echo $config["server"]."/";if ($_GET["method"]!="") echo $config[$_GET["method"]."-css-path"];else echo $config[$config["index-default-page"]."-css-path"];?>")</style>
		<title><?php if ($_GET["method"]!="") echo $language[$_GET["method"]."-title"];else echo $language[$config["index-default-page"]."-title"];?> - <?php echo $config["website-title"];?></title>
	</head>
	<body>
		<script>
			function strip_tags(html) 
			{
				var div=document.createElement("div");
				div.innerHTML=html;
				return (div.textContent||div.innerText||"");
			}
			$.ajax({
				type:"POST",
				url:<?php echo "\"".$config["api-server-address"]."/admin/updata.php"."\"";?>,
				data:{page:<?php echo "\"".$_GET["method"]."\""?>},
				success:function(message)
				{
					var obj=JSON.parse(strip_tags(message));
					console.log(obj["message"]);
				}
			});
		</script>
		<div class="toolbar" id="toolbar">
			<?php require_once $config["toolbar-path"];?>
		</div>
		<div class="main" id="main">
			<?php 
				if ($_GET["method"]!="") require_once $config[$_GET["method"]."-source-path"];
				else require_once $config[$config["index-default-page"]."-source-path"];
			?>
		</div>
		<div class="footer" id="footer" style="display:<?php echo ($config["enable-footer"]?"flex":"none")?>">
			<?php require_once $config["footer-path"];?>
		</div>
		<div class="copyright" id="copyright" style="<?php if (!$config["enable-footer"]) echo "border:1px solid;"?>">
			<p>
				GPLv2 licensed by <a href="//github.com/LittleYang0531/News-Website" target="view_window">News-Website</a> <?php date("Y");?>.
				Theme designed by <a href="//github.com/LittleYang0531" target="view_window">LittleYang0531</a>
			</p>
		</div>
		<script>
			function locate(url) {window.open(url,"_blank");}
			function locate_nogui(url) {window.location.href=url;}
		</script>
	</body>
</html>