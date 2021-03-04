---
title: 从零开始的JavaWeb（四）使用Tomcat JDBC pool优化数据库连接
categories: Java
date: 2018-04-28 21:58:42
tags: [Java,后端]
---

&emsp;&emsp;如果每次对数据库的访问都建立连接，在高并发情况下数据库会拒绝连接：too many connections 如果使用Tomcat JDBC Pool，则由Tomcat对连接池进行维护，对于超出限制的连接可以进行等待，防止连接过多。 

&emsp;&emsp;1.Tomcat配置   
在项目的WebContent/META-INF下新建context.xml文件进行配置。

&emsp;&emsp;2.DBConnection类的实现
```java
public class DBConnection {
	 private static DataSource ds = null;
	    static{
	        try{

	            Context initCtx = new InitialContext();
                    //根据元素的name属性值到JNDI容器中检索连接池对象，固定写法
	            Context envCtx = (Context)initCtx.lookup("java:comp/env");
                    //根据配置中的池名称检索获得连接池
	            ds = (DataSource) envCtx.lookup("dbpool");    
	        
	        }catch (Exception e) {
	            throw new ExceptionInInitializerError(e);
	        }
	    }
	    
	    public static Connection getConnection() throws SQLException {
                //获取连接
	        return ds.getConnection();
	    }
	    
	    public static void CloseConnection(Connection conn) {
                //归还连接
	        if(conn!=null) {
	            try{
	                conn.close();
	            }catch (Exception e) {
	                e.printStackTrace();
	            }
	        }
	    }
}
```