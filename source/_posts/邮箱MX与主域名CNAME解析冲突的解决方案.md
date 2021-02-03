---
title: 邮箱MX与主域名CNAME解析冲突的解决方案
date: 2021-02-01 17:43:43
tags: [DNS,日常折腾]
categories: 日常折腾
---

# 起因
&emsp;&emsp;因为我启用了@lufer.cc的域名邮箱，所以DNS解析记录要添加一个值为@的MX记录，但这样就会与我想解析的@记录产生冲突，导致`lufer.cc`域名无法正常访问。

# 解决

## 解决方案
&emsp;&emsp;解决方案有两种，一种是把@记录进行301转发，另一种是使用一些DNS服务商提供的解决方案。我采用了第一种，第二种对不同的DNS服务商而言不太一样，例如Cloudflare是CNAME Flattening，对于cloudxns是link记录。

## DNS提供商选择
&emsp;&emsp;这里首先有一个前提，就是国内的DNS解析服务商在网站未备案的时候是不提供301转发服务的，我试过了阿里云，DNSpod，H3DNS均不行。而我在github的CNAME写的是`coder.lufer.cc`，我需要把www的解析也转发到这个域名，所以实际上我`www.lufer.cc`也是无法访问的。

&emsp;&emsp;所以我最后选择了Cloudflare。

## 实施（使用Cloudflare）
### 注册登录
&emsp;&emsp;不多说了，注册选免费方案，添加自己的域名。
### 转移DNS
&emsp;&emsp;因为我域名在阿里云，要在阿里云把DNS解析转移到Cloudflare。

&emsp;&emsp;先在阿里云的DNS修改处改成Cloudflare提供的DNS

[![Cloudflare的DNS](https://s3.ax1x.com/2021/02/02/ym2irV.png)](https://imgchr.com/i/ym2irV)

[![把阿里云修改成对应的DNS](https://s3.ax1x.com/2021/02/02/ymgKHS.png)](https://imgchr.com/i/ymgKHS)

&emsp;&emsp;DNS解析服务器修改之后可能需要一段时间才能同步，但不影响进行后续步骤。

### 添加解析

&emsp;&emsp;在Cloudflare按照之前的需求添加DNS解析。

[![DNS解析记录](https://s3.ax1x.com/2021/02/02/ymRKYQ.png)](https://imgchr.com/i/ymRKYQ)

### 设置301转发

&emsp;&emsp;在`页面规则`下面添加两条301转发，如下图所示。

[![添加页面转发](https://s3.ax1x.com/2021/02/02/ymRtTU.png)](https://imgchr.com/i/ymRtTU)

&emsp;&emsp;页面规则的具体添加方式如下图所示，注意上下两个域名最后分别要添加`/*`和`$1`。

[![页面规则编辑](https://s3.ax1x.com/2021/02/02/ymfMMn.png)](https://imgchr.com/i/ymfMMn)

&emsp;&emsp;在设置转发之后，还要为设置了转发的域名添加A记录，才能进行解析，地址可以随便写,因为实际上进行了转发，不会解析到该IP。

[![域名对应A记录](https://s3.ax1x.com/2021/02/02/ymhhp4.png)](https://imgchr.com/i/ymhhp4)

### 设置SSL

&emsp;&emsp;在转移到Cloudflare之后，我发现我连`coder.lufer.cc`都无法访问了，具体出错信息是`“网站将您重定向次数过多”`。

&emsp;&emsp;解决方法是在`SSL/TLS`的菜单页中，默认的加密方式为`完全`，我们将它更改为`完全（严格）`即可。

[![SSL/TLS设置](https://s3.ax1x.com/2021/02/02/ym4wE6.png)](https://imgchr.com/i/ym4wE6)

# 结语

&emsp;&emsp;至此完成了域名解析的转移和301转发的设置，域名均可正常访问，邮箱也可正常收发邮件。  
&emsp;&emsp;本来想把整个域名都转走的，奈何阿里云的域名太便宜了。