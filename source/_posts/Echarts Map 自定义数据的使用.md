---
title: Echarts Map 自定义数据的使用
categories: 前端
date: 2018-01-23 19:27:44
tags: [Echarts,前端]
---

# 1. Map中坐标点带自定义数据

&emsp;&emsp;在坐标点后添加任意数据，构造形如`[116.377047322,39.9340718473,1]`的Json对，在使用时可以通过监听函数读取`data`的`[2][3]...`等字段。
```javascript
 myChart.on('click', function (params) {
     var id = params.data\[2\];
 });
```
# 2. VisualMap与自定义数据的映射
&emsp;&emsp;对于任何自定义数据，例如Map中的Link，无法通过制定Category的方式有效的与VisualMap进行自动映射，只需在数据中添加Value项，VisualMap即可自动识别。
```javascript
pathbd.push(
                {
                    coords: cvps,
                    value:data20[i][3],
                    lineStyle: {
                        normal: {
                            opacity: 100,
                            width: truewidth
                        }
                    }
                });

 visualMap: {
                type: 'piecewise',
                left: 'right',
                top: 'up',
                pieces: [{
                    gt: 0,
                    lte: 4,
                    color: 'blue'
                }, {
                    gt: 4,
                    lte: 6,
                    color: 'green'
                }, {
                    gt: 6,
                    lte: 8,
                    color: 'yellow'
                }, {
                    gt: 8,
                    color: 'red'
                }]
            },
```