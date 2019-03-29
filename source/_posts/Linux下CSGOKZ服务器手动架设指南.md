---
title: Linux下CSGO KZ服务器手动架设指南
date: 2018-06-18 13:58:40
categories: Game
tags:
---
# 前言
最近想搭CSGO KZ服务器，却发现以前的一件安装脚本已经无法下载了，而网上很少能看见Linux版本的KZ服务器假设教程，参考Steam社区文档，进行了一波尝试

>https://steamcommunity.com/sharedfiles/filedetails/?id=855147229

# 前期准备
申请服务器，选择远程工具和FTP工具

# 安装依赖
## Ubuntu
### Ubuntu 64-bit
```
sudo dpkg --add-architecture i386; sudo apt update; sudo apt install mailutils postfix curl wget file bzip2 gzip unzip bsdmainutils python util-linux ca-certificates binutils bc tmux lib32gcc1 libstdc++6 libstdc++6:i386
```
### Ubuntu 32-bit
```
sudo apt install mailutils postfix curl wget file bzip2 gzip unzip bsdmainutils python util-linux ca-certificates binutils bc tmux libstdc++6
```
## Debian
### Debian 64-bit
```
sudo dpkg --add-architecture i386; sudo apt update; sudo apt install mailutils postfix curl wget file bzip2 gzip unzip bsdmainutils python util-linux ca-certificates binutils bc tmux lib32gcc1 libstdc++6 libstdc++6:i386
```
### Debian 32-bit
```
sudo apt install mailutils postfix curl wget file bzip2 gzip unzip bsdmainutils python util-linux ca-certificates binutils bc tmux libstdc++6
```
## Fedora
### Fedora 64-bit
```
dnf install mailx postfix curl wget file bzip2 gzip unzip python binutils bc tmux glibc.i686 libstdc++ libstdc++.i686
```
### Fedora 32-bit
```
dnf install mailx postfix curl wget file bzip2 gzip unzip python binutils bc tmux libstdc++
```
## CentOS
### CentOS 64-bit
```
yum install mailx postfix curl wget bzip2 gzip unzip python binutils bc tmux glibc.i686 libstdc++ libstdc++.i686
```
### CentOS 32-bit
```
yum install mailx postfix curl wget bzip2 gzip unzip python binutils bc tmux libstdc++
```
# 安装CSGO服务器
```
adduser csgoserver
passwd csgoserver    //这里可能因服务器而异，我安装时直接让我手动输入密码
su - csgoserver
wget https://linuxgsm.com/dl/linuxgsm.sh && chmod +x linuxgsm.sh && bash linuxgsm.sh csgoserver
./csgoserver install    //最后这一步非常慢，需要等一会儿
```

# 下载MetaMod&SourceMod

https://www.sourcemm.net/downloads.php?branch=stable

选择Linux保存

http://www.sourcemod.net/downloads.php?branch=stable

选择Linux保存

解压两个压缩包，包含addons,cfg两个文件夹，合并两个文件夹,上传至服务器serverfiles/csgo下

# 下载KzTimer

https://bitbucket.org/kztimerglobalteam/kztimerglobal/downloads/

下载Full Zip,解压后复制到csgo文件夹下

# 服务器配置
## 添加管理员

打开csgo\addons\sourcemod\configs\admins_simple.ini

在末尾添加"SteamID" "Z"

SteamID可以访问steamid.io获取

>"STEAM_0:0:123123123" "Z"
## KzTimer配置
打开同目录下databases.cfg，在最后一个花括号前添加这些内容:
```
"kztimer"
    {
        "driver"            "sqlite"
        "host"                "localhost"
        "database"            "kztimer-sqlite"
        "user"                "root"
        "pass"                ""
    }
```
## Steam令牌设置

前往https://steamcommunity.com/dev/managegameservers

游戏的APPID为730，生成令牌，将令牌复制。

打开csgo\cfg\csgoserver.cfg

在最后添加一行

sv_setsteamaccount  "令牌"

# 服务器命令

1. 启动服务器

./csgoserver start

2. 关闭服务器

./csgoserver stop

3. 重启服务器

./csgoserver restart

4. 查看控制台

./csgoserver console



