---
title: CYC推荐LeetCode试题题解与总结
date: 2019-08-12 19:38:54
tags: [Java,算法,数据结构]
categories: [Java]
---
本篇文章主要记录来自CYC所推荐的200+LeetCode经典题目解题思路与题解。

https://github.com/CyC2018/CS-Notes/blob/master/notes/Leetcode%20%E9%A2%98%E8%A7%A3%20-%20%E7%9B%AE%E5%BD%95.md

# 第一部分 算法思想
## 一、双指针
### 1. 有序数组的 Two Sum

https://leetcode-cn.com/problems/two-sum-ii-input-array-is-sorted/

#### 思路
因为数组是有序的，所以用双指针首尾想加，结果偏大则尾部向前移动，结果偏小则头部向后移动。
#### 代码
```Java
class Solution {
    public int[] twoSum(int[] numbers, int target) {
        int[] res=new int[2];
        for(int i=0,j=numbers.length-1;i<j;)
        {
            int temp=numbers[i]+numbers[j];
            if(temp==target)
            {
                res[0]=i+1;
                res[1]=j+1;
                break;
            }
            else
                if(temp<target)
                    i++;
            else
                j--;
        }
        return res;
    }
}
```
### 2.两数平方和

https://leetcode-cn.com/problems/sum-of-square-numbers/

#### 思路
如果一个数可以被两个数的平方和表示，那么这两个数一定都小于这个数的平方根，故可先求平方根，然后同样双指针前后夹逼。
#### 代码
```Java
class Solution {
    public boolean judgeSquareSum(int c) {
        int left=0;
        int right=(int)Math.sqrt(c);
        for(;left<=right;)
        {
            int temp=left*left+right*right;
            if(temp==c)
                return true;
            else
                if(temp>c)
                    right--;
             
            else
                left++;
        }
        return false;
    }
}
```
### 3.反转字符串中的元音字符

https://leetcode-cn.com/problems/reverse-vowels-of-a-string/submissions/

#### 思路
双指针前后寻找，找到元音后停止，当两个指针都停止时，交换字符，然后继续移动。
#### 代码
代码感觉写的比较繁琐，有待优化
```Java
class Solution {
    public String reverseVowels(String s) {
        if (s!=null && s.length()!=0)
        {
            char[] ch=new char[s.length()];
            String letter="aieouAIEOU";
            for(int i=0,j=s.length()-1;i<=j;)
            {
                char left=s.charAt(i);
                char right=s.charAt(j);
                int flagleft=0;
                int flagright=0;
                if(i==j)
                {
                    ch[i]=left;
                    i++;
                    j--;
                    continue;
                }
                if(letter.indexOf(left)==-1)
                {
                    ch[i]=left;
                    i++;
                }
                else
                    flagleft=1;
                if((letter.indexOf(right)==-1)&&(i<j))
                {
                    ch[j]=right;
                    j--;
                }
                else
                    flagright=1;
                if(flagleft==1&&flagright==1)
                {
                    ch[i]=right;
                    ch[j]=left;
                    i++;
                    j--;
                }
            }
            s=String.valueOf(ch);
        }
        return s;
    }
}
```
### 4.验证回文字符串 Ⅱ

https://leetcode-cn.com/problems/valid-palindrome-ii/

#### 思路
前后指针同时移动，判断是否相等。当第一次判断不相等时，前后各移动一次通过子函数判断是否可行，如果依然构不成回文则返回false。
#### 代码
```Java
class Solution {
    public boolean validPalindrome(String s) {
        char[] chars = s.toCharArray();
        int i = 0, j = chars.length - 1;
        while (i < j) {
            if (chars[i] != chars[j]) {
                return validPalindrome(chars, i, j - 1) || validPalindrome(chars, i + 1, j);
            }
            i++;
            j--;
        }
        return true;
    }

    private boolean validPalindrome(char[] chars, int l, int r) {
        while (l < r) {
            if (chars[l] != chars[r]) {
                return false;
            }
            l++;
            r--;
        }
        return true;
    }
}
```
### 5. 归并两个有序数组

https://leetcode-cn.com/problems/merge-sorted-array/

#### 思路
本意是二路归并，但是本题需要在nums1上进行修改。  
因为两个数组全是有序的，而nums1数组空间足够，后部用0填充，则可以从最后的0开始向前填充，双指针对比nums1与nums2的队尾，取较大者进行填充。
如果结束时nums1的指针未移动到头部，则前部的较小元素无需移动，已经有序，直接结束。  
如果nums2指针未移动到头部，说明nums1所有较大元素已经移好，只需将剩下元素复制过去即可。
#### 代码
```Java
class Solution {
    public void merge(int[] nums1, int m, int[] nums2, int n) {
        int p = m-- + n-- - 1;
        while (m >= 0 && n >= 0) {
            nums1[p--] = nums1[m] > nums2[n] ? nums1[m--] : nums2[n--];
        }
        while (n >= 0) {
            nums1[p--] = nums2[n--];
        }
    }
}
``` 
### 6. 判断链表是否存在环

https://leetcode-cn.com/problems/linked-list-cycle/

#### 思路
常用套路，快慢指针，慢指针位移1，快指针位移2，如果有环必会相遇。

骚套路：
1. 用Set存ListNode，如果已经contains，则存在环
2. 每次给Node的value设定一个特殊值，如果检测到，则存在环

