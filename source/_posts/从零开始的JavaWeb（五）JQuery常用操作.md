---
title: 从零开始的JavaWeb（五）JQuery常用操作
categories: Java
date: 2018-04-28 22:23:35
tags: [Java,后端]
---

&emsp;&emsp;1、动态添加元素
```java
var know = document.createElement('div'); //创建元素
know.setAttribute("class", "form-group"); //设置class样式
know.innerHTML="知识点

 [删除](#) ";//设置HTML内容
var field = document.getElementById('knowledge'); //2、找到父级元素
field.appendChild(know);//插入
```
&emsp;&emsp;2、动态删除元素
```javascript
//删除按钮设定class为delete，取所有delete元素绑定click事件
$('.delete').click(function(){
	$(this).parent().remove();//从父级元素开始删除
})
```
&emsp;&emsp;3、字符串查找及与ASCII转换

```javascript
//将返回A在字符串answer中的位置，如果没有返回-1
answer.indexOf("A")
//返回ASCII码为65的字符
String.fromCharCode(65)
```

&emsp;&emsp;4、控制元素的显示与隐藏
```javascript
//显示
singlesection[0].style.display="";
//隐藏
multysection[0].style.display="none";
```
&emsp;&emsp;5、控制元素的可用于禁用
```javascript
//禁用
$('#directurl').attr("disabled","disabled");
//启用
$('#directurl').removeAttr("disabled");
```
&emsp;&emsp;6、获取下拉列表选中的值
```javascript
$("#college option:selected").val()
```
&emsp;&emsp;7、正则表达式获取当前页面的参数
```javascript
//quizid作为key
var reg = new RegExp("(^|&)quizid=(\[^&\]*)(&|$)", "i");  
var r = window.location.search.substr(1).match(reg);
//quizid中即为value
var quizid=r\[2\];
```
&emsp;&emsp;8、控制页面跳转
```javascript
//刷新当前页面
location.reload() 
//重定向到某个页面
window.location.href="index.html";
//返回到上一页面，并刷新上个页面
self.location=document.referrer;
```