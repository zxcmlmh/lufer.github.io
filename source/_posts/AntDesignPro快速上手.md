---
title: AntDesign Pro 快速上手
date: 2019-04-22 13:19:24
categories: 前端
tags: [React,前端]
---
## 前期准备
### 环境配置

1. 安装Node.js  
2. Clone项目  
3. 本地NPM INSTALL完成相关组件的自动安装  

## 新建页面

&emsp;&emsp;项目组织结构：

```
.
├── /src/            # 项目源码目录
| |—— /assets        # 图标
| |—— /common
|   |—— /menu        # 菜单
    |—— /router      # 路由
| |—— /layouts       # 布局
│ ├── /components/   # 项目组件
│ ├── /routes/       # 路由组件（页面维度）
│ ├── /models/       # 数据模型
│ ├── /services/     # 数据接口
│ ├── /utils/        # 工具函数
│ ├── router.js      # 路由权限？
| |—— config.js      # 全局配置
│ ├── index.js       # 入口文件
│ ├── index.less     
│ └── index.ejs
```

### 创建文件

&emsp;&emsp;与routes文件夹下按照喜好的组织结构建立js文件。

&emsp;&emsp;本例中建立文件夹Tutorial，并建立demo1.js与demo2.js两个页面。

```
.
├── /src/            
│ ├── /routes/       
    |—— /Tutorial    
        |—— /demo1.js
        |—— /demo2.js
```

### 设定菜单

&emsp;&emsp;在common/menu下，托管了项目所有的左边栏菜单。

&emsp;&emsp;菜单以Json数组形式存在，如下是一个完整的菜单项。
```Json
{
    name: 'dashboard',          #菜单名
    icon: 'dashboard',          #菜单图标
    path: 'dashboard',          #菜单路径
    authority: 'admin',         #权限控制    
    children: [                 #子菜单
      {                            
        name: '分析页',         
        path: 'analysis',
      },
    ],
},
```
&emsp;&emsp;我们在其中添加一个菜单项。
```Json
{
    name: 'AntD-Tutorial',      
    icon: 'dashboard',         
    path: 'tutorial',   
    children: [                
      {                            
        name: '演示页面1',         
        path: 'demo1',
      },
      {                            
        name: '演示页面2',         
        path: 'demo2',
      },
    ],       
},
```
### 关联菜单与页面

&emsp;&emsp;在菜单页中，我们将两个子菜单分别指向了：  
&emsp;&emsp;`/tutorial/demo1`  
&emsp;&emsp;`/tutorial/demo2`

&emsp;&emsp;接下来需要接管该路径，指向对应的js文件。

&emsp;&emsp;修改`/common/router.js`。

&emsp;&emsp;在routerConfig项中添加如下所示路径，即可绑定地址与页面。

```JavaScript
'/tutorial/demo1': {
    component: dynamicWrapper(app, [], () => import('../routes/Tutorial/demo1')),
},
'/tutorial/demo2': {
    component: dynamicWrapper(app, [], () => import('../routes/Tutorial/demo2')),
},
```

## 页面1-原生开发

&emsp;&emsp;在demo1，我们将介绍AntDesign架构的原生开发方式，也借此来理解AntDesign的页面生命周期与数据驱动流程。

&emsp;&emsp;我们建立一个十分简单的空白页面demo1，并逐步丰富内容来实现最终功能

```JS
import React, { Component, Fragment } from 'react';

export default class demo1 extends Component {
  componentDidMount() {
  }

  render() {
    return (
      <Fragment>

      </Fragment>
    );
  }
}
```

&emsp;&emsp;AntDesign提供了十分丰富的标准页面，例如表单页，列表页，详情页等，我们这里手动实现一次列表页。

&emsp;&emsp;既然是数据驱动，所以我们从数据开始，像页面倒推实现过程。

### 向后端发起请求

&emsp;&emsp;所有向后端发起的请求，均托管于`servvices/api.js`中。

&emsp;&emsp;我们在其中实现一个请求函数。

&emsp;&emsp;这里强调一下，既然是前后端分离开发，所以各部分人员可以各司其职，并不需要相互制约，只要制定了前后端的数据格式，并且双方均遵守，即可各自分离开发，在最后阶段进行联调即可。

&emsp;&emsp;为了实现分离开发，前端可以在自行开发阶段对请求自动进行返回。

&emsp;&emsp;此时我们实现如下所示的一个请求函数：


```JS
export async function GetDataFromBackStage() {
  return request('/api/fake_get_data');
}
```

&emsp;&emsp;此处可见，我们向api/fake_get_data发起了一个请求。

