---
title: Hexo+GitHub搭建博客(一)  环境配置
date: 2018-05-17 20:16:36
categories: 前端
tags: [Github,Hexo]
---


>目录

* [背景说明](#背景说明)
* [Git安装与配置](#git安装与配置)
* [创建网站项目](#创建网站项目)
* [安装Hexo](#安装hexo)

## 背景说明

首先要理解Hexo与Github，Hexo是一个博客框架，可以把Markdown文法的md文件编译成HTMl页面，随后传到Github上进行展示。

Hexo作为npm的一个组件，是由npm进行管理的，而npm是Node.js的一个包管理器。

Github由Git进行管理。

顾要安装的环境有Git，Node.js，Hexo。

文本编辑器推荐Visual Studio Code。

## Git安装与配置

Git官网为：
>https://git-scm.com/

一定要下载32位的版本，64位的版本有BUG，下载地址为：
>https://github.com/git-for-windows/git/releases/download/v2.17.0.windows.1/Git-2.17.0-32-bit.exe

下载安装之后，我们来申请Github
>https://github.com/join?source=header-home

填写Username与Email，这里注意，Username将会作为你的账号昵称，请谨慎选择。Email将会作为生成密钥的参数。

注册成功之后打开Git Bash，在开始菜单输入Git Bash即可搜索到，或者到安装目录\bin\下可以找到Git Bash.exe

输入以下代码生成SSH Key，注意把邮箱替换为刚刚注册Github时所填邮箱,要保留引号。
>ssh-keygen -t rsa -C "your.email@example.com" -b 4096

会询问保存位置与密码，连输三次回车全部默认。

打开C:\User\你的用户名\\.ssh\id_rsa.pub文件，复制全部内容。

打开Github，登录，点击右上角头像旁的小三角->Settings

左侧选择SSH and GPG keys，右侧点击New SSH key，Title随便输入，Key部分粘贴刚才文件中的全部内容，点击Add SSH key完成添加，至此完成了Github的全部配置。

## 创建网站项目

打开Github.com,点击右侧New Repository

![](https://s2.ax1x.com/2019/08/07/e4zAeO.png)

Repository内部填入与前面Owner部分一样的名字+"github.io",下面选择Public，如下图所示，即完成网站创建。

![](https://s2.ax1x.com/2019/08/07/e4zCS1.png)

## 安装Hexo

安装Node.js，官网为
>https://nodejs.org/en/

下载LTS版本并安装

>https://nodejs.org/dist/v8.11.2/node-v8.11.2-x64.msi

装好之后,先建立一个文件夹，即作为自己博客项目所在的文件夹，我这里以D:\Test为例

打开CMD，输入D:切换到D盘，输入cd test进入Test文件夹

输入npm install hexo -g 安装hexo

安装完成之后，输入 hexo init 进行Hexo项目初始化
>看到 <font color=green>INFO</font>  Start blogging with Hexo!  说明初始化成功

安装Npm相关组件
>npm install

安装Hexo往Github部署的相关组件

>npm install hexo-deployer-git --save

打开_config.yml，进行配置
```
# Site
title: 网站标题
subtitle: 网站副标题
description:  网站描述
keywords:  网站关键字（主要用于搜索引擎搜索）
author:  网站作者
language: zh-CN   #代表使用中文
timezone:  时区，不用填

在最后面修改,"luferl"换成你的Username
deploy:
  type: git
  repo: git@github.com:luferl/luferl.github.io.git
  branch: master

```

至此已经完成了所有配置，运行如下命令来运行Hexo吧
>hexo g #进行编译

>hexo s #启动服务器

访问localhost:4000即可访问你的Hexo网站