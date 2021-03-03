---
title: DevExpress GridControl部分小结
categories: .NET
date: 2017-02-05 10:30:47
tags: [.NET]
---

# 1. 获取数据源与回写数据源

&emsp;&emsp;设置数据源:

`gridControl1.DataSource = listdt;`

&emsp;&emsp;回写数据源：
```cs
gridView1.CloseEditor();       //首先关闭editor
gridView1.UpdateCurrentRow();  //将当前所在行提交更改
OleDbCommandBuilder scb = new OleDbCommandBuilder(myadapter);  
//利用commandbuilder建立辅助
int count=myadapter.Update(dt); //用DataAdapter提交Update
myset.AcceptChanges();          //本次更新的数据保存修改
```
# 2. 列间计算与行间累加

### 列间计算
&emsp;&emsp;设置列的`UnboundExpression`属性，并将`UnboundType`设置为`Integer`。

![](https://s2.ax1x.com/2019/08/07/e4x9r8.jpg)

### 行间计算
&emsp;&emsp;将需要累和的列的SummaryItem中的SummaryType设置为Sum，随后在DisplayFormat中设置为想要的格式，并确保勾选Show Footer即可。  

![](https://s2.ax1x.com/2019/08/07/e4xpKf.jpg) 

&emsp;&emsp;获取Footer中的累计值。
```C#
string stt = gridView1.Columns["Isum"].SummaryItem.SummaryValue.ToString();
```

# 3. 列排序与行排序

&emsp;&emsp;列排序：设置不同列的VisibleIndex值进行手动排列。

&emsp;&emsp;行排序：设置某列的SortOrder为Ascending（升序）或Descending（降序），就可按改列值对行进行排序。
