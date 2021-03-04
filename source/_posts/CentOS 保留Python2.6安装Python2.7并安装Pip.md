---
title: CentOS保留Python2.6安装Python2.7并安装Pip
categories: Python
date: 2017-12-28 00:50:23
tags: [Python]
---

&emsp;&emsp;在保留系统Python2.6的情况下安装2.7，并为2.7的python安装pip。  

## 1. 下载Python-2.7.4.tgz
```
wget http://python.org/ftp/python/2.7.4/Python-2.7.4.tgz
```
## 2. 解压安装
```
tar -xvf Python-2.7.4.tgz
cd Python-2.7.4
./configure --prefix=/usr/local/python2.7
make
make install
```
## 3. 创建软链接来用python27调用python2.7
```
ln -s /usr/local/python2.7/bin/python2.7 /usr/bin/python27
```
## 4. 下载pip
&emsp;&emsp;地址 https://bootstrap.pypa.io/get-pip.py 
## 5. 执行安装命令
```
python27 get-pip.py
```
## 6. 创建软连接
```
ln -s /usr/local/python2.7/bin/pip /usr/bin/pip27
```