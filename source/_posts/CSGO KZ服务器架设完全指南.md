---
title: CSGO KZ服务器架设完全指南
categories: Linux
date: 2016-09-01 13:59:17
tags: [Linux,Game]
---

推荐使用Linux，无脑一键，使用Windows会遇到KZTimer插件无法启用等问题 

一、创建KZ服务器

打开putty登陆服务器 ![](http://bbs.93x.net/data/attachment/forum/201507/04/053512tlmj8l77pxqvl33f.jpg)

登陆服务器 login as :  登陆用户名 root 应该是一样的 云服务器Linux默认激活了 root 因此我们都可以使用root password : 你设置的密码 【注意你输入密码不会显示 只需要默默输入，区分大小写 然后按回车即可】 ![](http://bbs.93x.net/data/attachment/forum/201507/04/053557exmqjmqadxtdtmw5.jpg) 成功登陆 ![](http://bbs.93x.net/data/attachment/forum/201507/04/053856r7gf91zpfgz2fnu2.jpg)

创建KZ服务器

输入
```
cd /home
wget http://down.wc38.com/plugins/csgorunkz.tgz
tar xvf csgorunkz.tgz
chmod -R 777 *
./installkz.sh
```
等待出现下图最后一行 ![](http://bbs.93x.net/data/attachment/forum/201507/04/060519txkyika6wjrmgug8.jpg)

二、调整KZ服务器  

1. 为服务器添加steam token密钥，否则只能允许局域网连接

    账号需要已经绑定手机号 打开 服务器管理页 [https://steamcommunity.com/dev/managegameservers](https://steamcommunity.com/dev/managegameservers) （创建APPID地址） ![](http://bbs.93x.net/data/attachment/forum/201511/21/154935lsooozp9x5xp77gp.jpg)

    填写 730 （CSGO服务器端） ，下面是备注可选 然后点击create创建 如果失败 说明steam 账号不满足条件

    之后打开服务器中的文件夹 csgo/cfg 下的 **server.cfg 文件**

    尾部增加一行sv_setsteamaccount   "XXXX" xxx 为 token密钥

2. 为服务器设置OP
    ```
    home/csgoserver/csgo/addons/sourcemod/configs/admins_simple.ini 
    设置方法： 
    在尾部加上 前面为STEAM32ID 游戏内输入status得到  z代表最高权限！ 1行1个 
    如: "STEAM_0:1:55075206" "z" "STEAM_0:1:55075207" "z"
    ```
3. 为服务器添加地图

    首先将地图上传至服务器的maps文件夹下

    然后修改

    csgo\mapcycle.txt

    csgo\kzmaplist.txt

    将地图名字添加进去

    重启服务器即可

三、服务器维护

1. 开启服务器

>cd /home  
./start.sh

2. 关闭服务器

>screen -list//找到服务器进程ID(例如1234)  
kill 1234

3. 重启服务器

>screen -r 1234

四、设立下载服务器

以IIS服务器为例

先新建一个文件夹，例如CSGO

新建maps文件夹

将地图.bsp,.nav放在maps下面（这里可以对这两个文件分别进行bz2压缩，可以用7zip，压缩为.bsp.bz2放在maps下面也可）

建立IIS网站 ![](http://bbs.93x.net/data/attachment/forum/201604/11/130205d44z29sys4ydptko.jpg)  设置允许任何文件格式被下载 ![](http://bbs.93x.net/data/attachment/forum/201604/11/130515zdhgs1xidizdknsv.jpg) ![](http://bbs.93x.net/data/attachment/forum/201604/11/130515xkk6w6z2k3pz6abk.jpg)

用浏览器访问测试，成功即可

![](http://bbs.93x.net/data/attachment/forum/201604/11/130613ae4o4bjds7siv7vt.jpg)

设置CSGOserver

在csgo\cfg\server.cfg中加入

sv_allowdownload 1
sv_downloadurl "http://60.191.143.241:760"     //你的下载服务器地址

CSGO客户端便可自动从下载服务器下载地图