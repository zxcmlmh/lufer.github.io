---
title: AntDesign数据驱动流程
date: 2018-11-15 15:49:00
categories: 前端
tags: [React,前端]
---
&emsp;&emsp;AntDesign Pro的数据驱动流程一共涉及三个部分：页面，Model，API。

# 页面

&emsp;&emsp;从页面讲起，页面通过Dispatch来发起请求，将参数写在payload中，请求的地址写在type中，而这里是不关心返回值的。
```JavaScript
 dispatch({
      type: 'ModelA/FunctionA',
      payload: {
        Param:param
      },
    });
```
&emsp;&emsp;这里我用了ModelA和FuntionA，方便与后文对应，也就意味着存在一个model名字为ModelA，其中有一个函数为FunctionA。

&emsp;&emsp;假设ModelA存在，后续流程完成，那么我们需要获取返回的请求结果。

&emsp;&emsp;在页面的connect处，我们连接Model，并获取返回值。

```JavaScript
export default connect(({ModenlA}) => ({
  Data: ModelA.data,
}))(View1);
```
&emsp;&emsp;这样在Data中，我们就拿到了请求的结果，并可以在页面中使用。

# Model

&emsp;&emsp;在ModelA中，我们需要定义一个FunctionA函数，用来响应页面的请求，该函数应该写在effects里面。

&emsp;&emsp;先看代码：

```JavaScript
effects: {
    *FunctionA({ payload }, { call, put }) {
      const response = yield call(APIA, payload);
      yield put({
        type: 'ReducerA',
        payload: response,
      });
    },
}
```
&emsp;&emsp;我们定义了FunctionA用来响应请求，payload接收被调用时传入的参数，并在yield中进一步传递下去。

&emsp;&emsp;yield中会调用API中的对应接口，并真正的向后端发送请求。

&emsp;&emsp;put中则是对返回值进行处理，Type处我们调用了ReducerA，并把respons（即请求返回值）作为参数传送过去。

&emsp;&emsp;在Reducer中，Model正式将请求的返回值进行保存，这样当页面通过connect对model进行连接之后，就可以对返回值进行调用。

```JavaScript
 state: {
    Data: [],
  },
 reducers: {
    ReducerA(state, { payload }) {
      return {
        ...state,
      };
    },
}
```
# API

&emsp;&emsp;API层进行真正的前后端交互，发送请求到后端，并获取返回结果。

```JavaScript
export async function APIA(params) {
  return request(`${config.domain}/api`, {
    method: 'POST',
    headers: {
    },
    body: params,
  });
}
```