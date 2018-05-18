---
title: Linux SS IPV4+IPV6 配置
url: 469.html
id: 469
categories:
  - Code
date: 2017-05-31 18:59:48
tags:
---

旧金山的小飞机一开始好好地，最近丢包太严重了，感觉小飞机的服务器还是要来回换着用，写一篇文章备用。 换核---已经不想折腾了，换核太蛋疼，换上了合适的核又改不明白启动项，放弃锐速，采用Google BBR  
```
wget –no-check-certificate https://github.com/teddysun/across/raw/master/bbr.sh
chmod +x bbr.sh
./bbr.sh
```
随后安装SS，SS手动配置太麻烦了，推荐的还是一键配置脚本  
```
wget --no-check-certificate http://7xnxy3.com1.z0.glb.clouddn.com/shadowsocks.sh
chmod +x shadowsocks.sh
./shadowsocks.sh 2>&1 | tee shadowsocks.log
```
随后进行IPV6设置 Ubuntu下，编辑/etc/network/interfaces文件 添加以下字段进行IPV6固化设置   
```
iface eth0 inet6 static
        address YOUR\_PUBLIC\_IPV6_ADDRESS
        netmask 64
        gateway YOUR\_PUBLIC\_PIV6_GATEWAY
        autoconf 0
        dns-nameservers 2001:4860:4860::8844 2001:4860:4860::8888 209.244.0.3
```
然后修改ss配置文件，编辑/etc/shadowsocks.json 修改为  
 
>"server":"::"

重启即可  