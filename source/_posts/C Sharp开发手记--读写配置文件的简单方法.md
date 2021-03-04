---
title: C Sharp开发手记--读写配置文件的简单方法
categories: .NET
date: 2016-05-31 23:03:53
tags: [.NET]
---

&emsp;&emsp;配置文件是程序常用的一种设置存储方式，多为ini格式 系统提供了非常简单的读写函数，分别是:  
&emsp;&emsp;读：GetPrivateProfileString  
&emsp;&emsp;写：WritePrivateProfileString  
&emsp;&emsp;读取用法：

```cs
GetPrivateProfileString(lpApplicationName, lpKeyName, lpDefault,lpReturnedString, nSize, lpFileName)

//lpApplicationName String，欲在其中查找条目的小节名称。这个字串不区分大小写。如设为vbNullString，就在lpReturnedString缓冲区内装载这个ini文件所有小节的列表。
//lpKeyName String，欲获取的项名或条目名。这个字串不区分大小写。如设为vbNullString，就在lpReturnedString缓冲区内装载指定小节所有项的列表
//lpDefault String，指定的条目没有找到时返回的默认值。可设为空（""）
//lpReturnedString String，指定一个字串缓冲区，长度至少为nSize
//nSize，指定装载到lpReturnedString缓冲区的最大字符数量
//lpFileName String，初始化文件的名字。如没有指定一个完整路径名，windows就在Windows目录中查找文件

Example

ini:
[System]
ip=1.1.1.1

Program：
string ip;
GetPrivateProfileString("System","ip","",ip,20,"config.ini");

写入用法

WritePrivateProfileString(lpApplicationName, lpKeyName, lpString, lpFileName)

//lpApplicationName String，欲在其中查找条目的小节名称。这个字串不区分大小写。如设为vbNullString，就在lpReturnedString缓冲区内装载这个ini文件所有小节的列表。
//lpKeyName String，欲获取的项名或条目名。这个字串不区分大小写。如设为vbNullString，就在lpReturnedString缓冲区内装载指定小节所有项的列表
//lpString String,在该位置想要写入的信息
//lpFileName String，初始化文件的名字。如没有指定一个完整路径名，windows就在Windows目录中查找文件

Example

WritePrivateProfileString("System", "ip", "1.1.1.1", "config.ini");
```