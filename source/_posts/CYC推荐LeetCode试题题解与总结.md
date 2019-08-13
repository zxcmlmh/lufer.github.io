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
## 二、排序
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
### 4. 按颜色进行排序

https://leetcode-cn.com/problems/sort-colors/submissions/

#### 思路
原地排序，三向切分，用三个指针进行排序，中间指针用来遍历，左指针指向已拍好的0的位置，右指针指向已排好的2的位置，然后两面夹逼swap。
#### 代码
```Java
class Solution {
    public void sortColors(int[] nums) {
        int left=-1,mid=0,right=nums.length;
        while(mid<right)
        {
            if(nums[mid]==0)
            {
                swap(nums,++left,mid++);
            }
            else
                if(nums[mid]==2)
                {
                    swap(nums,mid,--right);
                }
            else
                mid++;
        }
    }
    public void swap(int[] nums,int i,int j)
    {
        int temp=nums[i];
        nums[i]=nums[j];
        nums[j]=temp;
    }
}
```
## 三、贪心
保证每次操作都是局部最优的，并且最后得到的结果是全局最优的。
### 1. 分配饼干

https://leetcode-cn.com/problems/assign-cookies/

#### 思路
要满足的孩子足够多，应从需求最低的孩子开始满足，这样可以用最小的代价来满足，从而使饼干可以满足更多的人。

CYC给了证明：
```
证明：假设在某次选择中，贪心策略选择给当前满足度最小的孩子分配第 m 个饼干，第 m 个饼干为可以满足该孩子的最小饼干。假设存在一种最优策略，给该孩子分配第 n 个饼干，并且 m < n。我们可以发现，经过这一轮分配，贪心策略分配后剩下的饼干一定有一个比最优策略来得大。因此在后续的分配中，贪心策略一定能满足更多的孩子。也就是说不存在比贪心策略更优的策略，即贪心策略就是最优策略。
```
#### 代码
```Java
class Solution {
    public int findContentChildren(int[] g, int[] s) {
        Arrays.sort(g);
        Arrays.sort(s);
        int count=0;
        for(int gi=0,si=0;gi<g.length&&si<s.length;)
        {
            if(s[si]>=g[gi])
            {
                count++;
                si++;
                gi++;
            }
            else
            {
                si++;
            }
            
        }
        return count;
    }
}
```
### 2. 不重叠的区间个数

https://leetcode-cn.com/problems/non-overlapping-intervals/

#### 思路
按区间尾部排序，尾部越小，后面的剩余空间越大，可以放置的区间越多。  
排序后查找不重叠区间数，就是最大区间数，从而可得移除的最小区间数。
#### 代码
```Java
class Solution {
    public int eraseOverlapIntervals(int[][] intervals) {
        if(intervals.length==0)
            return 0;
        Arrays.sort(intervals,Comparator.comparingInt(o->o[1]));
        int total=1;
        int right=intervals[0][1];
        for(int i=1;i<intervals.length;i++)
        {
            if(intervals[i][0]<right)
                continue;
            right=intervals[i][1];
            total++;
        }
        return intervals.length-total;
    }
}
```
### 3. 投飞镖刺破气球

https://leetcode-cn.com/problems/minimum-number-of-arrows-to-burst-balloons/

#### 思路
同样是求不重叠区间数，有多少不重叠区间就要多少个飞镖。
#### 代码
```Java
class Solution {
    public int findMinArrowShots(int[][] points) {
         if(points.length==0)
            return 0;
        Arrays.sort(points,Comparator.comparingInt(o->o[1]));
        int total=1;
        int right=points[0][1];
        for(int i=1;i<points.length;i++)
        {
            if(points[i][0]<=right)
                continue;
            right=points[i][1];
            total++;
        }
        return total;
    }
}
```
### 4. 根据身高和序号重组队列

https://leetcode-cn.com/problems/queue-reconstruction-by-height/

