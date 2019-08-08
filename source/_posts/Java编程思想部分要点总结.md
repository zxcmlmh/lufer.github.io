---
title: Java编程思想部分要点总结
date: 2019-07-31 13:34:31
categories: Java
tags: [Java,后端]
---
# 第一章&nbsp;&nbsp;对象导论
### 1.面向对象语言的五个特性
1. 万物皆为对象
2. 程序是对象的集合
3. 每个对象都有由其他对象所构成的存储
4. 每个对象都有其类型
5. 某一特定类型的对象都可以接受同种类型的消息

### 2.前期绑定与后期绑定
前期绑定：编译器产生对一个具体函数名字的调用，运行时将这个调用解析到将要被执行的绝对地址  
后期绑定：编译器确保被调用方法的存在，并对参数和返回值执行类型检查，被调用的代码直到运行时才确定。

# 第二章&nbsp;&nbsp;一切都是对象

### 1.对象的内存存储
1. 寄存器
2. 堆栈：存放对象引用
3. 堆：存放所有Java对象
4. 常量：常量值通常直接存放在程序代码内部
5. 非RAM存储：例如流对象和持久化对象
(对于基本类型，存放在堆栈中)

### 2.基本类型与包装类型
|基本类型|大小|包装器类型|
|---|---|---|
|boolean|-|Boolean|
|char|16bit|Character|
|byte|8bit|Byte|
|short|16bit|Short|
|int|32bit|Integer|
|long|64bit|Long|
|float|32bit|Float|
|double|64bit|Double|
|void|-|Void|
### 3.高精度计算类
1. BigInteger：可表示任何大小整数
2. BigDecimal：可表示任何精度浮点数

### 4.变量初始化
没有初始化的数组会被指向null，引用null会抛异常。  
没有初始化的局部变量会导致编译错误。  
没有初始化的成员变量会被自动初始化。  
### 5.Static关键字
使用Static的两种原因：  
1. 只想为特定域分配单一存储空间
2. 希望某个方法不与包含它的类的任何对象关联在一起

# 第五章&nbsp;&nbsp;初始化与清理
## 一、初始化
### 1.构造器初始化顺序
在类的内部，变量定义的先后顺序决定了初始化的顺序，但他们依旧会在任何方法被调用之前得到初始化。  
先初始化静态对象，然后才是非静态对象。
如果类没有定义`toString`方法，`print`将会打印"类名@对象地址"
## 二、垃圾清理
### 1.GC技术原理
1. 引用计数  
每个对象都有一个计数器，当有引用连接至对象时，引用计数+1，当引用离开作用域或者被置为null时，引用计数-1。  
引用计数通常在数值为0时立即回收，但是如果对象间存在循环引用，则可能会出现“应该被回收，但计数不为0”的情况。
(这种方法常用来说明GC的工作方式，但是没有JVM使用这种方式)  
2. 停止-复制  
暂停程序运行，将所有存活对象从当前堆复制到另一个堆，没有被复制的就全部是垃圾，当复制到新堆时，变量排列紧凑，所以可以直接分配新空间。  
“按需从堆中分配几块较大的内存，复制动作发生在内存之间“
3. 标记-清扫  
从堆栈和静态区出发，遍历所有的引用，并对对象进行标记，在全部标记工作完成后，将没有标记的对象回收。  
同样也需要暂停程序。

# 第六章&nbsp;&nbsp;访问权限控制
## 一、访问权限
### 1. 包访问权限
没有设置访问权限的默认为包访问权限，包访问权限中同一包内的所有类成员可互相访问，但对包外的类而言则是Private的。  
如果两个类处在同一目录下，并且没有设置任何包名称，Java会认为这些类处于该目录下的默认包中。
### 2.Public
一个编译单元只能有一个Public类，并且类名需要与文件名完全一致
### 3.Private
用Private修饰构造器，在其他方法中return一个新对象，这样可以阻止对构造器的直接访问。
### 4.Protected
被Protected修饰的成员可被派生类访问，同时还提供包访问权限。
# 第七章&nbsp;&nbsp;复用类（继承）
### 1. 初始化
总是先初始化基类，再初始化派生类，即使没有基类对象，也要初始化基类。  
基类构造器总是会被调用。  
如要调用带参的基类构造器，需要调用`super()`
### 2. @Override
该注解没有什么额外功能，只是说明该方法要进行Override，如果使用了该注解但没有进行Override，会触发编译错误。
### 3.组合与继承
1. 组合用于想在新类中使用现有类的功能。
2. 继承用于使用某个现有类，并基于它开发一个特殊版本。

