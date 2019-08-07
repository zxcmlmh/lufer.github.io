---
title: NextCloud私有云搭建指南
date: 2019-05-07 00:39:48
tags:
---

# 目录：
* [前期准备](#前期准备)
* [NextCloud安装](#NextCloud安装)
    * [NextCloud一键安装脚本](#NextCloud一键安装脚本)
    * [NextCloud初始配置](#NextCloud初始配置)
* [离线下载实现](#离线下载实现)
    * [Aria2安装与配置](#Aria2安装与配置)
    * [安装OCDownloader](#安装OCDownloader)

# 前期准备

一个可以直连，网速不错的VPS，建议使用Ubuntu16.04以上

# NextCloud安装

## NextCloud一键安装脚本

>sudo apt-get update  
sudo apt install snapd  
sudo snap install nextcloud

等待安装完成，即可通过IP访问NextCloud

## NextCloud初始配置

第一次登陆网站会提示创建管理员账号密码，设置成功后即完成初步设置

![e4zjjP.png](https://s2.ax1x.com/2019/08/07/e4zjjP.png)

# 离线下载实现

## Aria2安装与配置

### 安装Aria2

Aria2一键安装脚本
>wget -N --no-check-certificate https://raw.githubusercontent.com/ToyoDAdoubi/doubi/master/aria2.sh && chmod +x aria2.sh && bash aria2.sh

运行脚本后，你可以安装、升级Aria2。

![e4zXct.png](https://s2.ax1x.com/2019/08/07/e4zXct.png) 

### 配置Aria2

为了方便后续使用，需要进行一些配置

>vi /root/.aria2/aria2.conf

对aria2配置文件进行修改

要把rpc-secret注释掉，并修改做种条件
```conf
# 设置的RPC授权令牌, v1.18.4新增功能, 取代 --rpc-user 和 --rpc-passwd 选项
#rpc-secret=
# 设置的RPC访问用户名, 此选项新版已废弃, 建议改用 --rpc-secret 选项

......

# 当种子的分享率达到这个数时, 自动停止做种, 0为一直做种, 默认:1.0
seed-ratio=0.1
seed-time=0

```
## 安装OCDownloader

点击右上角-应用

搜索ocDownloader-下载并启用

随后便可使用ocDownloader进行离线下载

![e4zhX6.png](https://s2.ax1x.com/2019/08/07/e4zhX6.png) 