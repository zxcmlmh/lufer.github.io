---
title: V2ray+TLS+Websocket搭建指南
date: 2020-02-25 16:40:34
tags: Linux
categories : Linux
---
&emsp;&emsp;参考  [基于v2ray的websocket+tls+web实现安全网络代理](https://www.conum.cn/share/191.html)

# V2ray安装
## 安装V2ray主程序
&emsp;&emsp;一键脚本安装。
```bash
bash <(curl -L -s https://install.direct/go.sh)
```
## 修改配置文件
&emsp;&emsp;使用脚本安装完成后，需要完成配置文件的修改，脚本安装后的配置文件存放位置为`/etc/v2ray/config.json`。具体配置文件如下：

```json
{
    "inbound": {
	"port": 1028,
	"listen":"127.0.0.1", //只监听 127.0.0.1，避免除本机外的机器探测到开放了 1028 端口
	"protocol": "vmess",  //使用vmess协议
	"settings": {
	    "clients": [
	      {
		"id": "b831381d-6324-4d53-ad4f-8cda48b30811", //可在https://www.uuidgenerator.net/生成UUID
		"alterId": 64
	      }
	    ]
	},
    "streamSettings": {
        "network": "ws", //使用websocket协议作为传输协议
	"wsSettings": {
	    "path": "/v2ray" //WebSocket所使用的HTTP协议路径
	    }
        }
    },
    "outbound": {
        "protocol": "freedom",
	"settings": {}
    }
}
```
# 安装Caddy
## 配置域名
&emsp;&emsp;在Caddy启动前，首先需将域名指向服务器，否则会在Caddy启动后由于域名无法访问导致无法申请HTTPS证书，而且在出错后会被CD数小时，导致服务器不能正常使用。
## 安装Caddy
&emsp;&emsp;一键安装脚本。
```bash
curl https://getcaddy.com | bash -s personal
```
## 创建相关目录
&emsp;&emsp;设置Caddy目录。
```bash
mkdir /etc/caddy
touch /etc/caddy/Caddyfile
chown -R root:www-data /etc/caddy
```

&emsp;&emsp;除了配置文件，caddy 会自动生成 ssl 证书，需要一个文件夹放置 ssl 证书。
```bash
mkdir /etc/ssl/caddy
chown -R www-data:root /etc/ssl/caddy
chmod 0770 /etc/ssl/caddy
```
&emsp;&emsp;因为 ssl 文件夹里会放置私钥，所以权限设置成 770 禁止其他用户访问。

&emsp;&emsp;这里需要注意一点的是，因为caddy.service中默认的进程运行用户和用户组为`www-data`，所以日志文件也需要让www-data用户能够有权限读写，当然你也可以选择将日志文件存放在配置文件目录中。
```bash
mkdir /var/log/caddy
touch /var/log/caddy/caddy.log
chown -R root:www-data /var/log/caddy/
chmod 777 /var/log/caddy/caddy.log
```
### 修改systemd配置

&emsp;&emsp;下载systemd配置。
```bash
curl -s https://raw.githubusercontent.com/mholt/caddy/master/dist/init/linux-systemd/caddy.service -o /etc/systemd/system/caddy.service # 从 github 下载 systemd 配置文件 
sudo systemctl daemon-reload # 重新加载 systemd 配置
```

&emsp;&emsp;将`/etc/systemd/system/caddy.service`文件中以下三项配置选项的注释符`#`删除，如下：
```bash
CapabilityBoundingSet=CAP_NET_BIND_SERVICE
AmbientCapabilities=CAP_NET_BIND_SERVICE
NoNewPrivileges=true
```
### 修改CaddyFile
&emsp;&emsp;修改`/etc/caddy/Caddyfile`。

```ini
ray.mydomain.me #你的站点域名
{
  log /var/log/caddy/caddy.log
  tls test@csds.xxx
  proxy /v2ray localhost:1028 { #注意这里需要与v2ray中配置的监听端口及WebSocket所使用的HTTP协议路径一致
    websocket
    header_upstream -Origin
  }
}
```

### 启动各项服务
```bash
systemctl enable caddy.service
systemctl status caddy.service
systemctl start caddy
systemctl status caddy
systemctl start v2ray
systemctl status v2ray
```
# 客户端配置
&emsp;&emsp;地址为域名，端口为443，传输协议选择ws，并填写path，底层传输安全选择tls即可。