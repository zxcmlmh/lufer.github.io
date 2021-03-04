---
title: C++大作业：异质树的实现
categories: C++
date: 2017-12-28 00:59:25
tags: [C++]
---

&emsp;&emsp;设计了基类是Person，派生类是Teacher，Student的三个类，并构造一棵树，代码还有点问题在删除节点的时候，删除节点之后子节点如何并到上层节点，当时时间比较紧迫没有细弄就直接全部删除了。

```cs
#include #include #include #include using namespace std;
class Person
{
    public:
        Person *child\[10\];
        int children;
        Person(int id,string name);
        virtual void setjob();
        string getjob();
        void setid(int id);
        int getid();
        void addChild(Person *childPerson);
        int id;
        string Job;
        string Name;
};
Person::Person(int id,string name)
{
    this->id=id;
    this->Name=name;
    cout<<"Successfully new object,Id is:"<id=id;
}
void Person::setjob()
{
    Job="Person";
}
void Person::addChild(Person* childPerson)
{

    if(children==10)
        cout<<"Error,ChildNum limit exceeded!"<id==id&&root->Job==job)
    {
        root=NULL;
        return 1;
    }
    else
    {
        int i;
        for(i=0;ichildren;i++)
        {
            if(root->child\[i\]->id==id&&root->child\[i\]->Job==job)
            {
                root->child\[i\]=NULL;
                return 1;
            }
        }
        for(i=0;ichildren;i++)
        {
            int j=deletehelper(root->child\[i\],id,job);
            if(j==1)
                return 1;
        }
        return 0;
    }
}
void deletePerson(Person* root,int id,string job)
{
    int i=deletehelper(root,id,job);
    if(i==1)
    {
        cout<<"Successfully deleted"<children;i++)
    {
        Person *node=root->child\[i\];
		if (node != NULL)
		{
			cout << "Id is:" << node->id << endl;
			cout << "Job is:" << node->Job << endl;
			cout << "Name is:" << node->Name << endl;
			traversalTree(node);
		}
    }
}
void findPerson(Person *root,int id,string job)
{
    int i;
    if(root->id==id&&root->Job==job)
        {
            cout<<"Successfully Find Person whose id is:"<id<<" and Job is:"<Job<<" Name is:"<Name<children;i++)
            {
                Person *node=root->child\[i\];
                findPerson(node,id,job);
            }
        }

}
class Teacher:public Person
{
    public:
        void setjob();
        Teacher(int id,string name);

};
Teacher::Teacher(int id,string name):Person(id,name)
{
    setjob();
}
void Teacher::setjob()
{
    Job="Teacher";
}
class Student:public Person
{
    public:
        Student(int id,string name);
        void setjob();

};
Student::Student(int id,string name):Person(id,name)
{
    setjob();
}
void Student::setjob()
{
    Job="Student";
}
int main()
{
    //printf("Hello World!/n");
    Person* root=new Person(1,"Zhangsan");
    Person* root_child1=new Person(2,"Lisi");
    Person* root_child2=new Teacher(3,"Wangwu");
    Person* root\_child1\_child1=new Teacher(4,"Zhaoyi");
    Person* root\_child1\_child2=new Student(5,"Qianer");
    Person* root\_child2\_child1=new Student(6,"Sunliu");
    Person* root\_child2\_child2=new Teacher(7,"Zhouqi");
    root->addChild(root_child1);
    root->addChild(root_child2);
    root\_child1->addChild(root\_child1_child1);
    root\_child1->addChild(root\_child1_child2);
    root\_child2->addChild(root\_child2_child1);
    root\_child2->addChild(root\_child2_child2);
    findPerson(root,3,"Teacher");
    findPerson(root,6,"Student");
    traversalTree(root);
    deletePerson(root,7,"Teacher");
    traversalTree(root);
	system("pause");
    return 0;
}
```