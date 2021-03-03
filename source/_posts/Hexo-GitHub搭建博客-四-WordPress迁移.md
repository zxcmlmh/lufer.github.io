---
title: Hexo+GitHub搭建博客(四) WordPress迁移
date: 2018-05-18 14:36:57
categories: 前端
tags: [Github,Hexo]
---
## WordPress导出
&emsp;&emsp;`WordPress 仪表盘->工具->导出->所有内容`

&emsp;&emsp;会导出一个xml文件，假设保存在D:\1.xml。

## Hexo导入
```
npm install hexo-migrator-wordpress --save  
hexo migrate wordpress D:\1.xml
```
&emsp;&emsp;大功告成。