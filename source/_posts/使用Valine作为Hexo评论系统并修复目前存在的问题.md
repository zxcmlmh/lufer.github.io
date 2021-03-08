---
title: 使用Valine作为Hexo评论系统并修复目前存在的问题
date: 2020-12-21 09:41:27
categories: 前端
tags: [Github,Hexo]
---

# 何为Valine
&emsp;&emsp;既然大家已经使用了Gitpage+Hexo这种搭配，自然是想要在无需架设服务器的情况下完美运行自己的博客，那么如何建设一个评论系统也值得摸索。

&emsp;&emsp;目前常见的博客评论系统有：

* ~~Disqus~~ 被墙
* ~~来必力~~ 无法访问
* Gitment
* Gitalk
* Valine

&emsp;&emsp;Gitment和Gitalk都是基于Github Issue的评论系统，我之前在用的是Gitalk，但是基于Github有两个缺点，一个是每篇文章一定要点进去才可以初始化Issue，另一个是只有用Github账号登录之后才能评论。

&emsp;&emsp;虽说也有无需登录的解决方案，但是一方面其原理还是用自己的账号来发布匿名评论，另一方面需要后端服务器支持，这与无后的初衷相背离。

&emsp;&emsp;所以最后我选了Valine。

&emsp;&emsp;Valine作为一款快速、简洁且高效的~~无后端~~评论系统。其实还是要后端的，不过是架设在Leancloud上的，使用免费版就够用了。

LeanCloud的免费版目前每天强制休眠6小时，但是休眠只影响邮件通知功能，评论功能还可以正常使用。邮件的话在唤醒后自动检查一遍重发即可。

&emsp;&emsp;为了便于管理评论，更是处于想要邮件提醒这个功能，我使用了Valine-Admin（以下简称VA），项目地址如下

>https://github.com/DesertsP/Valine-Admin

# 配置Valine
## 账号注册及配置
&emsp;&emsp;建议注册国际版，目前国内版已经强制备案了，网址：

>https://leancloud.app/

&emsp;&emsp;注册之后创建应用，选择开发版即可。

&emsp;&emsp;随后进入应用，点击`云引擎-设置`，先添加环境变量，按照VA的Github页面说明填写即可。

&emsp;&emsp;配置完以后切换到`云引擎-部署`，部署模式选择`部署项目-Git部署`，分支`master`，手动部署目标环境为`生产环境`在Leancloud云引擎设置界面，填写代码库并保存：
>https://github.com/DesertsP/Valine-Admin.git

&emsp;&emsp;即可一键部署。

&emsp;&emsp;此外最好再绑定独立域名，便于后续访问管理。

# 修复BUG

&emsp;&emsp;在搭建测试过程中，发现了一些比较影响使用的BUG，记录如下。

## 邮件中无法点击链接
&emsp;&emsp;在收到回复邮件的时候，邮件中的蓝色链接部分无法点击。

![](http://pic.lufer.cc/images/2021/03/05/rwPH1J.png)

&emsp;&emsp;原因是在邮件的HTML代码中，herf标签没有带HTTP头，把代码中的链接部分根据实际需要添加HTTP或者HTTPS即可，示例如下：

```html
您在<a style="text-decoration:none;color: #ffffff;" href="${SITE_URL}"> ${SITE_NAME}</a>上的留言有新回复啦！</p></div><div style="margin:40px auto;width:90%">
```
&emsp;&emsp;改为
```html
您在<a style="text-decoration:none;color: #ffffff;" href="http://${SITE_URL}"> ${SITE_NAME}</a>上的留言有新回复啦！</p></div><div style="margin:40px auto;width:90%">
```
## LeanCloud被限流
&emsp;&emsp;LeanCloud的目前由于官方采取限流措施，在休眠之后就无法通过自动任务来唤醒了。

&emsp;&emsp;官方的公告如图

![](http://pic.lufer.cc/images/2021/03/05/rwFDzQ.png)

&emsp;&emsp;在休眠后LeanCloud会在试图唤醒时报错。

![](http://pic.lufer.cc/images/2021/03/05/rwklT0.png)

&emsp;&emsp;这里我采用了Flexiston的方案，链接如下

>https://blog.flesx.cn/posts/25909.html

&emsp;&emsp;主要原理是利用Github Action自动访问管理网页来通过外部唤醒LeanCloud，唤醒之后自动任务就可以继续执行了。

![](http://pic.lufer.cc/images/2021/03/05/rwABuj.png)
