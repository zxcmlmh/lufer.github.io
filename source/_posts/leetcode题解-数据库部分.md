---
title: leetcode题解-数据库部分
date: 2018-06-29 18:27:39
tags: [LeetCode]
categories: 算法
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
# 176. 第二高的薪水
## 题目
编写一个 SQL 查询，获取 `Employee` 表中第二高的薪水（Salary） 。
```
+----+--------+
| Id | Salary |
+----+--------+
| 1  | 100    |
| 2  | 200    |
| 3  | 300    |
+----+--------+
```
例如上述 `Employee` 表，SQL查询应该返回 `200` 作为第二高的薪水。如果不存在第二高的薪水，那么查询应返回 null。
```
+---------------------+
| SecondHighestSalary |
+---------------------+
| 200                 |
+---------------------+
```
## 题解
把薪水按从大到小排序，然后偏移1个取第一个，如果没有就返回空（IFNULL）  
还要把salary groupby一下来去重
```sql
Select IFNULL((SELECT Salary FROM Employee group by Salary order by Salary desc limit 1 offset 1),null) as SecondHighestSalary
```
# 177. 第N高的薪水
## 题目
编写一个 SQL 查询，获取 `Employee` 表中第 n 高的薪水（Salary）。
```
+----+--------+
| Id | Salary |
+----+--------+
| 1  | 100    |
| 2  | 200    |
| 3  | 300    |
+----+--------+
```
例如上述 `Employee` 表，n = 2 时，应返回第二高的薪水 `200`。如果不存在第 n 高的薪水，那么查询应返回 `null`。
```
+------------------------+
| getNthHighestSalary(2) |
+------------------------+
| 200                    |
+------------------------+
```
## 题解
第N高只需要偏移量设定为N-1即可，定义变量m=N-1进行计算
```sql
CREATE FUNCTION getNthHighestSalary(N INT) RETURNS INT
BEGIN
declare m int;
set m=N-1;
  RETURN (
      # Write your MySQL query statement below.
      Select IFNULL((SELECT Salary FROM Employee group by Salary order by Salary desc limit 1 offset m),null)
  );
END
```

