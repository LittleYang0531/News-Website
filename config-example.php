<?php 
	global $config;
	$config=array();
	
	//Mysql Database Settings
	$config["mysql-address"]="localhost";//Mysql Server Address 
	$config["mysql-user"]="root";//Mysql User Name 
	$config["mysql-password"]="";//Mysql User Password 
	$config["mysql-database"]="ycnews";//Mysql Database Name 
	
	//Email Settings
	$config["email-account"]="example@test.com";//The Email Account which is used to Send Email 
	$config["email-password"]="12345678";//The Email Account's Password()
	$config["email-server"]="dev.example.com";//The Email Server Address
	$config["email-protocol"]="ssl";//The Email Server Protocol 
	$config["email-port"]=123;//The Email Server Port 
	$config["email-from"]="test";//The Name of Sender
	
	//Routine Settings
	$config["server"]="//localhost";//Server Address
	$config["domain"]="localhost";//Server Domain
	$config["protocol"]="http";//The Protocol of Your Website
	$config["icon-addr"]="";//Icon File Address
	$config["website-title"]="Yucai News Website Powered by LittleYang";//The Title of Your Website
	$config["default-background"]="https://cdn.jsdelivr.net/gh/LittleYang0531/image/2021/02/24/334ea8f78a5f643f9df5f1769dece1d5.png";
	
	//Setup Settings
	$config["setup-background"]="https://cdn.jsdelivr.net/gh/LittleYang0531/image/2021/02/24/334ea8f78a5f643f9df5f1769dece1d5.png";
	$config["setup-title"]="";
	
	//Feature Settings
	$config["enable-article"]=true;//Allowed the Article Features
	$config["enable-notice"]=true;//Allowed the Notice Features
	$config["enable-wiki"]=false;//Allowed the Wiki Features
	$config["enable-drawing-bed"]=true;//Allowed the Drawing Bed
	$config["enable-explorer"]=false;//Allowed the Explorer
	
	//Data Settings 
	$config["article-data"]="data/article";
	$config["notice-data"]="data/notice";
	$config["account-data"]="data/user";
	$config["wiki-data"]="data/wiki";
	$config["language-data"]="data/language";
	$config["extension-data"]="extension";
	
	//Page Settings 
	$config["index-path"]="index.php";
	$config["setup-path"]="setup.php";
	
	//Configuration Source Settings
	$config["language-source"]=array(array(
		'name' => '中国 - 简体中文',
		'code' => 'zh-cn',
		'path' => './zh-cn.php',
	),);
	
	//Default Settings
	$config["language"]="zh-cn";
	
	//Account Settings 
	$config["enable-change-password"]=true;//Allowed Users to Change the Password
	$config["super-admin"]="root";
	
	//Drawing Bed Settings 
	$config["drawing-bed-platform"]="GITHUB";//Support Github and Gitee("GITHUB" for Github and "GITEE" for Gitee,Fill in other Things may Cause some Problem)
	$config["drawing-bed-user-name"]="test";//The Account of Your Drawing Bed Platform
	$config["drawing-bed-repository"]="test";//The Repository Name which is used to Deposit Your Picture
	$config["drawing-bed-email"]="example@test.com";//The Email of the Picture Server(You can Fill in it with Anything)
	$config["drawing-bed-token"]="213";//The Account's Token on the Platform which is used to Deposit Your Picture
	$config["enable-all-manage"]=false;//Allowed Everyone to Watch the Picture Infomation which were Uploaded to the Server
	
	//Home Page Settings 
	$config["index-pictures"]=array (
	);//Picture urls which will be Showed on the Home Page
	$config["home-article-number"]=8;//The Number of Articles which will be Showed on the Home Page
	$config["home-column-number"]=8;//The Number of Columns which will be Showed on the Home Page
	$config["home-wiki-number"]=8;//The Number of Wiki which will be Showed on the Home Page
?>