### 4.Final
用Final定义的基础变量类型无法被改变。  
用Final定义的对象无法改变其引用指向，但是可以修改它引用的对象的值。  
可以创建不赋值的空白Final，但是必须保证使用前被初始化。  
方法中被Final修饰的参数只能读不能写。 
用Final修饰的类无法更改，无法继承。   
Final的意义：把方法锁定，以防任何继承类修改。  
# 第八章&nbsp;&nbsp;多态
### 1.基类与派生类
1. 基类为其所有派生类建立公共接口，但其派生类向上转型由基类引用后，仍可找到其override的接口。  
但是只有非Private方法可以被覆盖，因为Private对派生类不可见，派生类中的方法相当于新方法。  
2. 派生类的构造器必然会调用基类的构造器，如果没有显式调用，则会自动调用基类默认构造器，如果没有默认构造器则会编译错误。  
如果需要手动清理对象，则必须在派生类中覆盖清理方法，并调用基类的清理方法，销毁顺序与初始化顺序相反。

### 2.初始化过程
1. 先给各对象分配存储空间，并初始化为二进制的0。  
2. 调用基类构造器
3. 初始化主体
4. 调用派生类构造器

### 3.协变返回类型
在派生类中被覆盖的方法可以返回基类的返回类型的某种派生类型。

# 第九章&nbsp;&nbsp;接口
### 1.Abstract抽象类
如果一个类包含一个或多个抽象方法，则必须被限定为抽象类，但抽象类可以没有抽象方法。  
抽象类无法产生对象，继承自抽象类的类如果没有完全实现其抽象方法，则也必须用abstract修饰。 
### 2.Interface接口
用Interface修饰的接口完全由抽象方法组成，不提供任何实现。  
接口中的任何域都自动是static和final的。  
接口中的方法默认为Public，所以实现接口时也必须定义为Public。  
### 3.多重继承
Java是单继承，但是一个类可以继承自一个基类，同时实现多个接口。
### 4.接口扩展
接口可以继承，通过继承可以在接口中添加新方法，从而扩展接口。
# 第十章&nbsp;&nbsp;内部类
### 1.内部类
在类的内部定义另一个类，内部类拥有其外围类的所有元素的访问权。
### 2.this与new
用`OuterClass.this`可以获得一个外部类对象的引用。  
用`OuterClassObject.new`可以使其创建一个内部类对象。
### 3.匿名内部类
```Java
public Contents contents(){
    return new Contents()
    {
        private int i=1;
        ......
    }
}
```
### 4.嵌套类
将内部类用static修饰
1. 要创建嵌套类的对象，并不需要其外围类的对象。
2. 不能从嵌套的对象中访问非静态的外围对象。

借助嵌套类，可以在接口中实现一些代码，甚至可以实现外围接口。
# 第十一章&nbsp;&nbsp;持有对象
## 一、容器类
1. Collection：
    List/Set/Queue
2. Map

## 二、迭代器
### 1.Iterator
使用iterator()方法返回一个Iterator，包含`next()/hasNext()/remove()`方法。  
迭代器不关心容器的类型，只关心容器中的变量类型。
### 2.ListIterator
只能用于List容器，但是可以双向移动。  
包含`next()/previous()/set()`等方法
### 三、Set
Set就是Collection的一种实现，接口完全一样。
1. HashSet使用散列结构
2. TreeSet使用红黑树
3. LinkedHashList使用散列，但看起来像是使用了链表。
4. TreeSet中保存的内容是有序的

用`contains()`可以判断Set是否包含元素。
### 四、Map
将容器组合起来可以获得多为扩展，例如`Map<Int,List<>>`。
### 五、Queue
PriorityQueue可以按优先级排序，通过构建Comparator来实现优先级比较。

# 第十二章&nbsp;&nbsp;异常处理
用Try包裹可能产生异常的语句块，用Catch进行捕获。  
仅搜索第一个匹配的Catch块，Catch执行后即认为异常得到处理，不会再匹配其他符合要求的Catch，即便再次抛出，也只会抛给上层处理，不会再被Catch捕获。

### 1.异常处理模型
1. 终止模型：抛出异常，终止代码。
2. 恢复模型：异常被处理后继续执行。

