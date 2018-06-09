---
title: Hexo+VsCode 在IDE中完成博客的一切操作
date: 2018-06-09 14:27:30
categories: Code
tags: [Hexo]
---
# 插件安装
在VSCode的插件库中搜索Hexo，安装VSCode-Hexo，安装完成之后重启VSCode  
{% asset_img 1.png %}  

# 新建文章
>首先需要在博客目录中打开VScode  
{% asset_img vscode.png %} 

按下Ctrl+Shift+P，呼出终端  
{% asset_img 2.png %}  
输入hexo new  
{% asset_img 3.png %}  
输入布局种类，可以选post（文章），page（页面）,draft(草稿)  
{% asset_img 4.png %}   
1. Post
post就是通常的文章，会被自动归档和处理
2. Page
page是一种页面，例如标签页啊，分类页啊，关于作者啊这种页面
3. Draft
草稿不会被加到目录中，也不能通过链接访问，用来临时存放文章或者放一些不公开的文章。

要写文章选Post就好了,然后输入title，即文章标题   
{% asset_img 5.png %}  
回车之后控制台输出  
{% asset_img 6.png %}  
文章生成，可在source/_posts下找到对应的md文件。

# 发布文章
发布有两个步骤，生成和部署。
按下Ctrl+Shift+P，呼出终端，输入Hexo generate  
{% asset_img 7.png %}  
选择参数，无需填写，直接回车  
{% asset_img 8.png %}  
启动生成，控制台输出如下信息  
{% asset_img 9.png %}  
生成成功
{% asset_img 10.png %}  
按下Ctrl+Shift+P，呼出终端，输入Hexo deploy  
{% asset_img 11.png %}  
选择参数，无需填写，直接回车  
{% asset_img 12.png %}  
启动生成，控制台输出如下信息  
{% asset_img 13.png %}  
生成成功  
{% asset_img 14.png %}  