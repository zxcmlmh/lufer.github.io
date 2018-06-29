---
title: leetcode题解-数据库部分
date: 2018-06-29 18:27:39
tags: LeetCode
categories: Code
---
# 175. 组合两个表
## 题目
表1: `Person`
```
+-------------+---------+
| 列名         | 类型     |
+-------------+---------+
| PersonId    | int     |
| FirstName   | varchar |
| LastName    | varchar |
+-------------+---------+
PersonId 是上表主键
```
表2: `Address`
```
+-------------+---------+
| 列名         | 类型    |
+-------------+---------+
| AddressId   | int     |
| PersonId    | int     |
| City        | varchar |
| State       | varchar |
+-------------+---------+
AddressId 是上表主键
```
编写一个 SQL 查询，满足条件：无论 person 是否有地址信息，都需要基于上述两表提供 person 的以下信息：
```
FirstName, LastName, City, State
```
## 题解
用左连接（LEFT JOIN）来保证保留Person表中的空记录。
```sql
SELECT person.FirstName, person.LastName,City,State
FROM person
LEFT JOIN address
ON person.PersonId = address.PersonId
```

# 182. 查找重复的电子邮箱
## 题目
编写一个 SQL 查询，查找 `Person` 表中所有重复的电子邮箱。

示例：
```
+----+---------+
| Id | Email   |
+----+---------+
| 1  | a@b.com |
| 2  | c@d.com |
| 3  | a@b.com |
+----+---------+
```
根据以上输入，你的查询应返回以下结果：
```
+---------+
| Email   |
+---------+
| a@b.com |
+---------+
```
说明：所有电子邮箱都是小写字母。
## 题解
按Email进行GroupBy，然后统计数量>1的
```sql
SELECT Email FROM (select count(*) as count2,Email from Person group by Email) a where count2>1
```

# 197. 上升的温度
## 题目
给定一个 `Weather` 表，编写一个 SQL 查询，来查找与之前（昨天的）日期相比温度更高的所有日期的 Id。
```
+---------+------------------+------------------+
| Id(INT) | RecordDate(DATE) | Temperature(INT) |
+---------+------------------+------------------+
|       1 |       2015-01-01 |               10 |
|       2 |       2015-01-02 |               25 |
|       3 |       2015-01-03 |               20 |
|       4 |       2015-01-04 |               30 |
+---------+------------------+------------------+
```
例如，根据上述给定的 `Weather` 表格，返回如下 Id:
```
+----+
| Id |
+----+
|  2 |
|  4 |
+----+
```
## 题解
把`Weather`表并表查询，挑选温度较大并且日期晚一天的记录，用`DATEDIFF`控制日期差距
```sql
select w1.ID from Weather w1,Weather w2
where w1.Temperature>w2.Temperature and DATEDIFF(w1.RecordDate,w2.RecordDate)=1
```


# 595. 大的国家
## 题目
这里有张`World`表
```
+-----------------+------------+------------+--------------+---------------+
| name            | continent  | area       | population   | gdp           |
+-----------------+------------+------------+--------------+---------------+
| Afghanistan     | Asia       | 652230     | 25500100     | 20343000      |
| Albania         | Europe     | 28748      | 2831741      | 12960000      |
| Algeria         | Africa     | 2381741    | 37100000     | 188681000     |
| Andorra         | Europe     | 468        | 78115        | 3712000       |
| Angola          | Africa     | 1246700    | 20609294     | 100990000     |
+-----------------+------------+------------+--------------+---------------+
```
如果一个国家的面积超过300万平方公里，或者人口超过2500万，那么这个国家就是大国家。

编写一个SQL查询，输出表中所有大国家的名称、人口和地区。

例如，根据上表，我们应该输出:
```
+--------------+-------------+--------------+
| name         | population  | area         |
+--------------+-------------+--------------+
| Afghanistan  | 25500100    | 652230       |
| Algeria      | 37100000    | 2381741      |
+--------------+-------------+--------------+
```
## 题解
简单查询
```sql
select name,population,area from world where area>3000000 or population>25000000
```
# 596. 超过5名学生的课
## 题目
有一个`courses`表 ，有: student (学生) 和 class (课程)。

请列出所有超过或等于5名学生的课。

例如,表:
```
+---------+------------+
| student | class      |
+---------+------------+
| A       | Math       |
| B       | English    |
| C       | Math       |
| D       | Biology    |
| E       | Math       |
| F       | Computer   |
| G       | Math       |
| H       | Math       |
| I       | Math       |
+---------+------------+
```
应该输出:
```
+---------+
| class   |
+---------+
| Math    |
+---------+
```
Note:
学生在每个课中不应被重复计算。

## 题解
先从课程中去重(`DISTINCT`),然后按照课程GroupBy并计数，取大于5的
```sql
SELECT class FROM (select count(*) as count2,class from (select distinct student,class from courses) b group by class) a where count2>=5
```

# 620. 有趣的电影

## 题目

某城市开了一家新的电影院，吸引了很多人过来看电影。该电影院特别注意用户体验，专门有个 LED显示板做电影推荐，上面公布着影评和相关电影描述。

作为该电影院的信息部主管，您需要编写一个 SQL查询，找出所有影片描述为非 boring (不无聊) 的并且 id 为奇数 的影片，结果请按等级 `rating` 排列。

例如，下表 `cinema`:
```
+---------+-----------+--------------+-----------+
|   id    | movie     |  description |  rating   |
+---------+-----------+--------------+-----------+
|   1     | War       |   great 3D   |   8.9     |
|   2     | Science   |   fiction    |   8.5     |
|   3     | irish     |   boring     |   6.2     |
|   4     | Ice song  |   Fantacy    |   8.6     |
|   5     | House card|   Interesting|   9.1     |
+---------+-----------+--------------+-----------+
```
对于上面的例子，则正确的输出是为：
```
+---------+-----------+--------------+-----------+
|   id    | movie     |  description |  rating   |
+---------+-----------+--------------+-----------+
|   5     | House card|   Interesting|   9.1     |
|   1     | War       |   great 3D   |   8.9     |
+---------+-----------+--------------+-----------+
```
## 题解

选取不为“boring”且id为奇数的记录，按`DESC`排序。

```sql
select * from cinema where description<>'boring' and id%2=1 order by rating DESC
```

# 627. 交换工资
## 题目
给定一个`salary`表，如下所示，有m=男性 和 f=女性的值 。交换所有的 f 和 m 值(例如，将所有 f 值更改为 m，反之亦然)。要求使用一个更新查询，并且没有中间临时表。

例如:
```
| id | name | sex | salary |
|----|------|-----|--------|
| 1  | A    | m   | 2500   |
| 2  | B    | f   | 1500   |
| 3  | C    | m   | 5500   |
| 4  | D    | f   | 500    |
```
运行你所编写的查询语句之后，将会得到以下表:
```
| id | name | sex | salary |
|----|------|-----|--------|
| 1  | A    | f   | 2500   |
| 2  | B    | m   | 1500   |
| 3  | C    | f   | 5500   |
| 4  | D    | m   | 500    |
```
## 题解
更新数据库，条件更新，m赋值f，f赋值m
```sql
update salary set sex=case when sex='f' then 'm' else 'f' end
```



# 
## 题目
## 题解