### 2.自定义异常
可以通过继承自异常类来创建自定义类型的异常。
### 3.Finally
由于Java不需要进行内存回收，也不需要析构，所以Finally中主要负责将除内存之外的其他资源恢复到初始状态。
# 第十四章&nbsp;&nbsp;类型信息
## 一、ClassLoader
Java的Class是动态加载的，各个部分在必需时才会被加载。   
先检查类是否加载，若尚未加载，默认的类加载器根据类名查找Class。  
在加载时会验证类有没有被破坏，并且是否包含不良代码。  
实际工作：
1. 加载：由类加载器执行，查找字节码，并从字节码创建class对象。
2. 链接：验证字节码，为静态域分配空间，如果必须的话，解析这个类创建的对其他类的所有引用。
3. 初始化：如果类具有父类，则先对父类进行初始化，执行静态初始化器和静态块。

## 二、RTTI
### 1.主要形式
1. 传统的类型转换/强制类型转换，由RTTI保证类型的正确性。
2. 代表对象类型的class对象，通过查询class对象来获取运行时所需信息。
3. InstanceOf返回布尔值，判断对象类型。

用InstanceOf判断的是是否为该类或者其派生类，用类名.class搭配==或者equals()只能判断是否为该类。
## 三、反射
检查可用的方法，并返回方法名。  
RTTI是在编译时打开和检查class文件，而反射是在运行时打开和检查class文件。
# 第十五章&nbsp;&nbsp;泛型
## 一、简单泛型
用<>括住类型参数跟在类名后，括号内可以为多元组。
```Java
Class ClassName<T>() {}
Class ClassName<A,B,C>() {}
```
不能使用基本类型作为参数，需要使用包装类型。  
如果使用泛型方法可以取代将整个类泛型化，那就应该只使用泛型方法。
## 二、擦除
在泛型内部，无法获得任何有关泛型参数类型的信息，任何具体信息都将被消除，但是擦除只会擦除到第一个边界，
### 1.边界
```Java
<T extends ClassA&ClassB> 
```
### 2.通配符
`List<? extends BaseClass>`  
超类型通配符：`List<? super SuperClass>`   
无界通配符：`List<?>`
## 三、问题
1. 基本类型不能用作类型参数，要使用包装器。
2. 一个类不能实现同一泛型接口的两种变体，因为会被擦除。
3. 使用带有泛型类型参数的转型或InstanceOf不会有任何结果。
4. 由于擦除，不能产生唯一的参数列表，所以不能重载。
5. 基类会劫持接口，使得实现接口后就不能再接受其他类。