&emsp;&emsp;前端提供了一个mock工具，RoadHog，用于拦截请求，返回数据，从而让前端开发不再依赖于后端。

&emsp;&emsp;我们在.roadhogrc.mock.js中对该请求进行一个拦截并模拟了一组返回值。

&emsp;&emsp;其语法是`请求方式 请求路径:{返回值}`。

```JS
'GET /api/fake_get_data': {
    "pagination":
      { "total": 1, "current": 1, "pageSize": 10 },
    "list": [
      {
        "dataid": 1,
        "time": "2018-10-25T12:29:25.000+0000",
        "datasourcetype": "kafka",
        "status": 1,
        "name": "测试用-北京出租车GPS数据",
        "datatype": 0,
        "crossprovince": 1,
        "datalocation": "北京市",
        "datafield": 1,
        "company": "Didicompany",
        "datadescription": "测试用-北京出租车GPS数据",
        "ownnode": 5,
        "datadefinition": "字段名:StrCompanyID,数据类型:string,字段说明:StrCompanyID;字段名:StrDepLongitude,数据类型:double,字段说明:StrDepLongitude;字段名:StrDepLatitude,数据类型:double,字段说明:StrDepLatitude;字段名:StrOrderID,数据类型:string,字段说明:StrOrderID;",
        "url": "/user/download?file=f6bbfb6e512531cb78a086b94708f398.166ab336727&name=order_true.json",
        "resourceip": "127.0.0.1",
        "kafkatopic": "ORDER",
        "dataurl": "127.0.0.1:8000",
      },
      {
        "dataid": 2,
        "time": "2018-10-25T12:39:55.000+0000",
        "datasourcetype": "kafka",
        "status": 1,
        "name": "北京出租车GPS数据",
        "datatype": 1,
        "crossprovince": 1,
        "datalocation": "北京市",
        "datafield": 1,
        "company": "Didicompany",
        "datadescription": "北京出租车GPS数据",
        "ownnode": 6,
        "datadefinition": "字段名:StrCompanyID;字段名:StrDepLongitude;字段名:StrDepLatitude;字段名:StrOrderID;",
        "url": "/user/download?file=f6bbfb6e512531cb78a086b94708f398.166ab3d0424&name=order_true.json",
        "resourceip": "192.168.3.19",
        "kafkatopic": "ORDER",
        "dataurl": "192.168.3.19:8000",
      },
    ],
  },
```

&emsp;&emsp;这样在api中发送请求时，roadhog就可以先将请求拦截下，从而方便前端开发，而在进行联调时，仅需更改api中的请求函数，即可与后端进行测试，例如可改成如下代码段。

```JS
export async function DataSearch(params) {
  const curToken = token.get();
  return request(`${config.domain}/data/Search`, {
    method: 'POST',
    headers: {
      Authorization: curToken,
    },
    body: params,
  });
}
```

### 调用请求发起函数

&emsp;&emsp;现在我们有了前后端交互的最后一步，即发起请求的函数，那么我们再向前一步，实现调用该函数的函数。

&emsp;&emsp;model层托管了所有的数据服务，我们在Model下新建一个tutorialModel.js，来实现View层与API层的连接。

&emsp;&emsp;先放上全部代码，再进行解释。
```JS
import { GetDataFromBackStage } from '../services/api';    #从api中引入我们定义的请求函数

export default {
  namespace: 'tutorialModel',      #组件名
  state: {                         #state定义了你的数据格式，我们这里使用了一个data变量
    data: {
      list: [],
      pagination: {},
    },
  },

  effects: {
    *getData({  }, { call, put }) {                         #定义一个getData函数
      const response = yield call(GetDataFromBackStage, );  #调用我们定义的请求发起函数 
      yield put({                                           #这里处理返回值，调用reducer中的saveData，把返回值传过去
        type: 'saveData',
        payload: response,
      });
    },
  },
  reducers: {
    saveData(state, { payload }) {
      return {
        ...state,                                           #将state中的data取出来，并赋新值
        data: payload,
      };
    },
  },
};

```

&emsp;&emsp;至此就完成了数据的获取与保存，在下一步则是进行页面View层的更新。

### 更新页面

&emsp;&emsp;在最后一步，我们完成请求的发起与结果的获取，回到我们的demo.js。

&emsp;&emsp;我们以按照DashBoard下面的该表格为例，实现一个表格页面。

