---
title: Manjaro安装/配置/常用软件安装与BUG解决
date: 2019-06-25 23:20:36
tags: [Linux]
categories: Linux
---

Manjaro，基于ArchLinux的Linux发行版，中文社区见 https://www.manjaro.cn/

# 安装

Manjaro官方提供了三种版本——XFCE，KDE，GNOME，其实这些版本也就是桌面不同而已，我使用的是KDE版本。官方下载地址（https://manjaro.org/get-manjaro/）

随意下载一款官方ISO即可，随后刻录到U盘

这里刻录时官方推荐使用Rufus的DD模式，我也使用了这款软件，并不知用其他方式会怎样，建议按照官方要求来。

下载[Rufus](https://rufus.ie/)，并选择ISO，U盘，点击开始会提示是否使用DD模式，选DD。

刻录完成后重启，选BootDevice，引导成功则进入初始界面，选择时区，语言等，选择Boot

![](https://s2.ax1x.com/2019/08/07/e4zNfs.png) 

启动后会有一个体验版本的Manjaro，让你在未安装时即可使用Manjaro，体验一番

点击Install，开始安装

![](https://s2.ax1x.com/2019/08/07/e4zapn.png)  

设置键盘

![](https://s2.ax1x.com/2019/08/07/e4zw60.png)

设置分区，这里要注意，如果是从Windows空闲磁盘中划出空间安装，则分区不能超过4个，否则将会无法继续安装。如果少于4个，选择替换分区安装。

如果不要之前的系统，需要抹除磁盘，直接替换C盘是无法识别原有文件的。

随后设置用户名密码，就可以安装了。

最好断网安装，不然可能会卡在90%+的地方！！！

# 初始化配置

安装成功之后进入系统，首先要进行一波初始化的设置

## 更换Pacman的软件源

>sudo pacman-mirrors -i -c China -m rank

会自动检测软件源的延迟，并弹出对话框进行选择。我一开始选了清华的源，但是好像有些问题，建议选华为或者科大的源。

## 添加Archlinuxcn的软件源

修改/etc/pacman.conf,在最后一行添加：

```conf
[archlinuxcn]
SigLevel = Optional TrustedOnly
Server = https://mirrors.ustc.edu.cn/archlinuxcn/$arch
```

两个源都换好之后，直接更新一波应用及系统

>sudo pacman -Syyu

会各种刷屏，提示是否替换一般直接同意，问题不大。

## 安装yay

pacman好像并不能获取Archlinux的源，或者软件不全？反正很多软件我用pacman是找不到的，用yay则可以安装。

>sudo pacman -S yay base-devel

# 常用软件安装与BUG解决

列表: 
[输入法](#输入法)|[Tim&&WeChat](#Tim&&WeChat)|[WPS](#WPS)|[V2ray](#V2ray)|[TeamViewer](#TeamViewer)

## 输入法

### 安装

最重要的是先装输入法，安装搜狗输入法为例

```bash
sudo pacman -S fcitx-sogoupinyin
sudo pacman -S fcitx-im 
sudo pacman -S fcitx-configtool
```

如果想安装谷歌输入法，则把第一句改成

>sudo pacman -S fcitx-googlepinyin

安装完包之后，还需要修改一下配置

打开~/.profile，写入以下几行。如果没有这个文件则创建。
```
export GTK_IM_MODULE=fcitx
export QT_IM_MODULE=fcitx
export XMODIFIERS="@im=fcitx"
```

重启即可使用输入法，如果重启之后没有，手动运行一下fcitx，开始菜单里面直接搜索即可

### 换肤

去网站上下载皮肤，例如: https://www.deepin.org/2011/12/17/fcitx-skins/

直接下载压缩包，解压，把文件夹复制走，直接移动好像是不行的，需要提权

以皮肤LX-Simple7(Black)为例

>sudo cp -r /home/lufer/下载/LX-Simple7(Black)  /usr/share/fcitx/skin/

然后就可以在输入法配置中选择皮肤了

## Tim&&Wechat

Tim可以直接在Octopi中进行搜索，包名为 deepin.com.qq.office,直接安装
Wechat则可以在Octopi中搜索electronic-wechat

此时Tim可能会打不开，如果打不开则需要进行如下设置：

```
安装gnome-settings-daemon，在Octopi中搜索安装即可，安装所有依赖

在桌面设置中，将/usr/lib/gsd-xsettings设置为自动启动
```

此时Tim或Wechat可能会无法输入中文，建议先进行尝试，如果真的无法输入中文再进行修改

修改方法：

```
修改两个文件：
/opt/deepinwine/apps/Deepin-TIM/run.sh
/opt/deepinwine/apps/Deepin-WeChat/run.sh        

在两个文件最前面加入 一下三句话：                        
export XMODIFIERS="@im=fcitx"                        
export GTK_IM_MODULE="fcitx"                        
export QT_IM_MODULE="fcitx"
```

此时Tim必定存在字体发虚的情况，这个问题好像比较复杂，我也没有找到太好的解决方案

先是看了如下一句话

>Manjaro/ArchLinux下QQ和TIM字体发虚：需要安装打了字体清晰化补丁(如infinality/ultimate5)的freetype的lib32位包，挺复杂，建议不折腾。(https://www.lulinux.com/archives/1319)

故考虑安装lib32-infinality-freetype2的包，装完之后发现并不好使，此路不通

方法二：

>故在调整dpi时需要使用环境变量调用deepin的wine(https://www.cnblogs.com/mrway/p/10858234.html)

但是此命令找不到winecfg文件，我在各个deepin里面找了都没找到，这玩意也不行
>env WINEPREFIX="$HOME/.deepinwine/Deepin-TIM" winecfg

这时我看到了这样一篇文章

>Linux操作系统下Wine中文显示不正常的解决方案(https://blog.csdn.net/coderjiang/article/details/30737383)

虽然他是Ubuntu系统，但是他修改注册表的方法启发了我。

打开Tim路径下的注册表，应该是在如下路径，记得替换成自己的用户名

>/home/lufer/.deepinwine/Deepin-TIM/system.reg

```
修改
[System\\CurrentControlSet\\Hardware Profiles\\Current\\Software\\Fonts]
将“LogPixels”=dword:00000060”
改为“LogPixels”=dword:00000070”。

这步操作可以改一下字体大小，随后改一下字体

修改
”[Software\\Microsoft\\Windows NT\\CurrentVersion\\FontSubstitutes] xxxx“项，将其中的”MS Shell Dlg“相关的两项修改成如下内容（即更换字体为宋体）：

“MS Shell Dlg”=”SimSun”
“MS Shell Dlg 2″=”SimSun”

这里我尝试修改为其他字体，但是好像没有，还会被改回去，其默认就是宋体，所以还是用宋体吧

但是这里的问题就是系统不带宋体，所以需要下载一个宋体的ttf文件，然后放到

/home/lufer/.deepinwine/Deepin-TIM/drive_c/windows/Fonts/
/usr/share/fonts/TTF/
```
我不确定哪里有效，所以两边都放一下，这样之后文字会改为宋体，并放大一号，好歹能用了，就是丑一点。

## WPS

由于没有Word，所以安装WPS，直接在Octopi中搜索WPS-office安装即可

### 无法输入汉字

修改/usr/bin下面的三个脚本，把fcitx的启动命令加进去

```
/usr/bin/wps   对应WPS文字
/usr/bin/et    对应WPS表格
/usr/bin/wpp   对应WPS演示

在这三个文件的开头添加

export XMODIFIERS="@im=fcitx"                        
export GTK_IM_MODULE="fcitx"                        
export QT_IM_MODULE="fcitx"
```

### 缺少字体

直接上网下载ttf格式的字体，缺什么下什么，然后用cp命令把这些字体都移动到/usr/share/fonts/TTF/

在使用下面这个命令刷新字体缓存

>fc-cache -fv

## V2ray

直接在Octopi里面搜索V2ray安装，装完之后控制台输入v2ray启动

但是并不能直接启动！！这才是最坑爹的，自动安装会把v2ray的文件扔到/etc/但是他并不能识别

还需要进行如下步骤：

```
修改/etc/v2ray/config.json为正确内容

cp /etc/v2ray/下面的geoip.dat和geosite.dat 到 /usr/bin

最后还需要以指定config的方式启动

sudo v2ray -config /etc/v2ray/config.json 
```

这里补充一句，如果想让终端中的命令走代理
```
设置代理
export http_proxy="socks5://127.0.0.1:1080"
export https_proxy="socks5://127.0.0.1:1080"
取消代理
unset http_proxy
unset https_proxy
```

## TeamViewer

Octopi搜索安装TeamViewer，但是装完会出现没有ID的情况

解决办法：

```
修改DNS，改为114之类的，以防是网络问题

执行命令（可能需要sudo）
teamviewer -daemon start
```

## Chrome

Octopi安装Chrome，但是刚安装如果没有代理插件的话无法设置代理，这时需要以带参数的命令启动Chrome让他在代理环境下运行

>sudo google-chrome --proxy-server=“socks://127.0.0.1:port” --no-sandbox --user-data-dir


其余软件好像没啥坑了，印象不大