<?php
	global $config;
	$config=array();
	$config["mysql-address"]="localhost";
	$config["mysql-user"]="root";
	$config["mysql-password"]="";
	$config["mysql-database"]="ycnews";
	$config["email-account"]="example@test.com";
	$config["email-password"]="12345678";
	$config["email-server"]="dev.example.com";
	$config["email-protocol"]="ssl";
	$config["email-port"]=123;
	$config["email-from"]="test";
	$config["server"]="http://192.168.0.11";
	$config["domain"]="192.168.0.11";
	$config["icon-addr"]="./unnecessary/icon/favicon.ico";
	$config["sign-addr"]="./unnecessary/icon/sign.png";
	$config["website-title"]="Yucai News Website Powered by LittleYang";
	$config["website-name"]="育才新闻";
	$config["default-background"]="//cdn.jsdelivr.net/gh/LittleYang0531/image/2021/02/24/334ea8f78a5f643f9df5f1769dece1d5.png";
	$config["default-cover"]="//i0.hdslb.com/bfs/space/70ce28bcbcb4b7d0b4f644b6f082d63a702653c1.png@2560w_400h_100q_1o.webp";
	$config["setup-background"]="//cdn.jsdelivr.net/gh/LittleYang0531/image/2021/02/24/334ea8f78a5f643f9df5f1769dece1d5.png";
	$config["enable-article"]=true;
	$config["enable-wikis"]=true;
	$config["enable-notice"]=true;
	$config["enable-drawing-bed"]=true;
	$config["enable-explorer"]=true;
	$config["enable-document"]=true;
	$config["enable-footer"]=true;
	$config["email-suffix"]=array (
  0 => 'qq.com',
  1 => 'foxmail.com',
  2 => 'outlook.com',
);
	$config["index-default-page"]="index";
	$config["article-data"]="data/article";
	$config["notice-data"]="data/notice";
	$config["account-data"]="data/user";
	$config["wiki-data"]="data/wiki";
	$config["language-data"]="language";
	$config["api-server-address"]="http://192.168.0.11/api";
	$config["extension-data"]="extension";
	$config["index-path"]="index.php?method=index";
	$config["news-path"]="index.php?method=news";
	$config["wiki-path"]="index.php?method=wiki";
	$config["notice-path"]="index.php?method=notice";
	$config["document-path"]="index.php?method=document";
	$config["drawing-bed-path"]="./app/drawing-bed/index.php";
	$config["video-path"]="index.php?method=video";
	$config["music-path"]="index.php?method=music";
	$config["society-path"]="index.php?method=society";
	$config["search-path"]="index.php?method=search";
	$config["history-path"]="index.php?method=history&type=article";
	$config["message-path"]="index.php?method=message";
	$config["star-path"]="index.php?method=star&type=article";
	$config["upload-path"]="index.php?method=upload";
	$config["login-path"]="index.php?method=login";
	$config["register-path"]="index.php?method=register";
	$config["main-path"]="index.php?method=main";
	$config["profile-path"]="index.php?method=profile";
	$config["change-password-path"]="index.php?method=change-password";
	$config["complaint-path"]="index.php?method=complaint";
	$config["setting-path"]="index.php?method=setting";
	$config["admin-path"]="index.php?method=admin";
	$config["setup-path"]="setup.php";
	$config["function-path"]="function.php";
	$config["header-path"]="template/header.php";
	$config["footer-path"]="template/footer.php";
	$config["toolbar-path"]="template/toolbar.php";
	$config["index-source-path"]="template/index.php";
	$config["news-source-path"]="template/news.php";
	$config["wiki-source-path"]="template/wiki.php";
	$config["notice-source-path"]="template/notice.php";
	$config["login-source-path"]="template/login.php";
	$config["main-source-path"]="template/main.php";
	$config["history-source-path"]="template/history.php";
	$config["message-source-path"]="template/message.php";
	$config["star-source-path"]="template/star.php";
	$config["upload-source-path"]="template/upload.php";
	$config["search-source-path"]="template/search.php";
	$config["register-source-path"]="template/register.php";
	$config["profile-source-path"]="template/profile.php";
	$config["setting-source-path"]="template/setting.php";
	$config["admin-source-path"]="template/admin.php";
	$config["language-source"]=array (
  0 => 
  array (
    'name' => '中国 - 简体中文',
    'code' => 'zh-cn',
    'path' => './zh-cn.php',
  ),
);
	$config["language"]="zh-cn";
	$config["super-admin"]="root";
	$config["drawing-bed-platform"]="GITHUB";
	$config["drawing-bed-user-name"]="test";
	$config["drawing-bed-repository"]="test";
	$config["drawing-bed-email"]="example@test.com";
	$config["drawing-bed-token"]="213";
	$config["enable-all-watch"]=true;
	$config["enable-all-upload"]=false;
	$config["index-pictures"]=array (
);
	$config["index-article-number"]=8;
	$config["index-column-number"]=8;
	$config["index-wikis-number"]=8;
	$config["index-notice-number"]=8;
	$config["news-column-number"]=30;
	$config["wiki-page-number"]=30;
	$config["notice-page-number"]=30;
	$config["main-article-number"]=30;
	$config["main-history-number"]=30;
	$config["main-column-article-number"]=30;
	$config["main-wiki-version-number"]=30;
	$config["history-article-number"]=30;
	$config["history-column-number"]=30;
	$config["history-wikis-number"]=30;
	$config["message-comment-number"]=5;
	$config["message-like-number"]=5;
	$config["message-system-number"]=5;
	$config["star-article-number"]=30;
	$config["star-wiki-number"]=30;
	$config["upload-article-number"]=5;
	$config["upload-wiki-number"]=5;
	$config["search-article-number"]=30;
	$config["search-column-number"]=30;
	$config["search-wikis-number"]=30;
	$config["search-notice-number"]=30;
	$config["search-user-number"]=30;
	$config["profile-article-number"]=5;
	$config["profile-follow-number"]=5;
	$config["profile-fans-number"]=5;
	$config["style-css-path"]="css/style.css";
	$config["index-css-path"]="css/index.css";
	$config["news-css-path"]="css/news.css";
	$config["wiki-css-path"]="css/wiki.css";
	$config["notice-css-path"]="css/notice.css";
	$config["login-css-path"]="css/login.css";
	$config["main-css-path"]="css/main.css";
	$config["history-css-path"]="css/history.css";
	$config["message-css-path"]="css/message.css";
	$config["star-css-path"]="css/star.css";
	$config["upload-css-path"]="css/upload.css";
	$config["search-css-path"]="css/search.css";
	$config["register-css-path"]="css/register.css";
	$config["profile-css-path"]="css/profile.css";
	$config["setting-css-path"]="css/setting.css";
	$config["admin-css-path"]="css/admin.css";
	$config["time-zone"]="Asia/Shanghai";
	$config["photo-left-arrow-path"]="./photo/left.png";
	$config["photo-right-arrow-path"]="./photo/right.png";
	$config["photo-black-right"]="./photo/black-right.png";
	$config["photo-article-like"]="./photo/like.png";
	$config["photo-article-unlike"]="./photo/unlike.png";
	$config["photo-article-star"]="./photo/star.png";
	$config["photo-article-unstar"]="./photo/unstar.png";
	$config["photo-article-share"]="./photo/share.png";
	$config["photo-article-unshare"]="./photo/unshare.png";
	$config["photo-comment-like"]="./photo/comment-like.png";
	$config["photo-comment-unlike"]="./photo/comment-unlike.png";
	$config["photo-emoji"]="./photo/emoji.png";
	$config["photo-noface"]="./photo/noface.gif";
	$config["photo-wiki-like"]="./photo/like.png";
	$config["photo-wiki-unlike"]="./photo/unlike.png";
	$config["photo-wiki-star"]="./photo/star.png";
	$config["photo-wiki-unstar"]="./photo/unstar.png";
	$config["photo-wiki-share"]="./photo/share.png";
	$config["photo-wiki-unshare"]="./photo/unshare.png";
	$config["photo-upload-watch-path"]="./photo/watch.png";
	$config["photo-upload-comment-path"]="./photo/comment.png";
	$config["photo-upload-like-path"]="./photo/unlike.png";
	$config["photo-upload-star-path"]="./photo/unstar.png";
	$config["photo-profile-watch-path"]="./photo/watch.png";
	$config["photo-profile-comment-path"]="./photo/comment.png";
	$config["photo-profile-like-path"]="./photo/unlike.png";
	$config["photo-profile-star-path"]="./photo/unstar.png";
	$config["cookie-column-time"]=180000;
	$config["cookie-wikis-time"]=180000;
	$config["cookie-article-time"]=180000;
	$config["cookie-wiki-time"]=180000;
	$config["rsa-public-key"]="-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAw6Yf7j7CRp+G/I+Owupa
