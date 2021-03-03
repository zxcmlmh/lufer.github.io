---
title: Hexo+GitHub搭建博客(六) 添加评论功能
date: 2018-05-18 16:14:56
tags: [Github,Hexo]
categories: 前端
---

&emsp;&emsp;使用Valine作为评论插件。

# 部署LeanCloud

&emsp;&emsp;点击下方链接注册。

&emsp;&emsp;`https://leancloud.cn/dashboard/login.html#/signup`

&emsp;&emsp;注册后登陆，在控制台点击`创建新应用`，填写应用名称，点击创建。

&emsp;&emsp;点击`云引擎`->`设置`。

&emsp;&emsp;代码库部分填入：

&emsp;&emsp;`https://github.com/panjunwen/Valine-Admin.git`

&emsp;&emsp;Deploy Key不用管，直接点保存。

&emsp;&emsp;点击`云引擎`->`部署`。

&emsp;&emsp;`部署目标`->`生产环境`，分支或版本号填写`master`，点击部署。

&emsp;&emsp;`云引擎`->`设置`->`自定义环境变量`，填写如下环境变量：

|变量名|变量值|变量说明|
|----|----|----|
|SITE_NAME|Lufer|网站名|
|SITE_URL|https://luferl.github.io|网站网址|
|SMTP_HOST|smtp.163.com|smtp服务器，这里以163为例|
|SMTP_PORT|465|ssl链接端口|
|SMTP_USER|Lufercc@163.com|要使用的邮箱|
|SMTP_PASS|blablabla|邮箱的smtp密码|
|SENDER_NAME|Lufer|发件人昵称|
|SENDER_EMAIL|Lufercc@163.com|发件人邮箱号|

&emsp;&emsp;点击保存即可。这里注意，网易邮箱的SMTP密码要在邮箱里单独设置。

&emsp;&emsp;在下方设置Web主机域名，这样就可以通过设置的域名访问后台管理页。

&emsp;&emsp;点击`存储`->`User`。

&emsp;&emsp;点击Username和Password两列，编辑单元格，填入用户名密码，用于登录后台管理页。


&emsp;&emsp;`设置`->`安全中心`->`Web安全域名`，填上你的域名，注意http,https区分填写。

&emsp;&emsp;`设置`->`应用Key`，记下来APPID和App Key，在下一个步骤会用到。

&emsp;&emsp;至此LeanCloud设置完成，点击`云引擎`->`实例`->`小齿轮`点击重启即可。

## 修改Yilia

&emsp;&emsp;`\yilia\_config.yml`,添加以下内容:
```
#6、Valine https://valine.js.org
valine: 
 appid:  #Leancloud应用的appId
 appkey:  #Leancloud应用的appKey
 verify: false #验证码
 notify: false #评论回复提醒
 avatar: '' #评论列表头像样式：''/mm/identicon/monsterid/wavatar/retro/hide
 avatar_cdn: 'https://sdn.geekzu.org/avatar/' #头像CDN
 placeholder: '瞎白话' #评论框占位符
 pageSize: 15 #评论分页
```

&emsp;&emsp;`\yilia\layout\_partial\article.ejs`,添加对valine的支持

&emsp;&emsp;在各种if之间插入以下代码。
```
<% if (theme.valine && theme.valine.appid && theme.valine.appkey){ %>
  <section id="comments" class="comments">
    <style>
      .comments{margin:30px;padding:10px;background:#fff}
      @media screen and (max-width:800px){.comments{margin:auto;padding:10px;background:#fff}}
    </style>
    <%- partial('post/valine', {
      key: post.slug,
      title: post.title,
      url: config.url+url_for(post.path)
      }) %>
  </section>
<% } %>
```
&emsp;&emsp;`在\yilia\layout\_partial\post\下创建valine.ejs`,填入以下内容:
```javascript
<div id="vcomment" class="comment"></div>
<script src="//cdn.jsdelivr.net/npm/jquery@latest/dist/jquery.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/leancloud-storage@latest/dist/av-min.js"></script>
<script src='//cdn.jsdelivr.net/npm/valine@latest/dist/Valine.min.js'></script>
<script>
   var notify = '<%= theme.valine.notify %>' == true ? true : false;
   var verify = '<%= theme.valine.verify %>' == true ? true : false;
   new Valine({
            av: AV,
            el: '#vcomment',
            notify: notify,
            verify: verify,
            app_id: "<%= theme.valine.appid %>",
            app_key: "<%= theme.valine.appkey %>",
            placeholder: "<%= theme.valine.placeholder %>",
            avatar: "<%= theme.valine.avatar %>",
            avatar_cdn: "<%= theme.valine.avatar_cdn %>",
            pageSize: <%= theme.valine.pageSize %>
    });
</script>
```