---
title: SpingBoot常见注解说明
date: 2018-06-07 18:59:30
tags:
---



## @EqualsAndHashCode
此注解会生成equals(Object other) 和 hashCode()方法。

## @Autowired
自动填充，可以通过Autowired引入需要新建的对象，就无需每次调用时新建。
```Java
@Autowired
private  LocaleMessageSourceUtil localeMessageSourceUtil;
```
就相当于
```Java
private  LocaleMessageSourceUtil localeMessageSourceUtil=new LocaleMessageSourceUtil();
```
## @ControllerAdvice


## @Builder
## @AllArgsConstructor
## @NoArgsConstructor
## @ExceptionHandler

# Response类别
## @RequestMapping
用于进行路径映射
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
加在controller函数的前面，可以将返回的对象自动转换为json字符串。

# Lombok
lombok是一个注解种类，可以自动生成一些方法，从而减少开发复杂度
## @Getter/@Setter
可以用@Getter/@Setter注释任何字段（当然也可以注释到类上的），lombok会自动生成默认的getter/setter方法。
## @Data
相当于@Getter @Setter @RequiredArgsConstructor @ToString @EqualsAndHashCode这5个注解的合集。  
可自动生成类的Get和Set等方法。