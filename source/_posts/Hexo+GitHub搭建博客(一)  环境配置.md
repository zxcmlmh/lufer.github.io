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

&emsp;&emsp;首先要理解Hexo与Github，Hexo是一个博客框架，可以把Markdown文法的md文件编译成HTMl页面，随后传到Github上进行展示。

&emsp;&emsp;Hexo作为npm的一个组件，是由npm进行管理的，而npm是Node.js的一个包管理器。

&emsp;&emsp;Github由Git进行管理。

&emsp;&emsp;顾要安装的环境有Git，Node.js，Hexo。

&emsp;&emsp;文本编辑器推荐Visual Studio Code。

## Git安装与配置

&emsp;&emsp;Git官网为 `https://git-scm.com/`。

&emsp;&emsp;打开Git Bash，在开始菜单输入Git Bash即可搜索到，或者到安装目录\bin\下可以找到Git Bash.exe。

&emsp;&emsp;输入以下代码生成SSH Key，注意把邮箱替换为刚刚注册Github时所填邮箱,要保留引号。

```bash
ssh-keygen -t rsa -C "your.email@example.com" -b 4096
```

&emsp;&emsp;会询问保存位置与密码，连输三次回车全部默认。

&emsp;&emsp;打开C:\User\你的用户名\\.ssh\id_rsa.pub文件，复制全部内容。

&emsp;&emsp;打开Github，登录，点击右上角头像旁的小三角->Settings。

&emsp;&emsp;左侧选择SSH and GPG keys，右侧点击New SSH key，Title随便输入，Key部分粘贴刚才文件中的全部内容，点击Add SSH key完成添加，至此完成了Github的全部配置。

## 创建网站项目

&emsp;&emsp;打开Github,点击右侧`New Repository`。

![](http://pic.lufer.cc/images/2021/03/05/e4zAeO.png)

&emsp;&emsp;Repository内部填入与前面Owner部分一样的名字+"github.io",下面选择Public，如下图所示，即完成网站创建。

![](http://pic.lufer.cc/images/2021/03/05/e4zCS1.png)

## 安装Hexo

&emsp;&emsp;安装Node.js，官网为：

&emsp;&emsp;`https://nodejs.org/en/`

&emsp;&emsp;下载LTS版本并安装。

&emsp;&emsp;装好之后,先建立一个文件夹，即作为自己博客项目所在的文件夹，我这里以D:\Test为例。

&emsp;&emsp;打开CMD，输入D:切换到D盘，输入cd test进入Test文件夹。

&emsp;&emsp;输入npm install hexo -g 安装hexo。

&emsp;&emsp;安装完成之后，输入 hexo init 进行Hexo项目初始化。

&emsp;&emsp;看到`INFO Start blogging with Hexo!`说明初始化成功。

&emsp;&emsp;安装Npm相关组件。

```bash
npm install
```

&emsp;&emsp;安装Hexo往Github部署的相关组件。

```bash
npm install hexo-deployer-git --save
```

&emsp;&emsp;打开_config.yml，进行配置。
```ini
# Site
title: 网站标题
subtitle: 网站副标题
description:  网站描述
keywords:  网站关键字（主要用于搜索引擎搜索）
author:  网站作者
language: zh-CN   #代表使用中文
timezone:  时区，不用填

#在最后面修改,"luferl"换成你的Username
deploy:
  type: git
  repo: git@github.com:luferl/luferl.github.io.git
  branch: master

```

&emsp;&emsp;至此已经完成了所有配置，运行如下命令来运行Hexo吧。
```bash
hexo g #进行编译
hexo s #启动服务器
```
&emsp;&emsp;访问localhost:4000即可访问你的Hexo网站。