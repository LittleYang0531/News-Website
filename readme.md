# News-Website
![](https://img.shields.io/github/stars/LittleYang0531/News-Website.svg?style=social) ![](https://img.shields.io/github/forks/LittleYang0531/News-Website.svg?style=social) 

English  [中文简体](./readme-zh_cn.md)

## Attention
1. This repository will not to update until June 14th, 2021 because of the coming high school entrance examination of author.(2021.06.01)
2. This repository isn't finish now. So if you build this system now, you will meet many issues and some of the features can't be used!(2021.07.23)

## Introduce
⚠Warning: The 'Readme.md' may have some grammar error because my poor English skill. Please ignore them.

News-Website is a online news system. Anyone can upload their articles to the server. And users can learn more about recent news.

This is my first web application. It has the most flexible configurations and the most comprehensive Features.

This repository is built by myself, it may have many issues. If you find any, please report it on [Issues · LittleYang0531/News-Website (github.com)](https://github.com/LittleYang0531/News-Website/issues)

If you want to make a contribution to this repository. You can clone it to your local machine and make some changes. After that, You can pull request to this repository. And your header will be shown under the 'contribution' dialog.

## Features

Base Features:

- [x] News Classifying
- [x] Article Requirement
- [x] Comment Area
- [x] Notice Display
- [x] History Saving&Watching
- [x] Account System
- [x] Message Administration
- [x] Article Staring&Administration
- [x] Email System
- [x] Article Uploading
- [x] Article Searching
- [ ] Article Reporting

Extensive Features:

- [x] Wiki System
- [ ] Social System
- [ ] Document System
- [x] File Explorer
- [x] Picture Storage Service
- [ ] Video Player
- [ ] Music Player
- [ ] Other Features......

Application Programming Interface:

- [x] Article Service
- [x] Account Operation Service
- [x] Account Query Service
- [x] Column Service
- [x] Comment Service
- [ ] Document Service
- [x] History Service
- [x] Login Service
- [x] Message Service
- [ ] Music Service
- [x] Notice Service
- [x] Search Service
- [ ] Report Service
- [ ] Social System Service
- [x] Wiki Service
- [ ] Video Service

## Build

### Environment Requirement

Operator System Requirement: Windows, Linux, MacOS (We didn't test on MacOS)

PHP Configuration: At least 7.x

Server Application Configuration: Nginx, Apache, IIS... (Any Application which can support PHP)

Database Configuration: MySQL 5.x or MariaDB 10.x

⚠Warning: The configuration above is the environment that we are sure it can be built normally. We haven't know the lowest configuration yet.

### Manual Installation Process

#### Installation Example Environment:

For Windows:

1. Windows Version: Microsoft Windows 11 Professional 10.0.22000 Version 22000 x64

2. PHP Version:

   ```shell
   PHP 7.3.4 (cli) (built: Apr  2 2019 21:57:22) ( NTS MSVC15 (Visual C++ 2017) x64 )
   Copyright (c) 1997-2018 The PHP Group
   Zend Engine v3.3.4, Copyright (c) 1998-2018 Zend Technologies
   ```

3. MySQL Version:

   ```shell
   C:\MySQL5.7.26\bin\mysql.exe  Ver 14.14 Distrib 5.7.26, for Win64 (x86_64
   ```

4. Server Application: Internet Information Services

For Linux:

1. Linux Version: Debian GNU/Linux 10 (buster) aarch64/arm64 (running under Termux)

2. PHP Version:

   ```shell
   Zend Engine v3.3.29, Copyright (c) 1998-2018 Zend Technologies
   with Zend OPcache v7.3.29-1~deb10u1, Copyright (c) 1999-2018, by Zend Technologies
   ```

3. MariaDB Version:

   ```shell
   mysql  Ver 15.1 Distrib 10.3.29-MariaDB, for debian-linux-gnu (aarch64) using readline 5.2
   ```

4. Server Application: Nginx

   Version Information:

   ```shell
   nginx version: nginx/1.14.2
   ```

For MacOS: We didn't have MacOS device!



#### Installation Process:

For Windows:

1. Built PHP and SQL environment.

2. Install the stable source code. Run:

   ```shell
   PS C:\Users\Administrator\Desktop> git clone https://github.com/LittleYang0531/News-Website.git
   ```

3. Configurate main website. Run: 

   ```shell
   PS C:\Users\Administrator\Desktop> cd ./News-Website/config/
   ```

   And then configurate some information under this folder.

4. Configurate application programming interface service website. Run:

   ```shell
   PS C:\Users\Administrator\Desktop\News-Website\config> cd ../api/
   ```

   And configurate the file 'config.php'.

5. Configurate your main website server and application programming interface server in the server application.

6. After those configuration operators, return the main folder and run:

   ```shell
   PS C:\Users\Administrator\Desktop\News-Website\api> cd ../
   PS C:\Users\Administrator\Desktop\News-Website> php check.php
   ```

   This php script will check all your configurations.

7. After checking your configuration, please run:

   ```shell
   PS C:\Users\Administrator\Desktop\News-Website> php install.php
   ```

   This php script will help you create tables in the database.

8. Enter your main website in the internet explorer to test whether your website. 

9. Register your administrator account in the internet explorer. 

10. Change your premission of the administrator account, please run:

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

    Then you will have administrator permission.

For Linux:

1. Built PHP and SQL environment.

2. Install the stable source code. Run:

   ```shell
   root@localhost:~# git clone https://github.com/LittleYang0531/News-Website.git
   ```

3. Configurate main website. Run:

   ```shell
   root@localhost:~# cd ./News-Website/config/
   ```

   And then configurate some information under this folder.

4. Configurate application programming interface service website. Run:

   ```shell
   root@localhost:~/News-Website/config# cd ../api/
   ```

   And configurate the file 'config.php'.

5. Configurate your main website server and application programming interface server in the server application.

6. After those configuration operators, return the main folder and run:

   ```shell
   root@localhost:~/News-Website/api# cd ../
   root@localhost:~/News-Website# php check.php
   ```

   This php script will check all your configurations.

7. After checking your configuration, please run:

   ```shell
   root@localhost:~/News-Website# php install.php
   ```

   This php script will help you create tables in the database.

8. Enter your main website in the internet explorer to test whether your website. 

9. Register your administrator account in the internet explorer.

10. Change your premission of the administrator account, please run:

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

    Then you will have administrator permission.

For MacOS: We didn't have MacOS device!

## Update

Run ./News-Website/update.php

## Developer Support

Issues: [Issues · LittleYang0531/News-Website (github.com)](https://github.com/LittleYang0531/News-Website/issues)

Wiki: [GitHub · Where software is built](https://github.com/LittleYang0531/News-Website/wiki)

API Introduce: [https://docs.littleyang.ml/api/index.html](https://docs.littleyang.ml/api/index.html)