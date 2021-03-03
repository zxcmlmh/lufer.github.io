---
title: Django 项目初始化坑
categories: Python
date: 2018-01-19 19:02:48
tags: [Python,后端]
---

1. PIP的安装
```
先安装setup tools
下载：https://pypi.python.org/pypi/setuptools#windows-simplified
解压后运行 python setup.py install
然后安装pip
下载：http://pypi.python.org/pypi/pip#downloads
解压后运行 python setup.py install
```
2. Mysql-Python

&emsp;&emsp;下载exe安装  
&emsp;&emsp;`http://www.codegood.com/archives/129`

3. 递归报错"RuntimeError: maximum recursion depth exceeded in cmp" 

&emsp;&emsp;找到`python\Lib\fuctools.py`将
```python
convert = {  
    '\_\_lt\_\_': \[('\_\_gt\_\_', lambda self, other: other < self),  
               ('\_\_le\_\_', lambda self, other: not other < self),  
               ('\_\_ge\_\_', lambda self, other: not self < other)\],  
    '\_\_le\_\_': \[('\_\_ge\_\_', lambda self, other: other <= self),  
               ('\_\_lt\_\_', lambda self, other: not other <= self),  
               ('\_\_gt\_\_', lambda self, other: not self <= other)\],  
    '\_\_gt\_\_': \[('\_\_lt\_\_', lambda self, other: other > self),  
               ('\_\_ge\_\_', lambda self, other: not other > self),  
               ('\_\_le\_\_', lambda self, other: not self > other)\],  
    '\_\_ge\_\_': \[('\_\_le\_\_', lambda self, other: other >= self),  
               ('\_\_gt\_\_', lambda self, other: not other >= self),  
               ('\_\_lt\_\_', lambda self, other: not self >= other)\]  
}  
```
替换为
```python
convert = {  
    '\_\_lt\_\_': \[('\_\_gt\_\_', lambda self, other: not (self < other or self == other)),  
               ('\_\_le\_\_', lambda self, other: self < other or self == other),  
               ('\_\_ge\_\_', lambda self, other: not self < other)\],  
    '\_\_le\_\_': \[('\_\_ge\_\_', lambda self, other: not self <= other or self == other),  
               ('\_\_lt\_\_', lambda self, other: self <= other and not self == other),  
               ('\_\_gt\_\_', lambda self, other: not self <= other)\],  
    '\_\_gt\_\_': \[('\_\_lt\_\_', lambda self, other: not (self > other or self == other)),  
               ('\_\_ge\_\_', lambda self, other: self > other or self == other),  
               ('\_\_le\_\_', lambda self, other: not self > other)\],  
    '\_\_ge\_\_': \[('\_\_le\_\_', lambda self, other: (not self >= other) or self == other),  
               ('\_\_gt\_\_', lambda self, other: self >= other and not self == other),  
               ('\_\_lt\_\_', lambda self, other: not self >= other)\]  
}
```