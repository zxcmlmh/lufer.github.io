---
title: C Sharp开发手记--录音功能的简洁实现
categories: .NET
date: 2016-05-30 22:00:51
tags: [.NET]
---

&emsp;&emsp;系统级的API提供了非常简单的录音功能实现方式，将声音录制为wave格式。
```cs

//调用系统API

\[DllImport("winmm.dll", EntryPoint = "mciSendString", CharSet = CharSet.Auto)\]
public static extern int mciSendString(
string lpstrCommand,
string lpstrReturnString,
int uReturnLength,
int hwndCallback
);

//开始录音

mciSendString("set wave bitpersample 8", "", 0, 0);
mciSendString("set wave samplespersec 20000", "", 0, 0);
mciSendString("set wave channels 2", "", 0, 0);
mciSendString("set wave format tag pcm", "", 0, 0);
mciSendString("open new type WAVEAudio alias movie", "", 0, 0);
mciSendString("record movie", "", 0, 0);

//保存文件

mciSendString("stop movie", "", 0, 0);
mciSendString("save movie D:\\\i.wav", "", 0, 0);
mciSendString("close movie", "", 0, 0);
```