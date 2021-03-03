---
title: Linux安装Nodejs和Hexo
date: 2021-03-02 10:54:13
categories: Linux
tags: [Linux]
---

# 安装NodeJS
## 下载压缩包
&emsp;&emsp;去官网直接下载对应的压缩包。

&emsp;&emsp;`https://nodejs.org/en/`

![官网下载压缩包](https://www.853tv.cn/imgs/2021/03/0258f4818f17e9e9.png)

## 解压缩
&emsp;&emsp;下载之后，解压缩，改一下目录名。
```bash
sudo -i
tar xf node-v14.16.0-linux-arm64.tar.xz -C /usr/local/
cd /usr/local/
mv node-v14.16.0-linux-arm64/ nodejs
```
### 建立软连接
```bash
ln -s /usr/local/nodejs/bin/node /usr/local/bin/
ln -s /usr/local/nodejs/bin/npm /usr/local/bin/
```
# 安装Hexo
## npm安装Hexo
&emsp;&emsp;先换源，再安装，以免装的太慢。
```bash
npm config set registry https://registry.npm.taobao.org/
npm install hexo-cli -g
```
## 建立软连接
```bash
ln -s /usr/local/nodejs/bin/hexo /usr/local/bin/
```

