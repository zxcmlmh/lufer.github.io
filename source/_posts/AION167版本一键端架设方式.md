---
title: AION167版本一键端架设方式
categories: 日常折腾
date: 2016-05-23 13:14:27
tags: [日常折腾]
---

&emsp;&emsp;云盘链接https://yunpan.cn/cSPXUiRhCQhyF （提取码：a168） 

&emsp;&emsp;带167版本客户端与服务端


&emsp;&emsp;局域网联机修改方式：   
&emsp;&emsp;数据库中server表IP地址可以填*也可以填内网IP。   
&emsp;&emsp;GS中的ipconfig如果开外网，就填公网ip，如果局域网就填内网ip。    
&emsp;&emsp;network.properties 中120.0.0.1全部改为内网IP。   
&emsp;&emsp;LS和CS中的properties改内网IP。   
&emsp;&emsp;登录器改成和ipconfig一样即可。   