#### 思路
按身高排序，相同身高按位置排序，位置大的在后面。  
然后开始重建队列，假设候选队列为 A，已经站好队的队列为 B。  
从 A 里挑身高最高的人 x 出来，插入到 B. 因为 B 中每个人的身高都比 x 要高，因此 x 插入的位置，就是看 x 前面应该有多少人就行了。比如 x 前面有 5 个人，那 x 就插入到队列 B 的第 5 个位置。

#### 代码
```Java
class Solution {
    public int[][] reconstructQueue(int[][] people) {
        if(people==null||people.length==0||people[0].length==0)
            return new int[0][0];
        Arrays.sort(people,(a,b)->(a[0]==b[0]?a[1]-b[1]:b[0]-a[0]));
        List<int[]> res=new ArrayList<int[]>();
        for(int i=0;i<people.length;i++)
        {
            res.add(people[i][1],people[i]);
        }
        return res.toArray(new int[people.length][1]);
    }
}
```
### 5. 买卖股票最大的收益

https://leetcode-cn.com/problems/best-time-to-buy-and-sell-stock/submissions/

#### 思路
遍历每一天的价格，保存到目前为止的最小值，然后判断当天卖出的收益，寻找收益最大值。
#### 代理
```Java
class Solution {
    public int maxProfit(int[] prices) {
        if(prices==null||prices.length==0)
            return 0;
        int max=0;
        int curmin=prices[0];
        for(int i=1;i<prices.length;i++)
        {
            if(curmin>prices[i])
                curmin=prices[i];
            if(prices[i]-curmin>max)
                max=prices[i]-curmin;
        }
        return max;
    }
}
```
### 6. 买卖股票的最大收益 II

https://leetcode-cn.com/problems/best-time-to-buy-and-sell-stock-ii/

#### 思路
借用CYC的说法
```
对于 [a, b, c, d]，如果有 a <= b <= c <= d ，那么最大收益为 d - a。而 d - a = (d - c) + (c - b) + (b - a) ，因此当访问到一个 prices[i] 且 prices[i] - prices[i-1] > 0，那么就把 prices[i] - prices[i-1] 添加到收益中。
```
#### 代码
```Java
class Solution {
    public int maxProfit(int[] prices) {
        if(prices==null||prices.length==0)
            return 0;
        int res=0;
        for(int i=1;i<prices.length;i++)
        {
            if(prices[i]-prices[i-1]>0)
                res+=prices[i]-prices[i-1];
        }
        return res;
    }
}
```
### 7. 种植花朵

https://leetcode-cn.com/problems/can-place-flowers/

#### 思路
有连续三个0就可以栽一个，所以针对每一位判断前后是否都是0，两头边界要增加一个判断。  
可以栽之后下一位不可再判断，要手动移位一次，或者把当前位置为1。
#### 代码
```Java
class Solution {
    public boolean canPlaceFlowers(int[] flowerbed, int n) {
        int count=0;
        if(flowerbed==null||flowerbed.length==0)
            return n==0?true:false;
        for(int i=0;i<flowerbed.length;i++)
        {
            if(flowerbed[i]==1)
                continue;
            int pre=i==0?0:flowerbed[i-1];
            int next=i==flowerbed.length-1?0:flowerbed[i+1];
            if(pre==0&&next==0)
            {
                count++;
                i++;
                //flowerbed[i] = 1;往后移一位或者改为1
            }
        }
        if(count>=n)
            return true;
        return false;
    }
}
```
### 8. 判断是否为子序列

https://leetcode-cn.com/problems/is-subsequence/

