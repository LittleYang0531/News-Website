<?php
	require_once "../../config/index.php";
	$language_name="";
	for ($i=0;$i<count($config["language-source"]);$i++) if ($config["language-source"][$i]["code"]==$config["language"]) $language_name=$config["language-source"][$i]["path"];
	$language_path="../../".$config["language-data"]."/".$language_name;
	require_once $language_path;
	if (!$config["enable-explorer"])
	{
		echo "<script>alert(\"".$langugae["explorer-unabled"]."\")</script>";
		echo "<script>window.history.back(-1)</script>";
		exit;
	}
	ob_start();
	include ('config/config.php');
	$app = new Application();
	init_config();
	$app->run();
?>
