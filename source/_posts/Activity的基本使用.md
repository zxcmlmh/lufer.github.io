---
title: Activity的基本使用
categories: Android
date: 2016-03-30 16:10:38
tags: [Android]
---

1.Activity布局的绑定。

&emsp;&emsp;onCreate()方法中提供了以下函数，用于进行xml文件布局的绑定。

&emsp;&emsp;`setContentView(R.layout.activity_main);`

2.Activity的切换
```
Intent intent2=new Intent();
intent2.setClass(this, Login.class);//方法2
startActivity(intent2);
```
3.Toast
```
Toast toast;
toast = Toast.makeText(MainActivity.this, "显示的信息", Toast.LENGTH_SHORT);
toast.setGravity(Gravity.CENTER, 0, 0);
toast.show();
```