![](https://i.loli.net/2019/08/07/ChdFbAEi9xTVBRX.jpg)

&emsp;&emsp;调用该组件，我们仅需在render中添加一个table标签，然后绑定各项数据源即可，查看示例页的实现，可见其table参数如下：
```JS
<Table
  rowKey={record => record.index}
  size="small"
  columns={columns}
  dataSource={searchData}
  pagination={{
    style: { marginBottom: 0 },
      pageSize: 5,
    }}
/>
```
&emsp;&emsp;我们所要修改的也就是其columns和DataSource了。

&emsp;&emsp;首先，在componentDidMount中，通过dispatch方法，发起请求，来触发前几步中所实现的逐项函数，这里才是这些函数的调用起点。

```JS
  componentDidMount() {
    const { dispatch } = this.props;
    dispatch({
      type: 'tutorialModel/getData',
    });
  }
```

&emsp;&emsp;通过connect，将model和这个页面的component连接起来，从而可以在页面中调用model保存下来的数值。

```JS
@connect(({ tutorialModel}) => ({
  tutorialModel,
}))

```

&emsp;&emsp;注意，@connect要写在component定义的前面。

&emsp;&emsp;获取到的数据是保存在props中的，我们先把数据解构出来，方面后面调用。
```JS
const { tutorialModel }=this.props;
const { data }=tutorialModel;
```
&emsp;&emsp;再定义一下表格的各列信息。
```JS
const columns = [
      {
        title: '数据名称',
        dataIndex: 'name',
      },
      {
        title: '描述',
        dataIndex: 'datadescription',
      },
      {
        title: '数据领域',
        dataIndex: 'datafield',
      },
      {
        title: '状态',
        dataIndex: 'status',
      },
      {
        title: '时间',
        dataIndex: 'time',
      },
    ];
```
&emsp;&emsp;最后在配置一下Table的各项数据源。
```JS
<Table
          rowKey="dataid"
          size="small"
          columns={columns}
          dataSource={data.list}
          pagination={{
              style: { marginBottom: 0 },
              pageSize: 5,
            }}
        />
```
&emsp;&emsp;完成，运行效果如图：

![](https://i.loli.net/2019/08/07/IAWpl6m8Ys7kZzX.jpg)

&emsp;&emsp;页面完整代码如下。
```JS
import React, { Component } from 'react';
import { connect } from 'dva';
import { Card,Table} from 'antd';

@connect(({ tutorialModel }) => ({
  tutorialModel,
}))

export default class demo1 extends Component {

  componentDidMount() {
    const { dispatch } = this.props;
    dispatch({
      type: 'tutorialModel/getData',
    });
  }

  render() {
    const { tutorialModel }=this.props;
    const { data }=tutorialModel;
    const columns = [
      {
        title: '数据名称',
        dataIndex: 'name',
      },
      {
        title: '描述',
        dataIndex: 'datadescription',
      },
      {
        title: '数据领域',
        dataIndex: 'datafield',
      },
      {
        title: '状态',
        dataIndex: 'status',
      },
      {
        title: '时间',
        dataIndex: 'time',
      },
    ];

    return (
      <Card bordered={false}>
        <Table
          rowKey="dataid"
          size="small"
          columns={columns}
          dataSource={data.list}
          pagination={{
              style: { marginBottom: 0 },
              pageSize: 5,
            }}
        />
      </Card>
    );
  }
}
```

&emsp;&emsp;最后注意，要修改一下router，将我们定义的model传给页面，否则会找不到数据。
```JS
'/tutorial/demo1': {
  component: dynamicWrapper(app, ['tutorialModel'], () => import('../routes/Tutorial/demo1')),
},
```


## 页面2-快速移植
&emsp;&emsp;在页面2，我们将演示如何快速的将一个现有页面完成移植。

&emsp;&emsp;我们要实现的目标页面如下图所示：

![](https://i.loli.net/2019/08/07/zgY51FiN8he4WqO.jpg)

### 静态文件移植
&emsp;&emsp;相关json文件，js文件，css文件等静态文件，全部放在public目录下

### 静态文件引入

&emsp;&emsp;JS文件，在index.ejs中进行引入。

![](https://i.loli.net/2019/08/07/wTRKvFLrd6OqzCG.jpg)

&emsp;&emsp;CSS文件，在同级目录下，建立demo2.less文件。

&emsp;&emsp;将页面中自定义的css样式复制其中，注意这里的css命名不能带“-”。

&emsp;&emsp;通过@import的方式，引入现成的静态CSS文件，代码如下：

```JS
@import '/css/commons.min.css';
@import '/css/scheme-polygon.min.css';
@import '//minedata.cn/minemapapi/v1.4/minemap.css';


.buttongroup {
  position: absolute;
  top: 20px;
  right: 120px;
  z-index: 3;
}

.mybutton {
  background-color: #257962;
  border: none;
  color: white;
  padding: 10px 25px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 10px;
  cursor: pointer;
}

.leftlist{
  background-color: #333333;
  position: absolute;
  left:15%;
  top:10%;
  opacity: .6;
  color:#ffffff;
  border-radius: 5px;
}

.table-row:hover {
  background-color: #666666;
}
#map-legend-box{
  display:none !important
}

```
### 页面实现

&emsp;&emsp;将原页面的HTML代码，全部放在render的return中，这里注意，所有的style需要改为json数组的形式进行实现，不能写成HTML的形式,而通过className方式引入的css样式可以正常使用。

&emsp;&emsp;代码节选如下：

```JS
 render() {
    return (
      <div>
        <div id="map"  style={{width: '100%',height:'700px'}}>
          <div className="map-control-box" id="map-control-box">
            <div id="map-control-btn-box" className="map-control-btn-box-dgmsd">
            </div>
            <div id="tipBox" className="tip-box-dgmsd select-1">
              <div id="tipInfo" className="tip-info"></div>
            </div>
          </div>
          <div id="map-legend-box" className="map-legend-box" style={{display:"none"}}>
          </div>
          <div className="map-select-locale" id="map-select-locale">
            <div className="select-control-1 btn-select-locale" id="select-control-1">
              <div className="select-text">2D</div>
            </div>
            <div className="select-control-2" id="select-control-2">
              <div className="select-text">3D</div>
            </div>
          </div>
      </div>
      .........
      </div>
    );
  }
```

&emsp;&emsp;完成页面移植，效果如下图：

![](https://i.loli.net/2019/08/07/fboKDguXcMOYFxE.jpg)

&emsp;&emsp;当然，这其中还需要做一些元素位置，部分js代码的微调。

### JS代码

&emsp;&emsp;如果页面有JS代码需要实现，只需写在componentDidMount中即可，以D3拓扑图为例。

&emsp;&emsp;代码节选如下：
```JS
 componentDidMount() {

    var width = document.getElementById("knowledge_graph").offsetWidth;
    var height = document.getElementById("knowledge_graph").offsetHeight;
    var img_w = 32;
    var img_h = 32;

    var svg = d3.select("#knowledge_graph").append("svg")
      .attr("width",width)
      .attr("height",height)
      .attr("vertical-align","center");

    d3.json("http://47.92.162.213:8080/static/data/topology.json",function(error,root){

      if( error ){
        return console.log(error);
      }
      console.log(root);

      var force = d3.layout.force()
        .nodes(root.nodes)
        .links(root.edges)
        .size([width,height])
        .linkDistance(90)
        .charge(-500)
        .start();

      ...........

```

&emsp;&emsp;其运行结果如下图，图标资源没有修改路径，所以没有加载，但功能均正常。

![](https://i.loli.net/2019/08/07/PchmW98Q4RYGxrb.jpg)

&emsp;&emsp;此页面在Dashboard/helloworld下可以看见源码。


## 最后-数据调用

&emsp;&emsp;如果你需要用该框架获取数据，在用自己的JS逻辑来实现页面。

&emsp;&emsp;在数据获取阶段，需要参考第一部分的原生开发方式，在model，api，和页面中完成数据获取阶段，随后数据就被保存在了props中。

&emsp;&emsp;接下来如果想实现自己的逻辑，我在这里以echarts为例，部分代码如下：

```JS
  componentDidMount() {
    //获取你的数据
    ........
    //开始实现echarts
    // echarts原来该怎么写就怎么写
    var myChart = echarts.init(document.getElementById('main'));
    myChart.setOption({
        title: { text: '某地区蒸发量和降水量' },
        tooltip : {
          trigger: 'axis'
      },
      legend: {
          data:['蒸发量','降水量']
      },
      toolbox: {
          show : true,
          feature : {
              dataView : {show: true, readOnly: false},
              magicType : {show: true, type: ['line', 'bar']},
              restore : {show: true},
              saveAsImage : {
                show: true,
                type: 'jpg'
              }
          }
      },
        xAxis : [
          {
              type : 'category',
              data : this.props.data.xdata    //在这里使用你的数据即可
          }
      ],
      yAxis : [
          {
              type : 'value'
          }
      ],
        series : [
          {
              name:'蒸发量',
              type:'bar',
              data: this.props.data.ydata.ydata1,
              markPoint : {
                  data : [
                      {type : 'max', name: '最大值'},
                      {type : 'min', name: '最小值'}
                  ]
              },
  .........................
```