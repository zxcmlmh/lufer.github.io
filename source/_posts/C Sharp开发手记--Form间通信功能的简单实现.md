---
title: C Sharp开发手记--Form间通信功能的简单实现
categories: .NET
date: 2016-05-30 22:13:51
tags: [.NET]
---

&emsp;&emsp;Form2，向Form1发送消息 `0x0444`为自定义进程间通信代码。
```Cs
private IntPtr ip = IntPtr.Zero;
[DllImport("user32")]
private static extern bool SendMessage(IntPtr a, int b, int c, string d);
[DllImport("User32.dll", EntryPoint = "FindWindow")]
private extern static IntPtr FindWindow(string lpClassName, string lpWindowName);

ip = FindWindow(null, "Form1");
if (ip == IntPtr.Zero)
{
   MessageBox.Show("Form1未运行。");
   return;
}
else
{
   SendMessage(this.ip, 0x0444, 100, "ssss");
}

  Form1，等待接收消息并进行相应处理 //需要重载DefWndProc方法

protected override void DefWndProc(ref System.Windows.Forms.Message m)
{
  switch (m.Msg)
  {
      case 0x0444://处理消息
         //do something
         break;
      default:
         base.DefWndProc(ref m);//调用基类函数处理非自定义消息。
         break;
   }
}
```