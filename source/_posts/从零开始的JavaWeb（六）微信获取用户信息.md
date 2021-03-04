---
title: 从零开始的JavaWeb（六）微信获取用户信息
categories: Java
date: 2018-04-28 22:28:53
tags: [Java,后端]
---

&emsp;&emsp;1、构造LoginService类，作为授权的起始页面，引导用户访问该servlet。
```java
protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		//构造回调页面链接，Request.getServerName()来获取当前域名
		String backUrl="http://"+request.getServerName()+"/SafeCampus/wechat/CallBack";
                //使用公众号的APPID
		String AppID="TESTAPPID";
                //构造发起请求授权的URL
		String reurl ="https://open.weixin.qq.com/connect/oauth2/authorize?appid="+AppID
		                \+ "&redirect_uri="+URLEncoder.encode(backUrl)   //这里要把回调页面的URL进行Encode
		                \+ "&response_type=code"
		                \+ "&scope=snsapi_userinfo"
		                \+ "&state=STATE#wechat_redirect";
                //跳转授权页面，打开授权引导页
		response.sendRedirect(reurl);	        
	}
```
&emsp;&emsp;2、如果用户同意授权，将会跳转至定义的回调页面，并附带参数Code。
```java
String code=request.getParameter("code");
String access_token = ""; 
String line="";
String openid = "";  
//使用公众号的APPID
String AppID="TESTAPPID";
//使用公众号的AppSecret
String AppSecret="TESTAPPSECRET";
//构造获取access_token的链接
String reurl ="https://api.weixin.qq.com/sns/oauth2/access\_token?appid="+AppID+"&secret="+AppSecret+"&code="+code+"&grant\_type=authorization_code";
//创建url连接
URL url = new URL(reurl);  
//打开连接 
HttpURLConnection urlConnection = (HttpURLConnection) url.openConnection(); 
urlConnection.setDoOutput(true);  
urlConnection.setDoInput(true);  
urlConnection.setRequestMethod("GET");  
urlConnection.setUseCaches(false);  
urlConnection.connect();  
BufferedReader reader = new BufferedReader(new InputStreamReader(urlConnection.getInputStream(), "utf-8"));  
//存储服务器返回的信息
StringBuffer buffer = new StringBuffer(); 
//读取返回值保存到buffer
while ((line = reader.readLine()) != null) {  
	buffer.append(line);  
} 
//断开连接
urlConnection.disconnect();
//buffer转string
String result = buffer.toString();  
//String转Obj
JSONObject resultObject = JSONObject.fromObject(result);
//获取用户的openid 
openid = resultObject.getString("openid");
//获取返回的access_token
access\_token=resultObject.getString("access\_token");
//构建获取用户详细信息的链接，使用刚获得的openid和access_token
reurl="https://api.weixin.qq.com/sns/userinfo?access\_token="+access\_token+"&openid="+openid+"&lang=zh_CN";
url = new URL(reurl); 
urlConnection = (HttpURLConnection) url.openConnection();
urlConnection.setDoOutput(true);  
urlConnection.setDoInput(true);  
urlConnection.setRequestMethod("GET");  
urlConnection.setUseCaches(false);  
urlConnection.connect();  
reader = new BufferedReader(new InputStreamReader(urlConnection.getInputStream(), "utf-8"));  
buffer = new StringBuffer(); 
line="";
while ((line = reader.readLine()) != null) {  
	buffer.append(line);  
} 
urlConnection.disconnect();
result = buffer.toString();  
resultObject = JSONObject.fromObject(result);
//获得用户昵称
String nickname = resultObject.getString("nickname"); 
//其它最终返回参数如下
"nickname": "nickname",
"sex": 1,
"language": "zh_CN",
"city": "city",
"province": "province",
"country": "中国",
"headimgurl": "http://wx.qlogo.cn/mmopen/JcDicrZBlREhnNXZRudod9PmibRkIs5K2f1tUQ7lFjC63pYHaXGxNDgMzjGDEuvzYZbFOqtUXaxSdoZG6iane5ko9H30krIbzGv/0",
"subscribe_time": 1386160805
```