---
title: Hexo+VsCode 在IDE中完成博客的一切操作
date: 2018-06-09 14:27:30
categories: 前端
tags: [Hexo,前端]
---
# 插件安装
&emsp;&emsp;在VSCode的插件库中搜索Hexo，安装VSCode-Hexo，安装完成之后重启VSCode。  

![](https://pic.lufer.cc/images/2021/03/05/eINKA0.png)

# 新建文章
&emsp;&emsp;首先需要在博客目录中打开VScode。  

![](https://pic.lufer.cc/images/2021/03/05/eINaAx.png)   

&emsp;&emsp;按下`Ctrl+Shift+P`，呼出终端。  

![](https://pic.lufer.cc/images/2021/03/05/eINn7q.png)  

&emsp;&emsp;输入hexo new。  

![](https://pic.lufer.cc/images/2021/03/05/eINmBn.png)   

&emsp;&emsp;输入布局种类，可以选post（文章），page（页面）,draft(草稿)。  

![](https://pic.lufer.cc/images/2021/03/05/eINVXj.png)  

1. Post  
&emsp;&emsp;post就是通常的文章，会被自动归档和处理。
2. Page  
&emsp;&emsp;page是一种页面，例如标签页啊，分类页啊，关于作者啊这种页面。
3. Draft  
&emsp;&emsp;草稿不会被加到目录中，也不能通过链接访问，用来临时存放文章或者放一些不公开的文章。

&emsp;&emsp;要写文章选Post就好了,然后输入title，即文章标题。   

![](https://pic.lufer.cc/images/2021/03/05/eINens.png)  

&emsp;&emsp;回车之后控制台输出  

![](https://pic.lufer.cc/images/2021/03/05/eINMNV.png)   

&emsp;&emsp;文章生成，可在source/_posts下找到对应的md文件。

# 发布文章
&emsp;&emsp;发布有两个步骤，生成和部署。  
&emsp;&emsp;按下Ctrl+Shift+P，呼出终端，输入Hexo generate  

![](https://pic.lufer.cc/images/2021/03/05/eINQhT.png)  

&emsp;&emsp;选择参数，无需填写，直接回车  

![](https://pic.lufer.cc/images/2021/03/05/eIN19U.png) 

&emsp;&emsp;启动生成，控制台输出如下信息  

![](https://pic.lufer.cc/images/2021/03/05/eIN33F.png)   

&emsp;&emsp;生成成功

![](https://pic.lufer.cc/images/2021/03/05/eIN8c4.png) 

&emsp;&emsp;按下Ctrl+Shift+P，呼出终端，输入Hexo deploy  

![](https://pic.lufer.cc/images/2021/03/05/eINGjJ.png)  

&emsp;&emsp;选择参数，无需填写，直接回车  

![](https://pic.lufer.cc/images/2021/03/05/eINYu9.png) 

&emsp;&emsp;启动生成，控制台输出如下信息  

![](https://pic.lufer.cc/images/2021/03/05/eINtBR.png)  

&emsp;&emsp;生成成功  

![](https://pic.lufer.cc/images/2021/03/05/eINNH1.png) 