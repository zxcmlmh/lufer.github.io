---
title: Spring Boot自定义异常处理与返回状态
date: 2018-06-07 18:54:42
categories: Java
tags: [Spring Boot,Java]
---
&emsp;&emsp;本文目的：集成Spring Boot的异常处理类，进行自定义的全局异常处理，并自定义返回的HTTP状态码。

>目录
* [新建异常类型](#新建异常类型)
* [结果处理类](#结果处理类)
* [接管全局异常处理](#接管全局异常处理)

# 新建异常类型

&emsp;&emsp;新建一个BusinessException并继承自RuntimeException。

```Java
public class BusinessException extends RuntimeException {
    protected String message;
    public BusinessException(String resultCode) {
        this.message=resultCode;
    }
```
# 结果处理类
&emsp;&emsp;建立一个结果处理类，对异常进行一些封装，从而获得一个格式化的返回结果。
```Java
@Builder
@AllArgsConstructor
@NoArgsConstructor
@Data
public class DefaultErrorResult  {
    private Integer status;
    public static DefaultErrorResult failure(BusinessException e) {
        DefaultErrorResult result = new DefaultErrorResult();
        result.setMessage(e.getMessage());
        result.setStatus(600);
        result.setError("Business Error");
        result.setException(e.getClass().getName());
        result.setPath(RequestContextHolderUtil.getRequest().getRequestURI());
        result.setTimestamp(new Date());
        return result;
    }
}
```

# 接管全局异常处理
&emsp;&emsp;建立ExceptionHandler类，通过@ControllerAdvice注解，接管全局的异常处理。
```Java
@ControllerAdvice
public class GlobalExceptionHandler  {
    /**
     * 处理400类异常
     * 违反约束异常
     */
    @ResponseStatus(HttpStatus.BAD_REQUEST)
    @ExceptionHandler(ConstraintViolationException.class)
    public DefaultErrorResult handleConstraintViolationException(ConstraintViolationException e, HttpServletRequest request) {


        log.error("handleConstraintViolationException start, uri:{}, caused by: ", request.getRequestURI(), e);
        List<ParameterInvalidItem> parameterInvalidItemList = ParameterInvalidItemHelper.convertCVSetToParameterInvalidItemList(e.getConstraintViolations());
        return DefaultErrorResult.failure(ResultCode.COMM_PARAM_IS_INVALID, e, HttpStatus.BAD_REQUEST, parameterInvalidItemList);

    }
     /**
     * 处理验证参数封装错误时异常
     */
    @ResponseStatus(HttpStatus.BAD_REQUEST)
    @ExceptionHandler(HttpMessageNotReadableException.class)
    public DefaultErrorResult handleConstraintViolationException(HttpMessageNotReadableException e, HttpServletRequest request) {
        log.error("handleConstraintViolationException start, uri:{}, caused by: ", request.getRequestURI(), e);
        return DefaultErrorResult.failure(ResultCode.COMM_PARAM_IS_INVALID, e, HttpStatus.BAD_REQUEST);
    }

    /**
     * 处理参数绑定时异常（反400错误码）
     */
    @ResponseStatus(HttpStatus.BAD_REQUEST)
    @ExceptionHandler(BindException.class)
    public DefaultErrorResult handleBindException(BindException e, HttpServletRequest request) {
        log.error("handleBindException start, uri:{}, caused by: ", request.getRequestURI(), e);
        List<ParameterInvalidItem> parameterInvalidItemList = ParameterInvalidItemHelper.convertBindingResultToMapParameterInvalidItemList(e.getBindingResult());
        return DefaultErrorResult.failure(ResultCode.COMM_PARAM_IS_INVALID, e, HttpStatus.BAD_REQUEST, parameterInvalidItemList);

    }
    /**
     * 处理使用@Validated注解时，参数验证错误异常（反400错误码）
     */
    @ResponseStatus(HttpStatus.BAD_REQUEST)
    @ExceptionHandler(MethodArgumentNotValidException.class)
    public DefaultErrorResult handleMethodArgumentNotValidException(MethodArgumentNotValidException e, HttpServletRequest request) {
        log.error("handleMethodArgumentNotValidException start, uri:{}, caused by: ", request.getRequestURI(), e);
        List<ParameterInvalidItem> parameterInvalidItemList = ParameterInvalidItemHelper.convertBindingResultToMapParameterInvalidItemList(e.getBindingResult());
        return DefaultErrorResult.failure(ResultCode.COMM_PARAM_IS_INVALID, e, HttpStatus.BAD_REQUEST, parameterInvalidItemList);

    }
    /* 处理运行时异常 （反500错误码）*/
    @ResponseStatus(HttpStatus.INTERNAL_SERVER_ERROR)
    @ExceptionHandler(RuntimeException.class)
    public DefaultErrorResult handleRuntimeException(RuntimeException e, HttpServletRequest request) {
        //TODO 可通过邮件、微信公众号等方式发送信息至开发人员、记录存档等操作
        log.error("handleRuntimeException start, uri:{}, caused by: ", request.getRequestURI(), e);
        return DefaultErrorResult.failure(ResultCode.COMM_SYSTEM_INNER_ERROR, e, HttpStatus.INTERNAL_SERVER_ERROR);
    }
    /* 处理自定义异常 */
    /*
        主要在这里进行一个自定义异常的处理
        首先通过Business.class说明该函数接管此类异常
    */
    @ExceptionHandler(BusinessException.class)
    public ResponseEntity<DefaultErrorResult> handleBusinessException(BusinessException e, HttpServletRequest request) {
        log.error("handleBusinessException start, uri:{}, exception:{}, caused by: {}", request.getRequestURI(), e.getClass(), e.getMessage());
        //通过定义的failure方法来初始化格式化的返回结果
        DefaultErrorResult defaultErrorResult = DefaultErrorResult.failure(e);
        /*
         ResponseEntity.status().body();
         status处可填入任何状态码，填多少返回时的HTTP状态就是多少，body处填入返回的结果
        */
        return ResponseEntity.status(defaultErrorResult.getStatus()).body(defaultErrorResult);
    }
```