# 第十七章&nbsp;&nbsp;容器深入研究
![](https://s2.ax1x.com/2019/08/08/eTh5zF.gif)
## 一、SET
如果没有其他限制，则应该使用HashSet，因为HashSet在速度上进行了优化。  
必须为类创建equals方法，在使用HashSet或LinkedHashSet时还要实现hasCode方法。  
SortedSet：按比较函数排序的Set  
LinkedHashSet：按插入顺序排序的Set
## 二、Queue
两种实现(LinkedList/PriorityQueue)的差异在于排序行为而不在于性能。  
LinkedList包含支持双向队列的方法，但是没有显式的接口。
## 三、Map
1. HashMap  
2. LinkedHashMap：按插入顺序排序  
3. TreeMap：基于红黑树，有序排列，比HashMap慢  
4. WeakHashMap：当没有引用指向某个Key时，该Key可以被回收  
5. ConcurrentHashMap：线程安全的Map，但是没有同步锁  
6. IdentityHashMap：内部用==代替了equals，所以具有完全不同的性能

## 四、散列码
步骤：  
1. 对对象进行散列，得到一个值
2. 将该值作为数组下标
3. 在该下表下的数组元素存在值得List
4. 查找时根据下标找到List，再在List中进行线性查找

散列码的生成必须要块，而且有意义，必须基于对象内容生成，保证对于同样的内容生成的散列码相同，但是不需要独一无二，不同内容也可生成相同的散列码，好的散列函数应该生成相对较为分散的散列码。

## 五、性能比较
### 1.List
ArrayList在数据量大的时候访问比较快，但是插入新数据会比较慢。  
LinkedList插入删除较快。
### 2.Map
HashMap与HashTable速度相当，都比较快。  
LinkedHashMap会慢一点，因为在插入时还要维护顺序。
## 六、持有引用
用Reference对象可以使我们持有该对象，但是在需要GC时回收该对象，在存在可能会耗尽内存的大对象时特别有用。
# 第十八章&nbsp;&nbsp;Java I/O
### 1.文件加锁
对FileChannel调用trylock或lock可以对文件加锁。  
文件锁对其他进程是可见的，因为Java的文件锁直接映射在系统加锁工具上。  
1. TryLock：非阻塞式加锁，若不能加锁则返回。
2. Lock：阻塞式加锁，会阻塞进程直至锁可以获得，或者调用Lock的线程中断/通道关闭。

`Filelock.release()`可以释放锁。  
trylock或lock可以带参数来指定加锁范围，只会锁定固定区域，不带参的锁将根据文件尺寸变化而变化。
### 2.锁的类型
1. 独占锁：锁定的资源只允许加锁的程序使用。
2. 共享锁：锁定的资源可被其他程序读取，但不能更改。

锁的类型由操作系统底层提供，如果不支持共享锁，调用时会返回独占锁。  
锁的类型可以通过`Filelock.isShared()`查询。
### 3.对象序列化
对于实现了Seriallized接口的对象，可以使用writeObject将其序列化，序列化可以将对象的关系网全部保存，可以借助readObject读取。  

序列化的意义：
1. 支持远程方法调用(RMI),可以在本机上使用远程计算机上的对象。
2. 支持JavaBeans。

### 4.控制序列化
让类实现接口Externalizable，并实现writeExternal和readExternal方法，但该类的默认构造器需为public，因为所有的默认构造器都会被调用
### 5.Transient
被transient修饰的变量在被序列化时不会被处理，可以防止敏感部分被序列化。
# 第二十章&nbsp;&nbsp;注解
## 一、定义注解
```Java
//定义注解用在什么地方
@Target(ElementType.CONSTRUCTOR)     //构造器
        ElementType.FIELD            //域
        ElementType.LOCAL_VARIABLE   //局部变量
        ElementType.METHOD           //方法
        ElementType.PACKAGE          //包
        ElementType.PARAMETER        //参数
        ElementType.TYPE             //类，接口
//定义在什么级别保存注解
@Retention(RetentionPolicy.SOURCE)   //源码级别，被编译器丢弃
           RetentionPolicy.CLASS     //class文件级别，被VM丢弃
           RetentionPolicy.RUNTIME   //运行时级别，可通过反射读取
//注解会被包含在JavaDoc中
@Documented
//子类可继承父类注解
@Inherited
public @interface Test() {};
```
## 二、实现注解处理器
通过`method.getAnnotation(ClassName.class)`来判断方法上是否有指定类型的注解，并进行后续处理
# 第二十一章&nbsp;&nbsp;并发
## 一、任务定义与执行
### 1.定义任务
实现Runable接口并编写run()方法，并在run中实现所需业务操作。
### 2.Thread类
```Java
Thread t=new Thread(new ClassA());
t.start();
```
为Thread构造器传入一个Runable对象，再调用Thread的`start()`方法，此时会创建一个新线程。
### 3.Executor
```Java
ExecutorService exec=Executors.newCachedThreadPool();
exec.execute(new ClassA());
exec.shutdown();
```
通过`exec.execute`提交任务，`exec.shutdown`可以防止新任务被提交，但是已提交的任务会继续执行，将在所有任务完成后退出。
```Java
//Executors在线程有可能的情况下都会被复用
Executors.newCachedThreadPool()
Executors.newFixedThreadPool(Count)  //数量有限的线程池，一次完成所有线程创建
Executors.newSingleThreadPool()      //只有1个线程，若提交多个任务会排队执行
```
## 二、任务回调
### 1.可回调的任务
Runable只可执行，不能回调。  
需要实现Callable接口，类型参数为call()方法的返回值。
### 2.任务调用
须用`exec.submit(new ClassA())`来调用，submit方法会返回Future对象，Future是泛型的，其参数类型是回调函数返回值的类型。  
可以用isDone()方法来查询Future是否完成，任务完成时，会产生结果，可用get()方法进行获取，如果在未完成时即调用get()方法，则会一致阻塞到有结果产生。
## 三、任务控制
### 1.休眠
```Java
Thread.sleep()                  (老方式)  
TimeUnit.MILLISECONDS.sleep()   (Java SE5/6)
```
### 2.优先级
在任务内部，调用`Thread.currentThread().getPriority()`来获取优先级。调用`Thread.currentThread().setPriority()`来修改优先级。

建议的优先级：
1. MAX_PRIORITY
2. NORMAL_PRIORITY
3. MIN_PRIORITY

### 3.让步
使用yield方法可以暗示建议其他具有相同优先级的线程先运行，但是不保证效果。
### 4.后台线程
在`Thread.start()`之前先`Thread.setDaemo(true)`将线程设置为后台线程，在程序的非后台线程结束时，程序会中指运行，并杀死所有的后台线程。
### 5.异常处理
无法直接用try-catch从线程中捕获异常，但是可以在每一个Thread对象上附着一个异常处理器。
```Java
Thread t=new Thread();
t.setUncaughtExceptionHandler(new MyUncaughtExceptionHandler())

//MyUncaughtExceptionHandler实现接口与方法
Class MyUncaughtExceptionHandler implements Thread.UncaughtExceptionHandler{
    public void uncaughtException(Thread t,Throwable e)
    {
        ......
    }
}
```
## 四、资源共享
### 1.加锁
用`synchronized`修饰的方法或代码片段，会在执行时检查->获取锁->执行->释放锁。  
一个对象中的所有同步方法共享一个锁，若某个任务调用了其中一个加锁方法，则只有等其结束并释放后，其他任务才能调用其中的任何一个同步方法。
### 2.原子性
Java中，所有出了long和double以外的基本类型进行读写都是原子性的，但JVM对于64位的long和double会拆分成两个32位的操作，破坏原子性。  
可以用volatile修饰long和double来获取原子性简单操作，此外，Java还提供AtomicLong，AtomicInteger，AtomicReference等原子类。
### 3.临界区
可用`synchronized(ObjectA){ 代码块 }`来对ObjectA的某个代码块加同步锁，在进入该代码块之前必须获得该对象的锁。
### 4.线程本地存储
使用`ThreadLocal`创建本地存储的副本，用`get()`可以获得与该线程关联的副本，用`set()`可以将参数写入线程的存储对象中。
## 五、线程中止
### 1.线程的状态
1. 新建(new):创建进程时会短暂处于该状态，此时已分配了必须的系统资源，并执行了初始化，之后会变为可运行/阻塞状态。
2. 就绪(Runable):此时只要调度器把时间片分配给线程，线程就可以运行，运行不运行完全取决于是否获得CPU时间。
3. 阻塞(Blocked):线程虽能运行，但有条件阻止其运行。调度器不会分配时间，直至线程重新回到就绪状态。
4. 死亡(Dead):不可调度，也不会获得时间，通常从run()中返回，但可被中断。

### 2.进入阻塞
1. 调用`sleep()`
2. 调用`wait()`，直到线程得到`notify()/notifyAll()/signal()/signalAll()`才会进入就绪状态
3. 等待某个输入/输出完成
4. 任务试图在对象上调用其同步方法，但是无法获取对象锁

### 3.中断
Thread类包含`interrupt()`方法，可以打断被阻塞的方法。   
## 六、线程协作
### 1.wait与notify
sleep与yield并不会释放锁，只有wait才会使线程挂起，并释放锁，其他synchronized方法才能继续调用。

wait有两种形式:
1. 带毫秒参数，在时间到期后会恢复执行
2. 不带参，将会无限等待

该方法是基类Object的一部分，而不属于Thread，所以可放在任何同步方法中。

notify()会唤醒一个任务，notifyAll()会唤醒在等待某个特定锁的任务。

### 2.await与signal
`Condition.await()`挂起任务，调用signal或signalAll可以唤醒Condition上被其自身挂起的任务。
### 3.同步队列
向同步队列中插入或移除元素，如果队列为空但试图从队列中获取对象，就会被挂起，直到队列中有对象可用。   

LinkedBlockingQueue是无届队列   
ArrayBlockingQueue有固定尺寸

高级封装：管道(PipedWriter/PipedReader)

写入类实现一个PipedWriter对象，然后`writer.write()`
读取类要先获取写入类的writer，并以此为参数构建Reader，再调用`reader.read()`
## 七、死锁
### 1.死锁条件(需同时满足)
1. 任务互斥，两个任务请求同一个不能共享的资源
2. 一个任务已持有一个资源，并在等待一个被其他任务占有的资源
3. 资源不能被抢占，必须等待释放
4. 存在循环等待

## 八、类库
### 1.CountDownLatch
构造时指定一个计数值，该值通过调用`countDown()`来递减1，在调用await()后对象会被阻塞，当计数器到0之后会恢复运行。
### 2.CyclicBarrier
与CountDownLatch累死，但是CountDownLatch是一个线程在等待多个线程，而CyclicBarrier是多个线程await之后都在等待计数器，到0之后会一起恢复运行。
### 3.DelayQueue
一个无界的BlockingQueue，内部放置的是实现了Delayed接口的对象，对象有一个到期时间，在到期之后才可以被取走。  
该队列是有序的，队首元素是到期时间最长的元素，如果没有对象到期，试图poll会返回null
### 4.PriorityBlockingQueue
线程安全的优先级队列，总是先执行优先级最高的任务
### 5.Semaphore
技术信号量(有点类似池的概念)，管理有限的对象。
### 6.Exchanger
双向同步队列，其中一个线程被调用时会唤醒另一个调用此方法的线程，两者交换数据