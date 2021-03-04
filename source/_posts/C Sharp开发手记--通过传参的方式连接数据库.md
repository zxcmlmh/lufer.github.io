---
title: C Sharp开发手记--通过传参的方式连接数据库
categories: .NET
date: 2016-05-31 22:51:46
tags: [.NET]
---

&emsp;&emsp;通过传参的方式与数据库进行连接，可以很好的解决字符串`过长`，字符串中`包含或`等问题
```cs
//构建SQL连接
SqlConnection myconnection = new SqlConnection("//ur sql connection string here");
myconnection.Open();

//建立参数方式的SQL语句
String sqlcommand = "insert into TABLE_NAME values(@id,@name.//@somethingelse";
SqlCommand mycommand = new SqlCommand(sqlcommand, myconnection);

//设定参数格式
mycommand.Parameters.Add(new SqlParameter("@id", System.Data.SqlDbType.Int, 0));
mycommand.Parameters.Add(new SqlParameter("@name", System.Data.SqlDbType.VarChar, 20));
//mycommand.Parameters.Add(new SqlParameter("@somethingelse", OtherType, Length));


//向参数中填值
 mycommand.Parameters\["@id"\].Value = _id;
mycommand.Parameters\["@name"\].Value = _name;
//mycommand.Parameters\["somethingelse"\].Value = _somethingelse;

//执行语句
mycommand.ExecuteNonQuery();
```