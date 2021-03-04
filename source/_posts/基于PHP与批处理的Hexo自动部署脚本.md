---
title: 基于PHP与批处理的Hexo自动部署脚本
date: 2018-06-13 16:23:05
categories: PHP
tags: [PHP,Bat]
---
# 创建文件
&emsp;&emsp;建立前端网页，把请求发到PHP后端，然后拼一下内容，操作文件。
```php
//设置一下页面超时时间，不然等全套操作完成就超时了
set_time_limit(100);
//取博文标题
$title=$_POST['title'];
//拼文件名
$filename=$title.".md";
//转码，防止中文文件名乱码
$filename = iconv('UTF-8', 'GB18030', $filename);
$category=$_POST['category'];
$tags=$_POST['tag'];
$content=$_POST['content'];
//按默认格式拼接内容
$filestream="---\n";
$filestream=$filestream."title: ".$title."\n";
//设置时区，确保date函数取到正确的服务器时间
date_default_timezone_set("Asia/Shanghai");
$filestream=$filestream."date: ".date("Y-m-d")." ".date("h:i:s")."\n";
if($category!='')
    $filestream=$filestream."categories: ".$category."\n";
if($tags!='')
    $filestream=$filestream."tags: [".$tags."]\n";
$filestream=$filestream."---\n";
$filestream=$filestream.$content;
//拼接目录，写入文件
$newfile = fopen("./source/_posts/".$filename, "w");
fwrite($newfile, $filestream);
fclose($newfile);
```
# 调用Hexo与Git进行自动部署和备份
&emsp;&emsp;用批处理文件调用Hexo命令，以push.bat为例，PHP中先调用push.bat。
```php
exec("start push.bat")
```
&emsp;&emsp;批处理文件
```bash
::切换当前工作目录到Hexo目录下
set current_dir=D:\htdocs\Blog
::设置环境变量，注意加上Hexo所在路径
set Path=C:\Program Files (x86)\Common Files\Oracle\Java\javapath;C:\Windows\system32;C:\Windows;C:\Windows\System32\Wbem;C:\Windows\System32\WindowsPowerShell\v1.0\;C:\Program Files\QCloud\Monitor\Barad;C:\Program Files\nodejs\;C:\Program Files (x86)\Git\cmd;D:\htdoc\Blog\node_modules\hexo\node_modules\.bin;C:\Users\Administrator\AppData\Roaming\npm;C:\Program Files\Microsoft VS Code\bin
set HOMEPATH=\Users\Administrator
set HOMEDRIVE=C:
set USERNAME=Administrator
set USERPROFILE=C:\Users\Administrator
set windir=C:\Windows
set SystemDrive=C:
set SystemRoot=C:\Windows
set ALLUSERSPROFILE=C:\ProgramData
set APPDATA=C:\Users\Administrator\AppData\Roaming
set CommonProgramFiles=C:\Program Files\Common Files
set CommonProgramFiles(x86)=C:\Program Files (x86)\Common Files
set CommonProgramW6432=C:\Program Files\Common Files
set ComSpec=C:\Windows\system32\cmd.exe
set FP_NO_HOST_CHECK=NO
set JAVA_HOME=C:\Program Files (x86)\Java\jdk1.8.0_171
set LOCALAPPDATA=C:\Users\Administrator\AppData\Local
set NUMBER_OF_PROCESSORS=1
set OS=Windows_NT
set ProgramData=C:\ProgramData
set ProgramFiles=C:\Program Files
set ProgramFiles(x86)=C:\Program Files (x86)
set ProgramW6432=C:\Program Files
set PSModulePath=C:\Windows\system32\WindowsPowerShell\v1.0\Modules\
set PUBLIC=C:\Users\Public
::调用Hexo，把输出保存到deploy.txt 2>&1 代表把异常也输出到该文件
call hexo d -g > deploy.txt 2>&1
调用git，输入list.txt中的命令，进行备份
call "C:\Program Files (x86)\Git\bin\sh.exe" --login -i <list.txt
exit
```
&emsp;&emsp;list.txt命令内容：
```text
git add -A .
git commit -m "backup"
git push origin backup
```
# 坑
## 环境变量
&emsp;&emsp;exec中调用的bat文件，读取不到系统的环境变量，需要在批处理文件中手动设定环境变量。

&emsp;&emsp;打开cmd输入set，会输出所有的环境变量，在bat里面进行set

&emsp;&emsp;主要设定PATH和USERNAME，USERPROFILE等，不然git会找不到config

## Git Config
&emsp;&emsp;exec中调用git，可能会存在读取不到用户config的问题，可以在项目的.git目录下修改config文件，手动指定用户。
```text
[user]
    name=name
    email=email@email.com
```
## Git连接方式
&emsp;&emsp;一定要用SSH，用HTTPS会需要输入密码，然而exec是在后台执行的，根本没有输入密码的机会！！！！
