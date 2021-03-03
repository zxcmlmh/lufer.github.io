---
title: SpingBoot常见注解说明
date: 2018-06-07 18:59:30
categories: Java
tags: [Spring Boot,Java]
toc: true
---
* [Bean类](#Bean类)
    * [@Service](#Service)
    * [@Controller](#Controller)
    * [@Repository](#Repository)
    * [@Component](#Component)
* [Response类](#Response类)
    * [@RequestMapping](#RequestMapping)
    * [@ResponseBody](#ResponseBody)
    * [@RestController](#RestController)
    * [@Component](#Component)
* [Lombok类](#Lombok类)
    * [@@Getter/@Setter](#@Getter/@Setter)
    * [@Data](#Data)
    * [@Cleanup](#Cleanup)
    * [@EqualsAndHashCode](#EqualsAndHashCode)
    * [@AllArgsConstructor](#AllArgsConstructor)
    * [@NoArgsConstructor](#NoArgsConstructor)
    * [@Builder](#Builder)
    * [@NonNull](#NonNull)
* [异常处理类](#异常处理类)
    * [@ControllerAdvice](#ControllerAdvice)
    * [@ExceptionHandler](#ExceptionHandler)
    * [@RestController](#RestController)
    * [@Component](#Component)
* [其他](#其他)
    * [@Autowired](#Autowired)
    
# Bean类
## @Service
&emsp;&emsp;注解在类上，表示这是一个业务层bean。
## @Controller
&emsp;&emsp;注解在类上，表示这是一个控制层bean,可以解析返回的jsp,html页面，并且跳转到相应页面。
```Java
@Controller
public class TestController {
    @RequestMapping(value="/gouploadimg", method = RequestMethod.GET)
    public String goUploadImg() {
    //跳转到 templates 目录下的 uploadimg.html
    return "uploadimg";
}
```
## @Repository
&emsp;&emsp;注解在类上，表示这是一个数据访问层bean。
## @Component
&emsp;&emsp;注解在类上，表示通用bean。

# Response类
## @RequestMapping
&emsp;&emsp;用于进行路径映射。
```Java
//对test路径进行映射，其下方函数用于对路径test的请求进行处理
@RequestMapping("/test") 
//其默认参数为value，即不指定时，参数当做value处理，上一行等价于
@RequestMapping(value="/test")
//method:指定请求的method类型， GET、POST、PUT、DELETE等
@RequestMapping(value="/test", method=RequestMethod.GET)
//consumes:指定处理请求的提交内容类型（Content-Type），例如application/json,text/html
@RequestMapping(value = "/test", method=RequestMethod.POST,consumes="application/json")
//produces:指定返回的内容类型，但仅当请求头中的(Accept)类型也是该类型时才处理请求
@RequestMapping(value = "/test", method=RequestMethod.GET,produces="application/json")
//params:指定request中必须包含某些参数值时，才让该方法处理
//仅处理请求中包含了名为“myParam”，值为“myValue”的请求
 @RequestMapping(value = "/test", method = RequestMethod.GET, params="myParam=myValue")
//headers:指定request中必须包含某些指定的header值，才能让该方法处理请求
//仅处理request的header中包含了指定“Refer”请求头和对应值为“http://www.ifeng.com/”的请求；
@RequestMapping(value = "/test", method = RequestMethod.GET, headers="Referer=http://www.ifeng.com/")
```
## @ResponseBody
&emsp;&emsp;加在controller函数的前面，可以将返回的对象自动转换为json字符串。
## @RestController
&emsp;&emsp;相当于@ResponseBody和@Controller的结合。

# Lombok类
&emsp;&emsp;lombok是一个注解种类，可以自动生成一些方法，从而减少开发复杂度。
## @Getter/@Setter
&emsp;&emsp;可以用@Getter/@Setter注释任何字段（当然也可以注释到类上的），lombok会自动生成默认的getter/setter方法。
## @Data
&emsp;&emsp;相当于`@Getter @Setter @RequiredArgsConstructor @ToString @EqualsAndHashCode`这5个注解的合集。  
&emsp;&emsp;可自动生成类的Get和Set等方法。
## @Cleanup
&emsp;&emsp;可以加在IO变量声明之前，这样会在使用后自动释放IO变量。
```Java
 @Cleanup InputStream in = new FileInputStream(path);
 ```
## @EqualsAndHashCode
&emsp;&emsp;此注解会生成equals(Object other) 和 hashCode()方法。
## @AllArgsConstructor
&emsp;&emsp;为类生成包含所有参数的构造函数。
## @NoArgsConstructor
&emsp;&emsp;为类生成无参数的构造函数。
## @Builder
&emsp;&emsp;建筑者模式，是现在比较推崇的一种构建值对象的方式。  
&emsp;&emsp;会为类生成各种构造函数。
## @NonNull
&emsp;&emsp;注解在参数上 如果该参数为null 会throw new NullPointerException(参数名)。

# 异常处理类
## @ControllerAdvice
&emsp;&emsp;被ControllerAdvice注解的类会被认为是用来进行全局异常处理的类。
```Java
@ControllerAdvice
public class GlobalExceptionHandler {
}
```
## @ExceptionHandler
&emsp;&emsp;用于确认被其注释的方法所要处理的异常类型。
```Java
@ControllerAdvice
public class GlobalExceptionHandler {
    //接管运行时异常，进行处理
    @ExceptionHandler(RuntimeException.class)
    @ResponseBody
    String handleException(){
        return "Exception Deal!";
    }
}
```
# 其他
## @Autowired
&emsp;&emsp;自动填充，可以通过Autowired引入需要新建的对象，就无需每次调用时新建。
```Java
@Autowired
private  LocaleMessageSourceUtil localeMessageSourceUtil;
```
&emsp;&emsp;就相当于：
```Java
private  LocaleMessageSourceUtil localeMessageSourceUtil=new LocaleMessageSourceUtil();
```