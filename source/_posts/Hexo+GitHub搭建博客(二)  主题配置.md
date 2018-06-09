---
title: Hexo+GitHub搭建博客(二)  主题配置
date: 2018-05-17 21:17:36
categories: Code
tags: [Github,Hexo]
---
以yilia为例

有了自己的网站，怎么能不进行一下个性化呢。

>目录

* [主题安装](#主题安装)
* [主题配置](#主题配置)


## 主题安装

Yilia主题的Github地址为：
>https://github.com/litten/hexo-theme-yilia

安装主题：
>cd Test
</br>
>git clone https://github.com/litten/hexo-theme-yilia.git themes/yilia

启用主题：
>在Test文件夹下的_config.yml中， 把"theme: landscape" 改为"theme: yilia"

这时我们清空一下项目的缓存，重新编译一下
>hexo clear 
</br>
>hexo g

这时我们再hexo s就可以看到启用了新主题的网站啦。

## 主题配置

主题的配置主要修改themes\yilia下的_config.yml，具体修改请参考作者注释。

这个主题有一个显示所有文章的侧边栏，这个侧边栏依靠一个新的组件，我们需要进行安装
>cd test</br>
>npm install hexo-generator-json-content --save

修改Test目录下的_config.yml最后面添加
```
jsonContent:
    meta: false
    pages: false
    posts:
      title: true
      date: true
      path: true
      text: false
      raw: false
      content: false
      slug: false
      updated: false
      comments: false
      link: false
      permalink: false
      excerpt: false
      categories: true
      tags: true
```
至此就完成主题的配置