#### 思路
没弄明白这题放这干啥，不就是移动判断吗
```Java
//手动实现的慢版本
class Solution {
    public boolean isSubsequence(String s, String t) {
        int si=0,ti=0;
        for(;si<s.length()&&ti<t.length();)
        {
            if(s.charAt(si)==t.charAt(ti))
            {
                si++;
                ti++;
            }
            else
            {
                ti++;
            }
        }
        if(si!=s.length())
            return false;
        return true;
    }
}
//借助indexOf实现的快版本
class Solution {
    public boolean isSubsequence(String s, String t) {
        int index=-1;
        for(int i=0;i<s.length();i++)
        {
            char ch=s.charAt(i);
            index=t.indexOf(ch,index+1);
            if(index==-1)
                return false;
        }
        return true;
    }
}
```
### 9. 修改一个数成为非递减数组

https://leetcode-cn.com/problems/non-decreasing-array/

#### 思路
如果出现 a[i] > a[i+1],改变一个数就面临两种选择
1. 把a[i]变大
2. 把a[i+1] 变小

而选择哪一种方式，还需要比较a[i-1] 与 a[i+1]的值  
如果a[i-1]比a[i+1]小，则需要把夹在中间的较大的a[i]变小，使a[i]=a[i+1]。    
如果a[i-1]比a[i+1]大，则a[i-1],a[i]已经非递减，需要把a[i+1]=a[i],来保持非递减。  
改变完之后，记录改变次数，再检测是否升序。  
如果次数大于1，至少改了两次 返回false。  
先让前两个有序
因为没有左边没有数 所以对于前两个数来说，最佳选择就是把 a[0] 变小。    
此外还需要注意先确保前两个有序。
#### 代码
```Java
class Solution {
    public boolean checkPossibility(int[] nums) {
        int flag=0;
        if(nums.length<3)
            return true;
        if(nums[0]>nums[1])
        {
            nums[0]=nums[1];
            flag=1;
        }
        for(int i=1;i<nums.length-1;i++)
        {
            if(nums[i]>nums[i+1])
            {
                if(flag==1)
                {
                    return false;
                }
                if(nums[i-1]<nums[i+1])
                {
                    nums[i]=nums[i+1];
                    flag=1;
                }
                else
                {
                    nums[i+1]=nums[i];
                    flag=1;
                }
            }
        }
        return true;
    }
}
```
### 10. 子数组最大的和

https://leetcode-cn.com/problems/maximum-subarray/submissions/

#### 思路
O(n)的方法遍历一次，如果当前和已经小于0，则让其等于当前遍历值，否则加上当前遍历值。  
和Max比较，得最大值。
#### 代码
```Java
class Solution {
    public int maxSubArray(int[] nums) {
        int max=nums[0];
        int cursum=nums[0];
        for(int i=1;i<nums.length;i++)
        {
            if(cursum<=0)
                cursum=nums[i];
            else
                cursum+=nums[i];
            if(cursum>max)
                max=cursum;
        }
        return max;
    }
}
```
### 11. 分隔字符串使同种字符出现在一起

https://leetcode-cn.com/problems/partition-labels/

#### 思路
对于每一个字符，查找其最后出现位置last，然后从当前位置到last遍历字符，挨个查找最后出现位置last2，当last2比last大时，说明不能在last出分隔，用last2替换last。当一次子查询完成后，代表当前获得了一个可以分割的子串。  

`lastIndexOf(ch,index)的index是他娘的从后往前的索引，我以为是从前往后的，差点改疯了`
#### 代码
```Java
class Solution {
    public List<Integer> partitionLabels(String S) {
                if (S == null || S.length() == 0) {
            return null;
        }
        int start=0;
        List<Integer> res=new ArrayList<>();
        for(int i=0;i<S.length();)
        {
            char ch=S.charAt(i);
            int lastindex=S.lastIndexOf(ch);
            if(lastindex!=-1)
            {
                for(i++;i<=lastindex;i++)
                {
                    char ch2=S.charAt(i);
                    int lastindex2=S.lastIndexOf(ch2);
                    if(lastindex2>lastindex)
                        lastindex=lastindex2;
                }
                res.add(i-start);
                start=i;
            }
            else
                i++;
        }
        return res;
    }
}
```
## 四、二分查找
### 1. 求开方

