---
title: Hexo+VsCode 在IDE中完成博客的一切操作
date: 2018-06-09 14:27:30
categories: 前端
tags: [Hexo,前端]
---
# 插件安装
在VSCode的插件库中搜索Hexo，安装VSCode-Hexo，安装完成之后重启VSCode  
![](https://s2.ax1x.com/2019/08/07/eINKA0.png)

# 新建文章
>首先需要在博客目录中打开VScode  
![](https://s2.ax1x.com/2019/08/07/eINaAx.png)   
按下Ctrl+Shift+P，呼出终端  

![](https://s2.ax1x.com/2019/08/07/eINn7q.png)  
输入hexo new  

![](https://s2.ax1x.com/2019/08/07/eINmBn.png)   
输入布局种类，可以选post（文章），page（页面）,draft(草稿)  

![](https://s2.ax1x.com/2019/08/07/eINVXj.png)  
1. Post
post就是通常的文章，会被自动归档和处理
2. Page
page是一种页面，例如标签页啊，分类页啊，关于作者啊这种页面
3. Draft
草稿不会被加到目录中，也不能通过链接访问，用来临时存放文章或者放一些不公开的文章。

要写文章选Post就好了,然后输入title，即文章标题   

![](https://s2.ax1x.com/2019/08/07/eINens.png)  
回车之后控制台输出  

![](https://s2.ax1x.com/2019/08/07/eINMNV.png)   
文章生成，可在source/_posts下找到对应的md文件。

# 发布文章
发布有两个步骤，生成和部署。
按下Ctrl+Shift+P，呼出终端，输入Hexo generate  

![](https://s2.ax1x.com/2019/08/07/eINQhT.png)  
选择参数，无需填写，直接回车  

![](https://s2.ax1x.com/2019/08/07/eIN19U.png) 
启动生成，控制台输出如下信息  

![](https://s2.ax1x.com/2019/08/07/eIN33F.png)   
生成成功

![](https://s2.ax1x.com/2019/08/07/eIN8c4.png) 

按下Ctrl+Shift+P，呼出终端，输入Hexo deploy  

![](https://s2.ax1x.com/2019/08/07/eINGjJ.png)  
选择参数，无需填写，直接回车  

![](https://s2.ax1x.com/2019/08/07/eINYu9.png) 
启动生成，控制台输出如下信息  

![](https://s2.ax1x.com/2019/08/07/eINtBR.png)  
生成成功  

![](https://s2.ax1x.com/2019/08/07/eINNH1.png) 