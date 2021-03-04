---
title: 从零开始的JavaWeb（三）通过Filter实现登录验证
categories: Java
date: 2018-04-20 23:55:03
tags: [Java,后端]
---

&emsp;&emsp;通过Filter验证缓存的session，从而验证当前用户的登录状态。 web.xml中对Filter进行注册。
```java
  //Filter名字与Class文件映射
    loginFilter
    pcadmin.LoginFilter 
  //Filter名字与所管辖的网址进行映射
    loginFilter
    * 
```
&emsp;&emsp;写入session可通过如下操作进行。
```java
//Sevlet中的request是HTTPRequest，直接可以getSession()
HttpSession session=request.getSession();
//设置session的Key,Value
session.setAttribute("Username", username);
```
&emsp;&emsp;Filter代码。
```java
//Filter中的是SevletRequest，需要先转换为HttpServletRequest才有getSession方法
HttpServletRequest httpRequest=(HttpServletRequest)request;
HttpServletResponse httpResponse=(HttpServletResponse)response;
HttpSession session=httpRequest.getSession();
//获取当前想要请求的url，getRequestURI可以删掉ip域名，仅获取后面的网址，用getRequestURL可获取全部网址
String a=httpRequest.getRequestURI();
//仅拦截html网页请求，这样可以避免拦截js和css等资源文件
if(a.contains(".html"))
{
	//session不为空，或者本身就要访问login页面，则按原请求放行
	if(session.getAttribute("Username")!=null||a.contains("login.html"))
	{
		chain.doFilter(request, response);
	}
	else
	//将客户端重定向到login页面
	{
		 httpResponse.sendRedirect(httpRequest.getContextPath()+"/pc/login.html");
	}
}
else
//其他请求放行
	chain.doFilter(request, response);
```