https://leetcode-cn.com/problems/sqrtx/submissions/

#### 思路
从1~X进行二分查找，判断平方与X的大小，并进行左右边界缩减。   
注意当左右相交还未找到x的平方根时，检查x的平方根与left的大小，如果x的平方根较大则取left，若left较大则取left-1
#### 代码
```Java
class Solution {
    public int mySqrt(int x) {
        if(x<=1)
            return x;
        int left=1,right=x;
        while(left<right)
        {
            int mid=left+(right-left)/2;
            if(x/mid==mid)
                return mid;
            else
                if(x/mid>mid)
                    left=mid+1;
            else
                right=mid-1;
        }
        if(x/left<left)
            return left-1;
        else
            return left;
    }
}
```
### 2. 大于给定元素的最小元素

https://leetcode-cn.com/problems/find-smallest-letter-greater-than-target/

#### 思路
常理来讲，二分查找，左右夹逼直到边界相交，如果到最后还没找到则直接输出最左。
#### 代码
```Java
class Solution {
    public char nextGreatestLetter(char[] letters, char target) {
         int n = letters.length;
        int l = 0, h = n - 1;
        while (l <= h) {
            int m = l + (h - l) / 2;
            if (letters[m] <= target) {
                l = m + 1;
            } else {
                h = m - 1;
            }
        }
        return l < n ? letters[l] : letters[0];
    }
}
```
### 3. 有序数组的 Single Element

https://leetcode-cn.com/problems/single-element-in-a-sorted-array/submissions/

#### 思路
因为数组是有序的，所以一定是`11,22,33,4,55,66,77`这样成对出现，中间夹一个单。  
故用二分法进行查找，看mid值是与左相等还是与右相等。  
如果与左相等，判断从left到mid有多少数，因为要去除与mid相等的mid-1，故如果mid-left是偶数的话，则left~mid-1是奇数，其中必然加载要寻找的数值，故`right=mid-2`，如果是奇数的话，说明从left~mid-1是偶数，则要寻找的数值在另一侧，则`left=mid+1`。如果与右相等，则同理。如果左右都不等，则mid即为要寻找的数值。
#### 代码
```Java
class Solution {
    public int singleNonDuplicate(int[] nums) {
        int left=0,right=nums.length-1;
        while(left<right)
        {
            int mid=left+(right-left)/2;
            if(nums[mid]==nums[mid-1])
            {
                if((mid-left)%2==0)
                    right=mid-2;
                else
                    left=mid+1;
            }
            else
                if(nums[mid]==nums[mid+1])
                {
                    if((right-mid)%2==0)
                        left=mid+2;
                    else
                        right=mid-1;
                }
            else
                return nums[mid];
        }
        return nums[right];
    }
}
```
### 4. 第一个错误的版本

https://leetcode-cn.com/problems/first-bad-version/submissions/

#### 思路
简单地二分查找，注意left和right的处理。
#### 代码
```Java
public class Solution extends VersionControl {
    public int firstBadVersion(int n) {
        int left=0,right=n;
        while(left<right)
        {
            int mid=left+(right-left)/2;
            if(isBadVersion(mid))
            {
                right=mid;
            }
            else
                left=mid+1;
        }
        return right;
    }
}
```
### 5. 旋转数组的最小数字

https://leetcode-cn.com/problems/find-minimum-in-rotated-sorted-array/

#### 思路
因为原来是有序的，旋转之后一定是要寻找的`N<right<left<N-1` ，故如果mid比right大了，mid一定是在`left~N-1`这个区间，故缩减左边界；如果mid比right小，说明mid在`N~right`这个区间内，故缩减右边界。
#### 代码
```Java
class Solution {
    public int findMin(int[] nums) {
        int l = 0, h = nums.length - 1;
        while (l < h) {
            int m = l + (h - l) / 2;
            if (nums[m] <= nums[h]) {
                h = m;
            } else {
                l = m + 1;
            }
        }
        return nums[l];
    }
}
```
### 6. 查找区间

