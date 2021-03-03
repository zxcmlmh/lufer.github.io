---
title: Linux下CSGO KZ服务器手动架设指南
date: 2018-06-18 13:58:40
categories: Linux
tags: [Linux,Game]
---
# 前言
&emsp;&emsp;最近想搭CSGO KZ服务器，却发现以前的一件安装脚本已经无法下载了，而网上很少能看见Linux版本的KZ服务器假设教程，参考Steam社区文档，进行了一波尝试。

>https://steamcommunity.com/sharedfiles/filedetails/?id=855147229

# 前期准备
&emsp;&emsp;购买服务器，选择远程工具和FTP工具。

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

&emsp;&emsp;https://www.sourcemm.net/downloads.php?branch=stable

&emsp;&emsp;选择Linux保存。

&emsp;&emsp;http://www.sourcemod.net/downloads.php?branch=stable

&emsp;&emsp;选择Linux保存。

&emsp;&emsp;解压两个压缩包，包含addons,cfg两个文件夹，合并两个文件夹,上传至服务器serverfiles/csgo下。

# 下载KzTimer

&emsp;&emsp;https://bitbucket.org/kztimerglobalteam/kztimerglobal/downloads/

&emsp;&emsp;下载Full Zip,解压后复制到csgo文件夹下。

# 服务器配置
## 添加管理员

&emsp;&emsp;打开`csgo\addons\sourcemod\configs\admins_simple.ini`。

&emsp;&emsp;在末尾添加`"SteamID" "Z"`。

&emsp;&emsp;SteamID可以访问`https://steamid.io`获取。

&emsp;&emsp;`"STEAM_0:0:123123123" "Z"`
## KzTimer配置
&emsp;&emsp;打开同目录下databases.cfg，在最后一个花括号前添加这些内容:
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

&emsp;&emsp;前往`https://steamcommunity.com/dev/managegameservers`

&emsp;&emsp;游戏的APPID为730，生成令牌，将令牌复制。

&emsp;&emsp;打开`csgo\cfg\csgoserver.cfg`

&emsp;&emsp;在最后添加一行：

&emsp;&emsp;`sv_setsteamaccount  "令牌"`

# 服务器命令

1. 启动服务器

&emsp;&emsp;`./csgoserver start`

2. 关闭服务器

&emsp;&emsp;`./csgoserver stop`

3. 重启服务器

&emsp;&emsp;`./csgoserver restart`

4. 查看控制台

&emsp;&emsp;`./csgoserver console`



