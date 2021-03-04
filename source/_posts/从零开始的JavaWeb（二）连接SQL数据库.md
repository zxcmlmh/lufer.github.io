---
title: 从零开始的JavaWeb（二）连接SQL数据库
categories: Java
date: 2018-04-20 23:45:13
tags: [Java,后端]
---

&emsp;&emsp;1、引入sql的jar包
```
mysql-connector-java-5.1.45-bin.jar
```
&emsp;&emsp;2、建立连接,注意此处需要catch异常

```java
Connection connection = null;
try {
	Class.forName("com.mysql.jdbc.Driver");
        //设置SQL的地址和数据库名
        String url = "jdbc:mysql://127.0.0.1/DataBaseName";
        //设置登录用户名的账号和密码
        connection = DriverManager.getConnection(url, "Username", "Password");
        //字符串写SQL语句
        String sql="SQL Sentences";
        PreparedStatement preparedStatement = connection.prepareStatement(sql);
        //preparedStatment有两种方法
        //executeUpdate执行Insert和Update操作，返回int型数据，为受影响的行数
        //executeQuery执行各种查询操作，返回ResultSet
        int re = preparedStatement.executeUpdate();
        //SELECT LAST\_INSERT\_ID() 可以获取本次连接最后插入的新行的ID
        preparedStatement = connection.prepareStatement("SELECT LAST\_INSERT\_ID()");
        ResultSet re2 = preparedStatement.executeQuery();
        //通过ResultSet.next()方法取下一行
        while(re2.next()){
        //ResultSet有getString,getInt等方法，根据需要的返回值而定，传参可以是字符串，用于按列名查找，也可以是整数，用于按序号查找
        String quizid=re2.getString("LAST\_INSERT\_ID()");
        //关闭连接，否则在大量查询时会被服务器拒绝
        connection.close();
}
catch(ClassNotFoundException e) {
        System.out.println("Sorry,can`t find the Driver!");
        e.printStackTrace();
}
catch(SQLException e) {
        //数据库连接失败异常处理
        e.printStackTrace();
}
catch (Exception e) {
        // TODO: handle exception
        e.printStackTrace();
}finally{
        System.out.println("Operation Finished");
}```