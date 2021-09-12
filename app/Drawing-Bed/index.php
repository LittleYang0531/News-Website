<?php
	require_once "../../config/index.php";
	require_once "../../".$config["function-path"];
	$language_name="";
	for ($i=0;$i<count($config["language-source"]);$i++) if ($config["language-source"][$i]["code"]==$config["language"]) $language_name=$config["language-source"][$i]["path"];
	$language_path="../../".$config["language-data"]."/".$language_name;
	require_once $language_path;
	if (!$config["enable-drawing-bed"])
	{
		echo "<script>alert(\"".$language["drawing-bed-unabled"]."\")</script>";
		echo "<script>window.history.back(-1)</script>";
		exit;
	}
	$url=$config["api-server-address"]."/check/login.php";
	$islogin=json_decode(strip_tags(GetFromHTML($url)),true)["data"]["isLogin"];
	$url=$config["api-server-address"]."/user/info.php?uid=".$_COOKIE["DedeUserId"];
	$isadmin=json_decode(strip_tags(GetFromHTML($url)),true)["data"]["authority"];
	if (!$islogin)
	{
		echo "<script>alert(\"".$language["drawing-bed-no-login"]."\")</script>";
		echo "<script>window.history.back(-1)</script>";
		exit;
	}
	if ($config["enable-all-upload"]&&!$isadmin)
	{
		echo "<script>alert(\"".$language["drawing-bed-no-admin"]."\")</script>";
		echo "<script>window.history.back(-1)</script>";
		exit;
	}
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
	<meta charset="utf-8" />
	<title>图床</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./static/layui/css/layui.css">
    <link rel="stylesheet" href="./static/css/mystyle.css">
    <script src="./static/js/jquery.min.js"></script>
    <script src="./static/js/Message.js"></script>
	<link rel="icon" href="/static/images/favicon.png" />
</head>
<body>
    <div id="left-gg"></div>
    <div id="right-gg"></div>
    <div class="header">
        <div class="layui-container">
            <div class="layui-row">
                <div class="layui-col-lg12">
                    
                    <div class="layui-hide-xs menu">
                        <ul class="layui-nav" lay-filter="">
                            <li class="layui-nav-item"><a id="alert2"><i class="layui-icon"></i> 使用协议</a></li>
                        <span class="layui-nav-bar"></span></ul>
                    </div>
                    <div class="menu layui-hide-lg layui-hide-md layui-hide-sm">
                        <ul class="layui-nav" lay-filter="">
                            <li class="layui-nav-item"><a id="alert"><i class="layui-icon"></i> 使用协议</a></li>
                        <span class="layui-nav-bar" style="left: 62.9375px; top: 55px; width: 0px; opacity: 0;"></span></ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- 顶部导航栏END --><div class="layui-container">
    <div class="layui-row">
        <!-- 首页主要区域 -->
        <div class="layui-col-lg12">
            <div id="main">
                <div class="alert alert-warning" role="alert">
                    <span class="alert-inner--icon"><i class="layui-icon"></i></span>
                    <span class="alert-inner--text">仅支持单文件,大小限制2MB,本站图床存储于<a style="color: red;" href="https://github.com/">Github</a>/<a style="color: red;" href="https://gitee.com/">Gitee</a>且由<a style="color: red;" href="https://www.jsdelivr.com/">Jsdelivr</a>提供全球CDN加速 &nbsp;&nbsp; 
			
		</span>
                </div>
                <!-- 上传区域 -->
                <div class="layui-form-item">
                
<div class="layui-upload-drag" id="upimg" name="pic">
                    <i class="layui-icon"></i>
                    <p>点击上传，拖拽至此处亦或者粘贴(Ctrl+V)图片</p>
                </div>
                </div>
                <!-- 上传区域END -->
            </div>
        </div>
        <!-- 首页主要区域END -->
    </div>
    <div class="layui-row">
        <div class="layui-col-lg12">
            <!-- 上传进度条 -->
            <div class = "progress">
                <div class="layui-progress layui-progress-big" lay-filter="uploadProgress" lay-showPercent="true">
                    <div class="layui-progress-bar" lay-percent="0%"></div>
                </div>
            </div>
            <!-- 上传进度条END -->
        </div>
        <div class="layui-col-lg12" id = "imgshow">
            <!-- 图片显示区域 -->
            <!-- 显示缩略图 -->
            <div class="layui-col-lg4">
                <div id = "img-thumb" style="padding-top: 1em;"><img src="" alt="查看详情"></div>
            </div>
            <!-- 显示地址 -->
            <div class="layui-col-lg7 layui-col-md-offset1">
                <div id="links">
                    <table class="layui-table" lay-skin="nob" lay-size="sm">
                        <colgroup>
                            <col width="100">
                            <col width="450">
                            <col>
                        </colgroup>
                        <tbody>
                            <tr>
                                <td>URL</td>
                                <td><input type="text" class="layui-input" id="url" data-cip-id="url"></td>
                                <td><a href="javascript:;" class="layui-btn layui-btn-sm" onclick="copyurl('url')">复制</a></td>
                            </tr>                            
                            <tr>
                                <td>UBB</td>
                                <td><input type="text" class="layui-input" id="bbcode" data-cip-id="bbcode"></td>
                                <td><a href="javascript:;" class="layui-btn layui-btn-sm" onclick="copyurl('bbcode')">复制</a></td>
                            </tr>
                            <tr>
                                <td>markdown</td>
                                <td><input type="text" class="layui-input" id="markdown" data-cip-id="markdown"></td>
                                <td><a href="javascript:;" class="layui-btn layui-btn-sm" onclick="copyurl('markdown')">复制</a></td>
                            </tr>                            							
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 图片显示区域END -->
        </div>
    </div>
</div>
<script src="./static/layui/layui.js"></script>
    <script src="./static/js/embed.js"></script>
    <script src="./static/js/clipBoard.min.js"></script>
    <script> document.getElementById('alert').onclick = function () { alert('<ul><li>不得上传色情、暴力、政治的内容</li><li>不得上传侵犯版权、隐私权的内容</li><li>违反规定直接删除图片不另行通知</li><li>所有图片仅供网站内部使用</li></ul>') } </script>
    <script> document.getElementById('alert2').onclick = function () { alert('<ul><li>不得上传色情、暴力、政治的内容</li><li>不得上传侵犯版权、隐私权的内容</li><li>违反规定直接删除图片不另行通知</li><li>所有图片仅供网站内部使用</li></ul>') } </script>	
</body>
</html>