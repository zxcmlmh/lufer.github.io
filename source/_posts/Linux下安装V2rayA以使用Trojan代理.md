---
title: Linux下安装V2rayA以使用Trojan代理
date: 2021-03-03 11:30:55
tags: Linux
categories : Linux
---
&emsp;&emsp;最近推行国产化，在用UOS，其前身是Deepin，但是日常访问github时好时坏，所以还得折腾一下代理。

# V2rayA

&emsp;&emsp;`https://github.com/v2rayA/v2rayA`

&emsp;&emsp;v2rayA 是一个支持全局透明代理的 V2Ray Linux 客户端，同时兼容SS、SSR、Trojan、PingTunnel协议。  
&emsp;&emsp;v2rayA 致力于提供最简单的操作，满足绝大部分需求。  
&emsp;&emsp;得益于Web客户端的优势，你不仅可以将其用于本地计算机，还可以轻松地将它部署在路由器或NAS上。


&emsp;&emsp;v2rayA提供了三种使用方法：
* 软件源安装
* docker
* 二进制文件、安装包

&emsp;&emsp;我尝试了第一种，但是在我添加apt库的时候报错了，网上也没搜到解决方案。

```bash
root@uos-PC:/tmp/v2raya# sudo add-apt-repository 'deb http://apt.v2raya.mzz.pub/ v2raya main'
Traceback (most recent call last):
  File "/usr/bin/add-apt-repository", line 95, in <module>
    sp = SoftwareProperties(options=options)
  File "/usr/lib/python3/dist-packages/softwareproperties/SoftwareProperties.py", line 109, in __init__
    self.reload_sourceslist()
  File "/usr/lib/python3/dist-packages/softwareproperties/SoftwareProperties.py", line 599, in reload_sourceslist
    self.distro.get_sources(self.sourceslist)    
  File "/usr/lib/python3/dist-packages/aptsources/distro.py", line 93, in get_sources
    (self.id, self.codename))
aptsources.distro.NoDistroTemplateException: Error: could not find a distribution template for Uos/eagle
```

&emsp;&emsp;所以我干脆直接就用第二种方法装了。

## 安装Docker

&emsp;&emsp;系统里面不带Docker，所以先装下Docker,顺便设置一下开机自动启动。
```bash
sudo apt-get update
sudo apt-get install -y docker.io
systemctl enable docker
```

## 安装V2rayA 
&emsp;&emsp;直接运行下面的命令
```bash
docker run -d \
-p 2017:2017 \
-p 20170-20172:20170-20172 \
--restart=always \
--name v2raya \
-v /etc/v2raya:/etc/v2raya \
mzz2017/v2raya
```

&emsp;&emsp;装好之后直接访问`http://localhost:2017/`就可以了。

&emsp;&emsp;进去设置一下服务器地址，启用代理。

# ProxyChains
&emsp;&emsp;由于很多程序和服务的下载都需要通过 npm, gem, nvm, git等命令进行，而在国内下载速度较差，所以还得为终端的命令启用代理，这里我采用了ProxyChains。

&emsp;&emsp;`https://github.com/haad/proxychains/`

## 安装配置
```bash
sudo apt-get install proxychains
sudo vi /etc/proxychains.conf
```
&emsp;&emsp;然后在最后的ProxyList里加入本地的代理设置，例如V2rayA的默认端口是20170。
``` bash
socks5 127.0.0.1 20170
```
&emsp;&emsp;然后可以测试一下代理是否成功。  
&emsp;&emsp;终端运行`curl -4 ip.sb`，将显示自己的IP。  
&emsp;&emsp;终端运行`proxychains curl -4 ip.sb`，将显示代理服务器的IP。  
&emsp;&emsp;后续使用的命令行需要代理时，只需要在前面加上`proxychains`即可。如`proxychains npm install`。