---
title: 从零开始的JavaWeb（一）Servlet
url: 623.html
id: 623
categories:
  - Java
date: 2018-04-20 23:34:52
tags: [JavaWeb]
---

1、从请求中获取参数

>String username=request.getParameter("username");

2、设置返回值的格式 对于Json串

>response.setContentType("application/json;charset=utf-8");  
response.setCharacterEncoding("utf-8");

对于纯文本

>response.setContentType("application/text;charset=utf-8");  
response.setCharacterEncoding("utf-8");

3、输出返回值

>response.getWriter().print("success");