# 178. 分数排名
## 题目
编写一个 SQL 查询来实现分数排名。如果两个分数相同，则两个分数排名（Rank）相同。请注意，平分后的下一个名次应该是下一个连续的整数值。换句话说，名次之间不应该有“间隔”。
```
+----+-------+
| Id | Score |
+----+-------+
| 1  | 3.50  |
| 2  | 3.65  |
| 3  | 4.00  |
| 4  | 3.85  |
| 5  | 4.00  |
| 6  | 3.65  |
+----+-------+
```
例如，根据上述给定的 `Scores` 表，你的查询应该返回（按分数从高到低排列）：
```
+-------+------+
| Score | Rank |
+-------+------+
| 4.00  | 1    |
| 4.00  | 1    |
| 3.85  | 2    |
| 3.65  | 3    |
| 3.65  | 3    |
| 3.50  | 4    |
+-------+------+
```
## 题解
先把人按成绩排个序，然后顺次select并保存上一次的分数，如果分数一样则给一样的排名，不一样了排名就+1。  
pre_score要初始化为null，我一开始初始化成0结果雪崩，测试样例里面有成绩为0的23333333
```sql
select Score,cast(Rank as signed) as Rank from(
select tmp.score as Score,@k:=(case when @pre_score=tmp.score then @k else @k:=@k+1 end) as Rank,@pre_score:=tmp.score as pre_score
from 
(select * from Scores order by Score desc) tmp,(select @k :=0, @pre_score:=null) initialise
) temp
```
# 180. 连续出现的数字
## 题目
编写一个 SQL 查询，查找所有至少连续出现三次的数字。
```
+----+-----+
| Id | Num |
+----+-----+
| 1  |  1  |
| 2  |  1  |
| 3  |  1  |
| 4  |  2  |
| 5  |  1  |
| 6  |  2  |
| 7  |  2  |
+----+-----+
```
例如，给定上面的 Logs 表， 1 是唯一连续出现至少三次的数字。
```
+-----------------+
| ConsecutiveNums |
+-----------------+
| 1               |
+-----------------+
```
## 题解
和178类似，只不过这次我们不排序了，直接select，然后通过维护一个times列来标记出现的次数，和上一次相同就+1，不同就回到1。   
最后统计times列大于2的数，再去个重就可以了。
```sql
select distinct Num as ConsecutiveNums from (
select tmp.ID,tmp.Num,@k:=(case when @pre_num=tmp.num then @k:=@k+1 else @k:=1 end) as Times,@pre_num:=tmp.num as pre_num
from 
(select * from Logs order by Id) tmp,(select @k :=1, @pre_num:=null) initialise
) tmp where Times>2
```
# 181. 超过经理收入的员工
## 题目
`Employee` 表包含所有员工，他们的经理也属于员工。每个员工都有一个 Id，此外还有一列对应员工的经理的 Id。
```
+----+-------+--------+-----------+
| Id | Name  | Salary | ManagerId |
+----+-------+--------+-----------+
| 1  | Joe   | 70000  | 3         |
| 2  | Henry | 80000  | 4         |
| 3  | Sam   | 60000  | NULL      |
| 4  | Max   | 90000  | NULL      |
+----+-------+--------+-----------+
```
给定 `Employee` 表，编写一个 SQL 查询，该查询可以获取收入超过他们经理的员工的姓名。在上面的表格中，Joe 是唯一一个收入超过他的经理的员工。
```
+----------+
| Employee |
+----------+
| Joe      |
+----------+
```
## 题解
把Employee表查询两次互相对比，把ID和managerID对应之后比较salary即可
```sql
SELECT A.name as Employee FROM employee as A,employee as B where A.ManagerID=B.Id and A.Salary>B.Salary
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


# 183. 从不订购的客户
## 题目
某网站包含两个表，`Customers` 表和 `Orders` 表。编写一个 SQL 查询，找出所有从不订购任何东西的客户。

`Customers` 表：
```
+----+-------+
| Id | Name  |
+----+-------+
| 1  | Joe   |
| 2  | Henry |
| 3  | Sam   |
| 4  | Max   |
+----+-------+
```
`Orders` 表：
```
+----+------------+
| Id | CustomerId |
+----+------------+
| 1  | 3          |
| 2  | 1          |
+----+------------+
```
例如给定上述表格，你的查询应返回：
```
+-----------+
| Customers |
+-----------+
| Henry     |
| Max       |
+-----------+
```
## 题解
直接NOT IN
```sql
select name as Customers from Customers where Customers.id NOT IN(select CustomerId from Orders)
```
# 184. 部门工资最高的员工
## 题目
`Employee` 表包含所有员工信息，每个员工有其对应的 Id, salary 和 department Id。
```
+----+-------+--------+--------------+
| Id | Name  | Salary | DepartmentId |
+----+-------+--------+--------------+
| 1  | Joe   | 70000  | 1            |
| 2  | Henry | 80000  | 2            |
| 3  | Sam   | 60000  | 2            |
| 4  | Max   | 90000  | 1            |
+----+-------+--------+--------------+
```
`Department`表包含公司所有部门的信息。
```
+----+----------+
| Id | Name     |
+----+----------+
| 1  | IT       |
| 2  | Sales    |
+----+----------+
```
编写一个 SQL 查询，找出每个部门工资最高的员工。例如，根据上述给定的表格，Max 在 IT 部门有最高工资，Henry 在 Sales 部门有最高工资。
```
+------------+----------+--------+
| Department | Employee | Salary |
+------------+----------+--------+
| IT         | Max      | 90000  |
| Sales      | Henry    | 80000  |
+------------+----------+--------+
```
## 题解
GroupBy部门之后获取Max Salary和对应的DepartmentID，然后查表获取ID相同薪水相同的兄弟，再加上其他信息就可以了。
```sql
select department.name as Department,B.Name as Employee,B.Salary from(SELECT DepartmentId,Max(salary) as salary FROM employee group by DepartmentId) A,employee B,Department where A.DepartmentId=B.DepartmentId and A.Salary=B.salary and B.DepartmentId=Department.Id
```

# 196. 删除重复的电子邮箱
## 题目
编写一个 SQL 查询，来删除 `Person` 表中所有重复的电子邮箱，重复的邮箱里只保留 Id 最小 的那个。
```
+----+------------------+
| Id | Email            |
+----+------------------+
| 1  | john@example.com |
| 2  | bob@example.com  |
| 3  | john@example.com |
+----+------------------+
Id 是这个表的主键。
```
例如，在运行你的查询语句之后，上面的 `Person` 表应返回以下几行:
```
+----+------------------+
| Id | Email            |
+----+------------------+
| 1  | john@example.com |
| 2  | bob@example.com  |
+----+------------------+
```
## 题解
先把email groupby来获得重复数量和最小ID，然后找数量大于1并且Not IN最小ID列表的全部删除
```sql
delete from person where
Email in (select distinct Email from ( select Email from person group by Email having count(Email)>1) a)
and Id not in ( select Id from (select min(Id) as Id from person group by Email having count(Email)>1 ) b) 
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
# 626. 换座位
## 题目
小美是一所中学的信息科技老师，她有一张 `seat` 座位表，平时用来储存学生名字和与他们相对应的座位 id。

其中纵列的 id 是连续递增的

小美想改变相邻俩学生的座位。

你能不能帮她写一个 SQL query 来输出小美想要的结果呢？

示例：
```
+---------+---------+
|    id   | student |
+---------+---------+
|    1    | Abbot   |
|    2    | Doris   |
|    3    | Emerson |
|    4    | Green   |
|    5    | Jeames  |
+---------+---------+
```
假如数据输入的是上表，则输出结果如下：
```
+---------+---------+
|    id   | student |
+---------+---------+
|    1    | Doris   |
|    2    | Abbot   |
|    3    | Green   |
|    4    | Emerson |
|    5    | Jeames  |
+---------+---------+
```
注意：

如果学生人数是奇数，则不需要改变最后一个同学的座位。

## 题解
我一开始写了个update，然后没输出，update之后select，直接报错，这玩意只能一句query来实现。

三步走，奇数换偶数名字，偶数换奇数名字，多一个的话直接输出，最后排个序即可。
```sql
select * from 
(
select A.id,B.student from seat as A,seat as B where B.id%2=0 and A.id=B.id-1 and B.id>1
union
select B.id,A.student from seat as A,seat as B where B.id%2=0 and A.id=B.id-1 and B.id>1
union
select id,student from seat where id%2=1 and id=(select max(id) from seat)
) b order by id
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