---
title: Melody主题个性化修改memo
date: 2020-12-28 17:00:37
categories: 前端
tags: [Github,Hexo]
---

## 1. 修改为Google Analytics V4
&emsp;&emsp;Google Analytics已更新到4.0版本，页面引用方式有变化，在Melody主题的dev分支下作者已经更新了代码，master分支需要手动修改。

&emsp;&emsp;文件位置`\hexo-theme-melody\layout\includes\head.pug`

&emsp;&emsp;修改代码
```js
if theme.google_analytics
  script(src="https://www.googletagmanager.com/gtag/js?id="+theme.google_analytics)
  script.
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', '!{theme.google_analytics}');

```

## 2.修改行内引用代码块颜色
&emsp;&emsp;主题配置文件的颜色选项不能修改行内代码块的颜色，原颜色太淡。

&emsp;&emsp;文件位置`\hexo-theme-melody\source\css\var.styl`

&emsp;&emsp;修改代码
```css
$code-background = rgba(101,196,235,0.2)
```

&emsp;&emsp;本文所做修改已打包至`hexo-theme-melody-lufer`，可通过`npm install hexo-theme-melody-lufer`来进行安装。

&emsp;&emsp;使用此包注意要修改`_config.yml`中的`theme`为`theme: melody-lufer`。  
&emsp;&emsp;把主题配置文件名由`_config.melody.yml`改为`_config.melody-lufer.yml`