#### 代码
```Java
//快慢指针版
public class Solution {
    public boolean hasCycle(ListNode head) {
        ListNode slow=head;
        ListNode fast=head;
        while(fast!=null&&fast.next!=null)
        {
            slow=slow.next;
            fast=fast.next.next;
            if(slow==fast)
                return true;
        }
        return false;
    }
}
//别人写的骚套路，复制过来记一下
public class Solution {
    public boolean hasCycle(ListNode head) {
        Set<ListNode>node = new HashSet<>();
        while(head!=null)
        {
            if(node.contains(head))
                return true;
            else
                node.add(head);
            head = head.next;
        }
        return false;
    }
}
```
### 7. 最长子序列

https://leetcode-cn.com/problems/longest-word-in-dictionary-through-deleting/

#### 思路
对于字典里的每一个字符串，分别判断其是否是s的子串，然后找最长的返回。  
双指针分别指向s和字典需要判断的串。
#### 代码
```Java
class Solution {
    public String findLongestWord(String s, List<String> d) {
        String res="";
        for(int i=0;i<d.size();i++)
        {
            String temp=d.get(i);
            if(issubstring(s,temp))
            {
                if(res.length()<temp.length())
                    res=temp;
                else
                    if((temp.length()==res.length())&&(temp.compareTo(res)<0))
                        res=temp;
            }
        }
        return res;
    }
    public boolean issubstring(String s,String target)
    {
        int i=0,j=0;
        for(;i<s.length()&&j<target.length();)
        {
            if(s.charAt(i)==target.charAt(j))
            {
                i++;
                j++;
            }
            else
            {
                i++;
            }
        }
        if(j==target.length())
            return true;
        return false;
    }
}
```
## 排序
### 1.数组中的第K个最大元素

https://leetcode-cn.com/problems/kth-largest-element-in-an-array/

#### 思路
利用Java的PriorityQueue来实现小顶堆，然后维护堆大小为K，堆顶元素就是第K大的。

#### 代码
```Java
//时间复杂度 O(NlogK)，空间复杂度 O(K)。
class Solution {
    public int findKthLargest(int[] nums, int k) {
        PriorityQueue<Integer> pq = new PriorityQueue<>();
        for(int i=0;i<nums.length;i++)
        {
            pq.add(nums[i]);
            if (pq.size() > k)
                pq.poll();
        }
        return pq.peek();
    }
}
```
### 2. 出现频率最多的 k 个元素

https://leetcode-cn.com/problems/top-k-frequent-elements/comments/

#### 思路
利用桶排序，每个桶存储每个元素的出现频率，然后维护一个小顶堆,手动实现comparator，通过获取频率来比较，就可以获得第K大,但是获取到的是反序的，再reverse一下。

#### 代码
```Java
class Solution {
    public List<Integer> topKFrequent(int[] nums, int k) {
        
        Map<Integer, Integer> m = new HashMap<>();
        for (int num : nums) {
            m.put(num, m.getOrDefault(num, 0) + 1);
        }
        PriorityQueue<Integer> pq = new PriorityQueue<>(new Comparator<Integer>(){
            public int compare(Integer a,Integer b)
            {
                return m.get(a)-m.get(b);
            }
        });
        for (int key : m.keySet()) {
            pq.add(key);
            if(pq.size()>k)
                pq.poll();
        }
        List<Integer> res=new ArrayList<Integer>();
        while(!pq.isEmpty())
        {
            res.add(pq.remove());
        }
        Collections.reverse(res);
        return res;
    }
}
```
### 3. 按照字符出现次数对字符串排序

https://leetcode-cn.com/problems/sort-characters-by-frequency/

#### 思路
用map统计频率，因为全部都需要，所以可以通过手动实现Comparator来维护一个大顶堆，然后遍历堆重复字符还原字符串。

#### 代码
```Java
class Solution {
    public String frequencySort(String s) {
        Map<Character,Integer> map=new HashMap<>();
        for(int i=0;i<s.length();i++)
        {
            char c=s.charAt(i);
            map.put(c,map.getOrDefault(c,0)+1);
        }
        PriorityQueue<Character> pq=new PriorityQueue<Character>(new Comparator<Character>(){
            public int compare(Character a,Character b)
            {
                return map.get(b)-map.get(a);
            }
        });
        for(Character key : map.keySet())
        {
            pq.add(key);
        }
        char[] res=new char[s.length()];
        int j=0;
        while(!pq.isEmpty())
        {
            char ch=pq.remove();
            int times=map.get(ch);
            for(int i=0;i<times;i++)
            {
                res[j++]=ch;
            }
        }
        return String.valueOf(res);
    }
}
```

- [双指针](Leetcode%20题解%20-%20双指针.md)
- [排序](Leetcode%20题解%20-%20排序.md)
- [贪心思想](Leetcode%20题解%20-%20贪心思想.md)
- [二分查找](Leetcode%20题解%20-%20二分查找.md)
- [分治](Leetcode%20题解%20-%20分治.md)
- [搜索](Leetcode%20题解%20-%20搜索.md)
- [动态规划](Leetcode%20题解%20-%20动态规划.md)
- [数学](Leetcode%20题解%20-%20数学.md)

# 数据结构相关

- [链表](Leetcode%20题解%20-%20链表.md)
- [树](Leetcode%20题解%20-%20树.md)
- [栈和队列](Leetcode%20题解%20-%20栈和队列.md)
- [哈希表](Leetcode%20题解%20-%20哈希表.md)
- [字符串](Leetcode%20题解%20-%20字符串.md)
- [数组与矩阵](Leetcode%20题解%20-%20数组与矩阵.md)
- [图](Leetcode%20题解%20-%20图.md)
- [位运算](Leetcode%20题解%20-%20位运算.md)
