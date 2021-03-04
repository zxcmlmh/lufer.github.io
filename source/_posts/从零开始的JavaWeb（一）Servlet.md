---
title: 从零开始的JavaWeb（一）Servlet
categories: Java
date: 2018-04-20 23:34:52
tags: [Java,后端]
---

&emsp;&emsp;1、从请求中获取参数
```java
String username=request.getParameter("username");
```
&emsp;&emsp;2、设置返回值的格式 对于Json串
```java
response.setContentType("application/json;charset=utf-8");  
response.setCharacterEncoding("utf-8");
```
&emsp;&emsp;对于纯文本
```java
response.setContentType("application/text;charset=utf-8");  
response.setCharacterEncoding("utf-8");
```
&emsp;&emsp;3、输出返回值
```java
response.getWriter().print("success");
```