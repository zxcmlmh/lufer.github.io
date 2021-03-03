---
title: Django在服务器部署的几大坑
categories: Python
date: 2017-12-28 00:56:56
tags: [Python,后端]
---

1. 静态资源获取

&emsp;&emsp;在manage.py里面startapp或者createapp创建的应用，在应用目录下创建/static/可能并不会被自动收集，要确保setting.py中的INSTALL APPS里面有你的APP

2. 域名访问限制

&emsp;&emsp;在settings.py中修改ALLOWED_HOSTS = \["服务器IP","localhost","127.0.0.1"\]，否则会无法访问

3. 跨域请求伪造

&emsp;&emsp;Django为了防止跨域请求伪造，会对表单请求进行验证，可能会导致请求无法正确响应，在MIDDLEWARE里面把'django.middleware.csrf.CsrfViewMiddleware'注释掉即可