https://leetcode-cn.com/problems/find-first-and-last-position-of-element-in-sorted-array/

#### 思路
分两步来找左右边界，以target的第一次出现位置作为条件二分查找来获取左边界，以target+1来二分查找来获取右边界，其-1就是左边界。
#### 代码
```Java
class Solution {
    public int[] searchRange(int[] nums, int target) {
        int first = binarySearch(nums, target);
        int last = binarySearch(nums, target + 1) - 1;
        if (first == nums.length || nums[first] != target) {
            return new int[]{-1, -1};
        } else {
            return new int[]{first, Math.max(first, last)};
        }
    }
    public int binarySearch(int[] nums, int target) {
        int l = 0, h = nums.length;
        while (l < h) {
            int m = l + (h - l) / 2;
            if (nums[m] >= target) {
                h = m;
            } else {
                l = m + 1;
            }
        }
        return l;
    }
}
```
## 五、分治
### 1. 给表达式加括号

https://leetcode-cn.com/problems/different-ways-to-add-parentheses/

#### 思路
从左往右遍历每一个符号，在每一个符号处分开进行分制，相当于对符号左右两侧加括号。
分治之后对于每个子串再遍历，从而完成每一种加括号的情况。
#### 代码
```Java
class Solution {
    public List<Integer> diffWaysToCompute(String input) {
        List<Integer> res=new ArrayList<>();
        for(int i=0;i<input.length();i++)
        {
            char ch=input.charAt(i);
            if(ch=='+'||ch=='-'||ch=='*')
            {
                List<Integer> left=diffWaysToCompute(input.substring(0,i));
                List<Integer> right=diffWaysToCompute(input.substring(i+1));
                for(int li=0;li<left.size();li++)
                    for(int ri=0;ri<right.size();ri++)
                    {
                        if(ch=='+')
                            res.add(left.get(li)+right.get(ri));
                        if(ch=='-')
                            res.add(left.get(li)-right.get(ri));
                        if(ch=='*')
                            res.add(left.get(li)*right.get(ri));
                    }
            }
        }
        if(res.size()==0)
            res.add(Integer.parseInt(input));
        return res;
    }
}
```
### 2. 不同的二叉搜索树

https://leetcode-cn.com/problems/unique-binary-search-trees-ii/

#### 思路
对于连续整数序列[left, right]中的一点i，若要生成以i为根节点的BST，则有如下规律：  
i左边的序列可以作为左子树结点，i右边的序列可以作为右子树结点，所以左右分治，生成子树的所有情况，然后遍历，加上根节点构建当前树。
#### 代码
```Java
class Solution {
    public List<TreeNode> generateTrees(int n) {
        List<TreeNode> res=new LinkedList<TreeNode>();
        if(n<1)
            return res;
        return generateSubtrees(1,n);
    }
    public List<TreeNode> generateSubtrees(int s, int e) {
        List<TreeNode> res = new LinkedList<TreeNode>();
        if (s > e) {
            res.add(null);
            return res;
        }
        for (int i = s; i <= e; i++) {
            List<TreeNode> left = generateSubtrees(s, i - 1);
            List<TreeNode> right = generateSubtrees(i + 1, e);
            for(int li=0;li<left.size();li++)
                for(int ri=0;ri<right.size();ri++)
                {
                    TreeNode root=new TreeNode(i);
                    root.left=left.get(li);
                    root.right=right.get(ri);
                    res.add(root);
                }
        }
        return res;
    }
}
```
## 六、搜索
### 
## 七、动态规划
## 八、数学
# 第二部分 数据结构相关
## 一、链表
## 二、树
## 三、栈和队列
## 四、哈希表
## 五、字符串
## 六、数组与矩阵
## 七、图
## 八、位运算