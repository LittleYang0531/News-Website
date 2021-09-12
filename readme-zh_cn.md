# News-Website
![](https://img.shields.io/github/stars/LittleYang0531/News-Website.svg?style=social) ![](https://img.shields.io/github/forks/LittleYang0531/News-Website.svg?style=social) 

[English](./readme.md)  中文简体

## 注意

1. 由于作者要准备即将到来的中考，此仓库即日起至2021年6月14日暂停更新！(2021.06.01)
2. 此项目还未完成！所以如果现在搭建了此系统，将会遇到很多很多的问题，有些功能甚至无法使用！(2021.07.23)

## 项目简介
News-Website是一个在线新闻写作及展示系统，任何人都可以上传他们的文章至此服务器，任何用户都可以了解到最近的新闻（只要此系统有人持续更新的话）

这是作者制作的第一个网站应用，它有着最灵活的配置方法与最全面的功能

该仓库是由作者一人维护的，它也许会有着许多问题。所以如果你在使用过程中发现了问题，请在[Issues · LittleYang0531/News-Website (github.com)](https://github.com/LittleYang0531/News-Website/issues)里报告

如果你想对此仓库做出共享，你可以克隆此仓库至你的本地服务器并作出一些更改。更改完成后，你可以提交至此仓库，在那之后，你的头像将会显示在'contribution'对话框下

## 功能展示

基本功能:

- [x] 新闻分类
- [x] 文章请求
- [x] 评论区实现
- [x] 公告显示
- [x] 历史记录保存与查看
- [x] 用户系统
- [x] 信息管理
- [x] 文章收藏与管理
- [x] 邮件系统
- [x] 文章上传
- [x] 文章查找
- [ ] 文章举报

扩展功能(可选):

- [x] 百科系统
- [ ] 社交系统
- [ ] 文档系统
- [x] 文件资源管理器
- [x] 图片存储服务
- [ ] 视频播放器
- [ ] 音频播放器
- [ ] 其他功能......

API服务:

- [x] 文章服务
- [x] 用户操作服务
- [x] 用户查询服务
- [x] 栏目服务
- [x] 评论服务
- [ ] 文档服务
- [x] 历史记录服务
- [x] 登录服务
- [x] 消息服务
- [ ] 音频服务
- [x] 公告服务
- [x] 搜索服务
- [ ] 举报服务
- [ ] 社交服务
- [x] 百科服务
- [ ] 视频服务

## 搭建过程

### 环境需求

操作系统配置：Windows, Linux, MacOS (我们还未在MacOS上进行测试)

PHP配置：建议7.x及以上

服务器软件配置：Nginx，Apache，IIS... (只要支持PHP都行)

数据库配置：建议MySQL 5.x或MariaDB 10.x

⚠警告：以上配置环境是我们能够确保搭建成功的环境，当前我们还不知道该系统的最低配置

### 手动安装过程

#### 安装样例环境:

对于Windows:

1. Windows版本: Microsoft Windows 11 Professional 10.0.22000 Version 22000 x64

2. PHP版本:

   ```shell
   PHP 7.3.4 (cli) (built: Apr  2 2019 21:57:22) ( NTS MSVC15 (Visual C++ 2017) x64 )
   Copyright (c) 1997-2018 The PHP Group
   Zend Engine v3.3.4, Copyright (c) 1998-2018 Zend Technologies
   ```

3. MySQL版本:

   ```shell
   C:\MySQL5.7.26\bin\mysql.exe  Ver 14.14 Distrib 5.7.26, for Win64 (x86_64
   ```

4. 服务器软件: Internet Information Services

对于Linux:

1. Linux版本: Debian GNU/Linux 10 (buster) aarch64/arm64 (在Android下运行,基于软件Termux)

2. PHP版本:

   ```shell
   Zend Engine v3.3.29, Copyright (c) 1998-2018 Zend Technologies
   with Zend OPcache v7.3.29-1~deb10u1, Copyright (c) 1999-2018, by Zend Technologies
   ```

3. MariaDB版本:

   ```shell
   mysql  Ver 15.1 Distrib 10.3.29-MariaDB, for debian-linux-gnu (aarch64) using readline 5.2
   ```

4. 服务器软件: Nginx

   版本信息:

   ```shell
   nginx version: nginx/1.14.2
   ```

对于MacOS: 我们还没有MacOS设备!



#### 安装过程:

对于Windows:

1. 搭建PHP与SQL环境.

2. 下载稳定版代码，请运行:

   ```shell
   PS C:\Users\Administrator\Desktop> git clone https://github.com/LittleYang0531/News-Website.git
   ```

3. 配置主要网站，请运行: 

   ```shell
   PS C:\Users\Administrator\Desktop> cd ./News-Website/config/
   ```

   接着在此文件夹内配置服务器信息.

4. 配置API网站，请运行:

   ```shell
   PS C:\Users\Administrator\Desktop\News-Website\config> cd ../api/
   ```

   接着配置文件'config.php'.

5. 在你的服务器软件内配置主网站服务器与API网站服务器.

6. 经过以上配置操作后，返回主目录并运行:

   ```shell
   PS C:\Users\Administrator\Desktop\News-Website\api> cd ../
   PS C:\Users\Administrator\Desktop\News-Website> php check.php
   ```

   该php文件将检查你的配置文件是否配置正确.

7. 在检查完你的配置文件后，请运行:

   ```shell
   PS C:\Users\Administrator\Desktop\News-Website> php install.php
   ```

   该php脚本将帮助你建立该系统所需要的表.

8. 在浏览器中进入你的主网站并此时是否配置正确. 

9. 在浏览器中注册你的管理员账号(此时还不是管理员). 

10. 修改你对该系统的权限，请运行:

    ```shell
    PS C:\Users\Administrator\Desktop\News-Website> mysql -u $$$MySQLAccount$$$ -p
    Enter password:
    Welcome to the MySQL monitor.  Commands end with ; or \g.
    Your MySQL connection id is 47
    Server version: 5.7.26 MySQL Community Server (GPL)
    
    Copyright (c) 2000, 2019, Oracle and/or its affiliates. All rights reserved.
    
    Oracle is a registered trademark of Oracle Corporation and/or its
    affiliates. Other names may be trademarks of their respective
    owners.
    
    Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.
    
    mysql> USE $$$DataBaseName$$$;
    Database changed
    mysql> UPDATE Users SET Authority=1 WHERE UserName='$$$UserName$$$';
    Query OK, 1 row affected (0.00 sec)
    Rows matched: 1  Changed: 1  Warnings: 0
    
    mysql> exit;
    Bye
    PS C:\Users\Administrator\Desktop\News-Website> 
    ```

    接着你将拥有管理员权限，该系统搭建完成.

对于Linux:

1. 搭建PHP与SQL环境.

2. 下载稳定版代码，请运行:

   ```shell
   root@localhost:~# git clone https://github.com/LittleYang0531/News-Website.git
   ```

3. 配置主要网站，请运行:

   ```shell
   root@localhost:~# cd ./News-Website/config/
   ```

   接着在此文件夹内配置服务器信息.

4. 配置API网站，请运行:

   ```shell
   root@localhost:~/News-Website/config# cd ../api/
   ```

   接着配置文件'config.php'.

5. 在你的服务器软件内配置主网站服务器与API网站服务器.

6. 经过以上配置操作后，返回主目录并运行:

   ```shell
   root@localhost:~/News-Website/api# cd ../
   root@localhost:~/News-Website# php check.php
   ```

   该php文件将检查你的配置文件是否配置正确.

7. 在检查完你的配置文件后，请运行:

   ```shell
   root@localhost:~/News-Website# php install.php
   ```

   该php脚本将帮助你建立该系统所需要的表.

8. 在浏览器中进入你的主网站并此时是否配置正确. 

9. 在浏览器中注册你的管理员账号(此时还不是管理员).

10. 修改你对该系统的权限，请运行:

    ```shell
    root@localhost:~/News-Website# mysql -u $$$MySQLAccount$$$ -p
    Enter password:
    Welcome to the MariaDB monitor.  Commands end with ; or \g.
    Your MariaDB connection id is 38
    Server version: 10.3.29-MariaDB-0+deb10u1 Debian 10
    
    Copyright (c) 2000, 2018, Oracle, MariaDB Corporation Ab and others.
    
    Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.
    
    MariaDB [(none)]> USE $$$DataBaseName$$$;
    Database changed
    MariaDB [ycnews]> UPDATE Users SET Authority=1 WHERE UserName='$$$UserName$$$';
    Query OK, 1 row affected (0.007 sec)
    Rows matched: 1  Changed: 1  Warnings: 0
    
    MariaDB [ycnews]> exit;
    Bye
    root@localhost:~/News-Website#
    ```

    接着你将拥有管理员权限，该系统搭建完成.

对于MacOS: 我们还没有MacOS设备!

## 更新

请运行 ./News-Website/update.php

## 开发者支持

Issues: [Issues · LittleYang0531/News-Website (github.com)](https://github.com/LittleYang0531/News-Website/issues)

Wiki: [GitHub · Where software is built](https://github.com/LittleYang0531/News-Website/wiki)

API简介: [https://docs.littleyang.ml/api/index.html](https://docs.littleyang.ml/api/index.html)