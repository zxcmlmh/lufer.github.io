---
title: Android四大组件--Activity的生命周期
categories: Android
date: 2016-03-30 15:25:03
tags: [Android]
---

 

![](http://hi.csdn.net/attachment/201109/1/0_1314838777He6C.gif)

&emsp;&emsp;一个完整的生命周期从Activity被启动开始，，onCreat()方法被调用，Activity被成功创建，然后调用onStart()方法，开始运行Activity，但是Activity的机制使得Start只是一个中间状态，当onResume()方法被调用时，Activity才会真正运行，屏幕上开始显示Activity的界面。

&emsp;&emsp;当在Activity中点击一些控件，导致跳转到其他的Activity时，由于Activity是以栈的形式存在，遵循先进后出的原则，跳转后之前的Activity不会被销毁，而是调用了onPause()方法进入后台暂停状态。

&emsp;&emsp;这时的Activity进入了分支阶段：

&emsp;&emsp;如果这个Activity不可视，调用onStop()方法先将其停止，进入下一分支

&emsp;&emsp;如果程序运行结束，调用onDestroy()进行销毁，至此完成一个生命周期。

&emsp;&emsp;如果再次切换到此Activity(例如Activity跳转或者新调用的Activity被退出),此时调用onRestart()方法重启此Activity，回到onStart()阶段。

&emsp;&emsp;如果系统内存不足，需要释放内存，同样会将此Activity进行销毁，在需要调用此Activity时重新OnCreat()。

&emsp;&emsp;如果ActivityPause之后又回到了最上面，则直接调用onResume()方法，继续运行Activity。