9SV0V/beSYu78Zj+l67Ik5v9ptGJM+MXK/g5jJP2AtyrC1qMGMCRuq2JLvD8zMri
1noL02TTSc3plwI+warmGaAzfMXyOFHcL5g2ENMzAGlxE/bYppFQPyP2Up52UnTL
hV4Ov2mFFL2sXF4SVo8Ca1qY3fmw9fc98UnvP82L3YqYRbbquHwFQ/qFQkEzR89N
YM+7mEny1zj+IYThD4FI1xsMnJ2IAAg19o89ARiUlsITvchEJL4UVGfACDM2Uu+C
OzTe1EuRS59e2YLikc8W+nei6xsETav/II9CexnBtfsaA0MdiQMTc5kUQzSthHZD
5wIDAQAB
-----END PUBLIC KEY-----";
	$config["rsa-private-key"]="-----BEGIN PRIVATE KEY-----
MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDDph/uPsJGn4b8
j47C6lr1JXRX9t5Ji7vxmP6XrsiTm/2m0Ykz4xcr+DmMk/YC3KsLWowYwJG6rYku
8PzMyuLWegvTZNNJzemXAj7BquYZoDN8xfI4UdwvmDYQ0zMAaXET9timkVA/I/ZS
nnZSdMuFXg6/aYUUvaxcXhJWjwJrWpjd+bD19z3xSe8/zYvdiphFtuq4fAVD+oVC
QTNHz01gz7uYSfLXOP4hhOEPgUjXGwycnYgACDX2jz0BGJSWwhO9yEQkvhRUZ8AI
MzZS74I7NN7US5FLn17ZguKRzxb6d6LrGwRNq/8gj0J7GcG1+xoDQx2JAxNzmRRD
NK2EdkPnAgMBAAECggEAYJDvM61gQHFes+u7X/NCH7tz2DLt9kj048NK7d0D/O9C
XAMSAD0246np5bvl8fWuqrTvbwwlIYmjqzqg3AfLvGaSzaz3KHdssu8VwIs0dTOA
FjaxXiDxV8B99wH3K13fxSXSOyx/+hoq6w5xgjNJfLM2/jz7xYf0ucZosLZ3UDqS
KmSBrJV9LXOOeetc7J8y0UL9TV8lHdKjgL+xhzyjxGTiLIU8BFJk2cdCeOuVABko
igKxl+8iaDCczRdtzT7OVrCjFUt4K77AJUrbY7IH3Y6bj/GikdaZ82UdH4VgjCM0
g7z0kihnRkB2/D2GxGyduNTHCK5qCYwKkcRpFpQm4QKBgQD3jrOLFpR7RGAZz5w/
5Dp+jX6esu4pa7Wc+5XVN77XH4QSdvt+CKWicCu0e+1ARViZ3HFpzAF1ahz6B3IM
Qjg4XXVr8rNRXKWQBYsNWx4zEMSgo6wfy1aVtz3EaZwAHdZsri1/WEP291JRztzN
SFvrhI3WCypOlgL6wv4Y2B0ZVwKBgQDKUjyMKaSr8D/WdJYRu8l+G3sozwOUDjbT
Tu4lJP74uFXjou36aOQW4KqV13Eh79WMS9sUdLYMbV4AWyCuTBDF9OGB4ESyl3ej
Bt4AskGtofhx7bDn6MorGoFzj9TxEUrrG/ASvYI7eLDJORMm68tnp9F+kA/IXzI8
0L+BULM/8QKBgQDbB95hZKQRAGlKzP7BJOyARi7OuR+xdEQm1g42rXDjo1XWhIF1
fK8YLsjskm1S3UhMMdgCtGZh/XYP3oCQyhI2BLK8xOrVRPSTnePu+DybD+3493d2
VhGQu6Uh4BMPo1axp9ZHgs/3ddHW4gFIfAogMpLP2+cdyupt9hKd5rCwVwKBgQCv
5G7fLBUNpgDZ4OHW6Ptzt1CLWe4yeWkQrD497Lv+X8PL84oReb9SZF/phTPF3Uw6
fHgqgI0EoNBoXtE6tsaeUxb6Yo9W9Hf+M6ot0MtouLfV1F9IPwoEDzcb0J/ANNh9
Lfy5Tig6q+KGDiioXbaoly16aqRN/vUeCh1zv0UXAQKBgQD2De7KRJoHV+IxAlSl
2vWhuO3T/87nwFORLKCtBNKjTtAvvG8tGr0HknkXXHU/zcR/lDQ/L1ap63GzBQV3
4egJA+1Q3TxQUxS2fPTSAp5Pr1nBt5kBziRBbs3phfIPgCkzvb0w1CkUcbMyWZoQ
w0/BwsN310MFUcpNm5TB217H3w==
-----END PRIVATE KEY-----
";
?>