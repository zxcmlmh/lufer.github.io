---
title: CYC推荐LeetCode试题题解与总结
date: 2019-08-12 19:38:54
tags: [Java,算法,数据结构]
categories: 算法
---
&emsp;&emsp;本篇文章主要记录来自CYC所推荐的200+LeetCode经典题目解题思路与题解。

&emsp;&emsp;https://github.com/CyC2018/CS-Notes/blob/master/notes/Leetcode%20%E9%A2%98%E8%A7%A3%20-%20%E7%9B%AE%E5%BD%95.md

# 第一部分 算法思想
## 一、双指针
### 1. 有序数组的 Two Sum

&emsp;&emsp;https://leetcode-cn.com/problems/two-sum-ii-input-array-is-sorted/

#### 思路
&emsp;&emsp;因为数组是有序的，所以用双指针首尾想加，结果偏大则尾部向前移动，结果偏小则头部向后移动。
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

&emsp;&emsp;https://leetcode-cn.com/problems/sum-of-square-numbers/

#### 思路
&emsp;&emsp;如果一个数可以被两个数的平方和表示，那么这两个数一定都小于这个数的平方根，故可先求平方根，然后同样双指针前后夹逼。
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

&emsp;&emsp;https://leetcode-cn.com/problems/reverse-vowels-of-a-string/submissions/

#### 思路
&emsp;&emsp;双指针前后寻找，找到元音后停止，当两个指针都停止时，交换字符，然后继续移动。
#### 代码
&emsp;&emsp;代码感觉写的比较繁琐，有待优化。
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

&emsp;&emsp;https://leetcode-cn.com/problems/valid-palindrome-ii/

#### 思路
&emsp;&emsp;前后指针同时移动，判断是否相等。当第一次判断不相等时，前后各移动一次通过子函数判断是否可行，如果依然构不成回文则返回false。
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

&emsp;&emsp;https://leetcode-cn.com/problems/merge-sorted-array/

#### 思路
&emsp;&emsp;本意是二路归并，但是本题需要在nums1上进行修改。  
&emsp;&emsp;因为两个数组全是有序的，而nums1数组空间足够，后部用0填充，则可以从最后的0开始向前填充，双指针对比nums1与nums2的队尾，取较大者进行填充。  
&emsp;&emsp;如果结束时nums1的指针未移动到头部，则前部的较小元素无需移动，已经有序，直接结束。  
&emsp;&emsp;如果nums2指针未移动到头部，说明nums1所有较大元素已经移好，只需将剩下元素复制过去即可。
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

&emsp;&emsp;https://leetcode-cn.com/problems/linked-list-cycle/

#### 思路
&emsp;&emsp;常用套路，快慢指针，慢指针位移1，快指针位移2，如果有环必会相遇。

&emsp;&emsp;骚套路：
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

&emsp;&emsp;https://leetcode-cn.com/problems/longest-word-in-dictionary-through-deleting/

#### 思路
&emsp;&emsp;对于字典里的每一个字符串，分别判断其是否是s的子串，然后找最长的返回。  
&emsp;&emsp;双指针分别指向s和字典需要判断的串。
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

&emsp;&emsp;https://leetcode-cn.com/problems/kth-largest-element-in-an-array/

#### 思路
&emsp;&emsp;利用Java的PriorityQueue来实现小顶堆，然后维护堆大小为K，堆顶元素就是第K大的。

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

&emsp;&emsp;https://leetcode-cn.com/problems/top-k-frequent-elements/

#### 思路
&emsp;&emsp;利用桶排序，每个桶存储每个元素的出现频率，然后维护一个小顶堆,手动实现comparator，通过获取频率来比较，就可以获得第K大,但是获取到的是反序的，再reverse一下。

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

&emsp;&emsp;https://leetcode-cn.com/problems/sort-characters-by-frequency/

#### 思路
&emsp;&emsp;用map统计频率，因为全部都需要，所以可以通过手动实现Comparator来维护一个大顶堆，然后遍历堆重复字符还原字符串。

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

&emsp;&emsp;https://leetcode-cn.com/problems/sort-colors/submissions/

#### 思路
&emsp;&emsp;原地排序，三向切分，用三个指针进行排序，中间指针用来遍历，左指针指向已拍好的0的位置，右指针指向已排好的2的位置，然后两面夹逼swap。
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
&emsp;&emsp;保证每次操作都是局部最优的，并且最后得到的结果是全局最优的。
### 1. 分配饼干

&emsp;&emsp;https://leetcode-cn.com/problems/assign-cookies/

#### 思路
&emsp;&emsp;要满足的孩子足够多，应从需求最低的孩子开始满足，这样可以用最小的代价来满足，从而使饼干可以满足更多的人。

&emsp;&emsp;CYC给了证明：
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

&emsp;&emsp;https://leetcode-cn.com/problems/non-overlapping-intervals/

#### 思路
&emsp;&emsp;按区间尾部排序，尾部越小，后面的剩余空间越大，可以放置的区间越多。  
&emsp;&emsp;排序后查找不重叠区间数，就是最大区间数，从而可得移除的最小区间数。
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

&emsp;&emsp;https://leetcode-cn.com/problems/minimum-number-of-arrows-to-burst-balloons/

#### 思路
&emsp;&emsp;同样是求不重叠区间数，有多少不重叠区间就要多少个飞镖。
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

&emsp;&emsp;https://leetcode-cn.com/problems/queue-reconstruction-by-height/

#### 思路
&emsp;&emsp;按身高排序，相同身高按位置排序，位置大的在后面。  
&emsp;&emsp;然后开始重建队列，假设候选队列为 A，已经站好队的队列为 B。  
&emsp;&emsp;从 A 里挑身高最高的人 x 出来，插入到 B. 因为 B 中每个人的身高都比 x 要高，因此 x 插入的位置，就是看 x 前面应该有多少人就行了。比如 x 前面有 5 个人，那 x 就插入到队列 B 的第 5 个位置。

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

&emsp;&emsp;https://leetcode-cn.com/problems/best-time-to-buy-and-sell-stock/submissions/

#### 思路
&emsp;&emsp;遍历每一天的价格，保存到目前为止的最小值，然后判断当天卖出的收益，寻找收益最大值。
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

&emsp;&emsp;https://leetcode-cn.com/problems/best-time-to-buy-and-sell-stock-ii/

#### 思路
&emsp;&emsp;借用CYC的说法
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

&emsp;&emsp;https://leetcode-cn.com/problems/can-place-flowers/

#### 思路
&emsp;&emsp;有连续三个0就可以栽一个，所以针对每一位判断前后是否都是0，两头边界要增加一个判断。  
&emsp;&emsp;可以栽之后下一位不可再判断，要手动移位一次，或者把当前位置为1。
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

&emsp;&emsp;https://leetcode-cn.com/problems/is-subsequence/

#### 思路
&emsp;&emsp;没弄明白这题放这干啥，不就是移动判断吗。
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

&emsp;&emsp;https://leetcode-cn.com/problems/non-decreasing-array/

#### 思路
&emsp;&emsp;如果出现 a[i] > a[i+1],改变一个数就面临两种选择：
1. 把a[i]变大
2. 把a[i+1] 变小

&emsp;&emsp;而选择哪一种方式，还需要比较a[i-1] 与 a[i+1]的值。  
&emsp;&emsp;如果a[i-1]比a[i+1]小，则需要把夹在中间的较大的a[i]变小，使a[i]=a[i+1]。    
&emsp;&emsp;如果a[i-1]比a[i+1]大，则a[i-1],a[i]已经非递减，需要把a[i+1]=a[i],来保持非递减。  
&emsp;&emsp;改变完之后，记录改变次数，再检测是否升序。  
&emsp;&emsp;如果次数大于1，至少改了两次 返回false。  
&emsp;&emsp;先让前两个有序。
&emsp;&emsp;因为没有左边没有数 所以对于前两个数来说，最佳选择就是把 a[0] 变小。    
&emsp;&emsp;此外还需要注意先确保前两个有序。
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

&emsp;&emsp;https://leetcode-cn.com/problems/maximum-subarray/submissions/

#### 思路
&emsp;&emsp;O(n)的方法遍历一次，如果当前和已经小于0，则让其等于当前遍历值，否则加上当前遍历值。  
&emsp;&emsp;和Max比较，得最大值。
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

&emsp;&emsp;https://leetcode-cn.com/problems/partition-labels/

#### 思路
&emsp;&emsp;对于每一个字符，查找其最后出现位置last，然后从当前位置到last遍历字符，挨个查找最后出现位置last2，当last2比last大时，说明不能在last出分隔，用last2替换last。当一次子查询完成后，代表当前获得了一个可以分割的子串。  

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

&emsp;&emsp;https://leetcode-cn.com/problems/sqrtx/submissions/

#### 思路
&emsp;&emsp;从1~X进行二分查找，判断平方与X的大小，并进行左右边界缩减。   
&emsp;&emsp;注意当左右相交还未找到x的平方根时，检查x的平方根与left的大小，如果x的平方根较大则取left，若left较大则取left-1
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

&emsp;&emsp;https://leetcode-cn.com/problems/find-smallest-letter-greater-than-target/

#### 思路
&emsp;&emsp;常理来讲，二分查找，左右夹逼直到边界相交，如果到最后还没找到则直接输出最左。
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

&emsp;&emsp;https://leetcode-cn.com/problems/single-element-in-a-sorted-array/submissions/

#### 思路
&emsp;&emsp;因为数组是有序的，所以一定是`11,22,33,4,55,66,77`这样成对出现，中间夹一个单。  
&emsp;&emsp;故用二分法进行查找，看mid值是与左相等还是与右相等。  
&emsp;&emsp;如果与左相等，判断从left到mid有多少数，因为要去除与mid相等的mid-1，故如果mid-left是偶数的话，则left~mid-1是奇数，其中必然加载要寻找的数值，故`right=mid-2`，如果是奇数的话，说明从left~mid-1是偶数，则要寻找的数值在另一侧，则`left=mid+1`。如果与右相等，则同理。如果左右都不等，则mid即为要寻找的数值。
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

&emsp;&emsp;https://leetcode-cn.com/problems/first-bad-version/submissions/

#### 思路
&emsp;&emsp;简单地二分查找，注意left和right的处理。
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

&emsp;&emsp;https://leetcode-cn.com/problems/find-minimum-in-rotated-sorted-array/

#### 思路
&emsp;&emsp;因为原来是有序的，旋转之后一定是要寻找的`N<right<left<N-1` ，故如果mid比right大了，mid一定是在`left~N-1`这个区间，故缩减左边界；如果mid比right小，说明mid在`N~right`这个区间内，故缩减右边界。
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

&emsp;&emsp;https://leetcode-cn.com/problems/find-first-and-last-position-of-element-in-sorted-array/

#### 思路
&emsp;&emsp;分两步来找左右边界，以target的第一次出现位置作为条件二分查找来获取左边界，以target+1来二分查找来获取右边界，其-1就是左边界。
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

&emsp;&emsp;https://leetcode-cn.com/problems/different-ways-to-add-parentheses/

#### 思路
&emsp;&emsp;从左往右遍历每一个符号，在每一个符号处分开进行分制，相当于对符号左右两侧加括号。
&emsp;&emsp;分治之后对于每个子串再遍历，从而完成每一种加括号的情况。
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

&emsp;&emsp;https://leetcode-cn.com/problems/unique-binary-search-trees-ii/

#### 思路
&emsp;&emsp;对于连续整数序列[left, right]中的一点i，若要生成以i为根节点的BST，则有如下规律：  
&emsp;&emsp;i左边的序列可以作为左子树结点，i右边的序列可以作为右子树结点，所以左右分治，生成子树的所有情况，然后遍历，加上根节点构建当前树。
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
### 1. 最短单词路径

&emsp;&emsp;https://leetcode-cn.com/problems/word-ladder/

#### 思路
&emsp;&emsp;说实话，看题解思路看懂了，自己做还是没头绪。  
1. 对给定的 wordList 做预处理，找出所有的通用状态。将通用状态记录在字典中，键是通用状态，值是所有具有通用状态的单词。
2. 将包含 beginWord 和 1 的元组放入队列中，1 代表节点的层次。我们需要返回 endWord 的层次也就是从 beginWord 出发的最短距离。
3. 为了防止出现环，使用访问数组记录。
4. 当队列中有元素的时候，取出第一个元素，记为 current_word。
5. 找到 current_word 的所有通用状态，并检查这些通用状态是否存在其它单词的映射，这一步通过检查 all_combo_dict 来实现。
6. 从 all_combo_dict 获得的所有单词，都和 current_word 共有一个通用状态，所以都和 current_word 相连，因此将他们加入到队列中。
7. 对于新获得的所有单词，向队列中加入元素 (word, level + 1) 其中 level 是 current_word 的层次。
8. 最终当你到达期望的单词，对应的层次就是最短变换序列的长度。

#### 代码
```Java
class Solution {
    public int ladderLength(String beginWord, String endWord, List<String> wordList) {
    // Since all words are of same length.
    int L = beginWord.length();
    // Dictionary to hold combination of words that can be formed,
    // from any given word. By changing one letter at a time.
    HashMap<String, ArrayList<String>> allComboDict = new HashMap<String, ArrayList<String>>();

    wordList.forEach(
        word -> {
          for (int i = 0; i < L; i++) {
            // Key is the generic word
            // Value is a list of words which have the same intermediate generic word.
            String newWord = word.substring(0, i) + '*' + word.substring(i + 1, L);
            ArrayList<String> transformations =
                allComboDict.getOrDefault(newWord, new ArrayList<String>());
            transformations.add(word);
            allComboDict.put(newWord, transformations);
          }
        });

    // Queue for BFS
    Queue<Pair<String, Integer>> Q = new LinkedList<Pair<String, Integer>>();
    Q.add(new Pair(beginWord, 1));

    // Visited to make sure we don't repeat processing same word.
    HashMap<String, Boolean> visited = new HashMap<String, Boolean>();
    visited.put(beginWord, true);

    while (!Q.isEmpty()) {
      Pair<String, Integer> node = Q.remove();
      String word = node.getKey();
      int level = node.getValue();
      for (int i = 0; i < L; i++) {

        // Intermediate words for current word
        String newWord = word.substring(0, i) + '*' + word.substring(i + 1, L);

        // Next states are all the words which share the same intermediate state.
        for (String adjacentWord : allComboDict.getOrDefault(newWord, new ArrayList<String>())) {
          // If at any point if we find what we are looking for
          // i.e. the end word - we can return with the answer.
          if (adjacentWord.equals(endWord)) {
            return level + 1;
          }
          // Otherwise, add it to the BFS Queue. Also mark it visited
          if (!visited.containsKey(adjacentWord)) {
            visited.put(adjacentWord, true);
            Q.add(new Pair(adjacentWord, level + 1));
          }
        }
      }
    }

    return 0;
  }
}
```
### 2. 查找最大的连通面积

&emsp;&emsp;https://leetcode-cn.com/problems/max-area-of-island/

#### 思路
&emsp;&emsp;遍历所有节点，从当前节点出发上下左右开始寻找所有连同的1，来寻找最大值。已经查找过得点可以置为0，以免再次查找。
#### 代码
```Java
class Solution {
    private int m, n;
    private int[][] direction = {{0, 1}, {0, -1}, {1, 0}, {-1, 0}};
    public int maxAreaOfIsland(int[][] grid) {
        if (grid == null || grid.length == 0) {
            return 0;
        }
        m = grid.length;
        n = grid[0].length;
        int maxArea = 0;
        for (int i = 0; i < m; i++) {
            for (int j = 0; j < n; j++) {
                maxArea = Math.max(maxArea, dfs(grid, i, j));
            }
        }
        return maxArea;
    }

    private int dfs(int[][] grid, int r, int c) {
        if (r < 0 || r >= m || c < 0 || c >= n || grid[r][c] == 0) {
            return 0;
        }
        grid[r][c] = 0;
        int area = 1;
        for (int[] d : direction) {
            area += dfs(grid, r + d[0], c + d[1]);
        }
        return area;
    }
}
```
### 3. 矩阵中的连通分量数目

&emsp;&emsp;https://leetcode-cn.com/problems/number-of-islands/

#### 思路
&emsp;&emsp;与上题基本一样。
#### 代码
```Java
class Solution {
    private int m, n;
    private int[][] direction = {{0, 1}, {0, -1}, {1, 0}, {-1, 0}};
    public int numIslands(char[][] grid) {
        if (grid == null || grid.length == 0) {
            return 0;
        }
        m = grid.length;
        n = grid[0].length;
        int islandsNum = 0;
        for (int i = 0; i < m; i++) {
            for (int j = 0; j < n; j++) {
                if (grid[i][j] != '0') {
                    dfs(grid, i, j);
                    islandsNum++;
                }
            }
        }
        return islandsNum;
    }
    private void dfs(char[][] grid, int i, int j) {
        if (i < 0 || i >= m || j < 0 || j >= n || grid[i][j] == '0') {
            return;
        }
        grid[i][j] = '0';
        for (int[] d : direction) {
            dfs(grid, i + d[0], j + d[1]);
        }
    }
}
```
### 4. 好友关系的连通分量数目

&emsp;&emsp;https://leetcode-cn.com/problems/friend-circles/

#### 思路
&emsp;&emsp;由于两个人互为好友，这个图一定是对角线对称的，我们可以从每个人出发遍历其朋友圈进行DFS，对于通过DFS遍历查找过得人，可以用一个标记数组来标记。
#### 代码
```Java
class Solution {
    public int findCircleNum(int[][] M) {
        boolean[] visited = new boolean[M.length];
        int ret = 0;
        for(int i = 0; i < M.length; ++i) {
            if(!visited[i]) {
                dfs(M, visited, i);
                ret++;
            }
        }
        return ret;
    }
    private void dfs(int[][] m, boolean[] visited, int i) {
        for(int j = 0; j < m.length; ++j) {
            if(m[i][j] == 1 && !visited[j]) {
                visited[j] = true;
                dfs(m, visited, j);
            }
        }
    }
}
```
### 5. 填充封闭区域

&emsp;&emsp;https://leetcode-cn.com/problems/surrounded-regions/

#### 思路
&emsp;&emsp;先遍历上下左右边界，把所有连通的`O`用`N`来代替，然后遍历整个数组，把`N`换位`O`，把`O`换为`X`即可。
#### 代码
```Java
class Solution {
    private int[][] direction = {{0, 1}, {0, -1}, {1, 0}, {-1, 0}};
    private int m, n;
    public void solve(char[][] board) {
         if (board == null || board.length == 0) {
            return ;
        }
        m = board.length;
        n = board[0].length;
        for(int i=0;i<m;i++)
        {
            dfs(board,i,0);
            dfs(board,i,n-1);
        }
        for(int i=0;i<n;i++)
        {
            dfs(board,0,i);
            dfs(board,m-1,i);
        }
        for (int i = 0; i < m; i++) 
        {
            for (int j = 0; j < n; j++) 
            {
                if (board[i][j] == 'N') 
                {
                    board[i][j] = 'O';
                }
                else
                {
                    if (board[i][j] == 'O') 
                    {
                        board[i][j] = 'X';
                    }
                }
            }
        }
    }
    private void dfs(char[][] board, int i, int j) {
        if (i < 0 || i >= m || j < 0 || j >= n || board[i][j] != 'O') {
            return;
        }
        board[i][j] = 'N';
        for (int[] d : direction) {
            dfs(board, i + d[0], j + d[1]);
        }
    }
}
```
### 6. 能到达的太平洋和大西洋的区域

&emsp;&emsp;https://leetcode-cn.com/problems/pacific-atlantic-water-flow/

#### 思路
&emsp;&emsp;从上下左右边界出发，寻找递增路线从而找到每个大洋可以触及的节点，如果两个大洋都能触及，则满足要求。
#### 代码
```Java
class Solution {
    private int m, n;
    private int[][] matrix;
    private int[][] direction = {{0, 1}, {0, -1}, {1, 0}, {-1, 0}};
    public List<List<Integer>> pacificAtlantic(int[][] matrix) {
        List<List<Integer>> res=new ArrayList<>();
        List<int[]> ret = new ArrayList<>();
        if (matrix == null || matrix.length == 0) {
            return res;
        }
        m = matrix.length;
        n = matrix[0].length;
        this.matrix = matrix;
        boolean[][] canReachP = new boolean[m][n];
        boolean[][] canReachA = new boolean[m][n];

        for (int i = 0; i < m; i++) {
            dfs(i, 0, canReachP);
            dfs(i, n - 1, canReachA);
        }
        for (int i = 0; i < n; i++) {
            dfs(0, i, canReachP);
            dfs(m - 1, i, canReachA);
        }

        for (int i = 0; i < m; i++) {
            for (int j = 0; j < n; j++) {
                if (canReachP[i][j] && canReachA[i][j]) {
                    List<Integer> temp=new ArrayList<>();
                    temp.add(i);
                    temp.add(j);
                    res.add(temp);
                }
            }
        }

        return res;
    }
    private void dfs(int r, int c, boolean[][] canReach) {
        if (canReach[r][c]) {
            return;
        }
        canReach[r][c] = true;
        for (int[] d : direction) {
            int nextR = d[0] + r;
            int nextC = d[1] + c;
            if (nextR < 0 || nextR >= m || nextC < 0 || nextC >= n
                    || matrix[r][c] > matrix[nextR][nextC]) {

                continue;
            }
            dfs(nextR, nextC, canReach);
        }
    }
}
```
## 七、动态规划
### 1. 爬楼梯

&emsp;&emsp;https://leetcode-cn.com/problems/climbing-stairs/

#### 思路
&emsp;&emsp;如果用`dp[i]`代表到第i级台阶的跳法，由于一次可以跳一级或者两级，所以`dp[i]=dp[i-1]+dp[i-2]`
#### 代码
```Java
class Solution {
    public int climbStairs(int n) {
        if (n <= 2) {
            return n;
        }
        int pre2 = 1, pre1 = 2;
        for (int i = 2; i < n; i++) {
            int cur = pre1 + pre2;
            pre2 = pre1;
            pre1 = cur;
        }
        return pre1;
    }
}
```
### 2. 强盗抢劫

&emsp;&emsp;https://leetcode-cn.com/problems/house-robber/

#### 思路
&emsp;&emsp;如果用`dp[i]`代表在当前房屋所能获取的最大收益，那么对于房屋i，一共有两种选择，即偷和不偷。如果偷的话，获得的收益就是`dp[i-2]+nums[i]`,如果不偷，那么收益就是`dp[i-1]`，两者取大者，就是当前房屋的最大收益，随后遍历即可。
#### 代码
```Java
class Solution {
    public int rob(int[] nums) {
        int pre2 = 0, pre1 = 0;
        for (int i = 0; i < nums.length; i++) {
            int cur = Math.max(pre2 + nums[i], pre1);
            pre2 = pre1;
            pre1 = cur;
        }
        return pre1;
    }
}
```
### 3. 强盗在环形街区抢劫

&emsp;&emsp;https://leetcode-cn.com/problems/house-robber-ii/

#### 思路
&emsp;&emsp;与非环抢劫的区别就是要单独处理首尾，第一个与最后一个只能选一个抢。
#### 代码
```Java
class Solution {
    public int rob(int[] nums) {
         if (nums == null || nums.length == 0) {
            return 0;
        }
        if (nums.length == 1) {
            return nums[0];
        }
        return Math.max(rob(nums,0,nums.length-1),rob(nums,1,nums.length));
    }
    public int rob(int[] nums,int start,int end) {
        int pre2 = 0, pre1 = 0;
        for (int i = start; i < end; i++) {
            int cur = Math.max(pre2 + nums[i], pre1);
            pre2 = pre1;
            pre1 = cur;
        }
        return pre1;
    }
}
```
### 4. 矩阵的最小路径和

&emsp;&emsp;https://leetcode-cn.com/problems/minimum-path-sum/

#### 思路
&emsp;&emsp;对于矩阵每一行进行一次遍历，每行的每个格子都只有从上走下来和从左走过来两种选择，所以`dp[i][j]=min(dp[i][j-1],dp[i-1][j])+grid[i][j]`。
#### 代码
```Java
//普通版本
class Solution {
    public int minPathSum(int[][] grid) {
        int[][] dp=new int[grid.length][grid[0].length];
        dp[0][0]=grid[0][0];
        for(int i=0;i<grid.length;i++)
        {
            for(int j=0;j<grid[0].length;j++)
            {
                if(i==0&&j==0)
                    continue;
                if(j==0)
                    dp[i][j]=dp[i-1][j]+grid[i][j];
                else
                    if(i==0)
                        dp[i][j]=dp[i][j-1]+grid[i][j];
                    else
                        dp[i][j]=Math.min(dp[i][j-1],dp[i-1][j])+grid[i][j];
            }
        }
        return dp[grid.length-1][grid[0].length-1];
    }
}
//空间优化版本，一维dp数组
class Solution {
    public int minPathSum(int[][] grid) {
        if (grid.length == 0 || grid[0].length == 0) {
            return 0;
        }
        int m = grid.length, n = grid[0].length;
        int[] dp = new int[n];
        for (int i = 0; i < m; i++) {
            for (int j = 0; j < n; j++) {
                if (j == 0) {
                    dp[j] = dp[j];        // 只能从上侧走到该位置
                } else if (i == 0) {
                    dp[j] = dp[j - 1];    // 只能从左侧走到该位置
                } else {
                    dp[j] = Math.min(dp[j - 1], dp[j]);
                }
                dp[j] += grid[i][j];
            }
        }
        return dp[n - 1];
    }
}
```
### 5. 矩阵的总路径数

&emsp;&emsp;https://leetcode-cn.com/problems/unique-paths/

#### 思路
&emsp;&emsp;对于每个`dp[i][j]`，到达该格子有从左和从上两个方式到达，最顶上和最左边的边界只有一种方式到达。
#### 代码
```Java
class Solution {
    public int uniquePaths(int m, int n) {
        int[][] dp=new int[m][n];
        for(int i=0;i<m;i++)
            for(int j=0;j<n;j++)
            {
                if(i==0||j==0)
                    dp[i][j]=1;
                else
                    dp[i][j]=dp[i-1][j]+dp[i][j-1];
            }
        return dp[m-1][n-1];
    }
}
```
### 6. 数组区间和

&emsp;&emsp;https://leetcode-cn.com/problems/range-sum-query-immutable/

#### 思路
&emsp;&emsp;因为会多次调用，所以每次都累加是不合适的。遍历数组一遍，保存每次到i的累和，然后返回两个边界的累和差值即可。
#### 代码
```Java
class NumArray {

    private int[] sums;

    public NumArray(int[] nums) {
        sums = new int[nums.length + 1];
        for (int i = 1; i <= nums.length; i++) {
            sums[i] = sums[i - 1] + nums[i - 1];
        }
    }

    public int sumRange(int i, int j) {
        return sums[j + 1] - sums[i];
    }
}
```
### 7. 数组中等差递增子区间的个数

&emsp;&emsp;https://leetcode-cn.com/problems/arithmetic-slices/

#### 思路
&emsp;&emsp;用`dp[i]`代表以`A[i]`为结尾的等差数列的数量，那么只有两种情况：
1. 当前的A[i]与前面的数据项构不成等差数列，则此处为0。
2. 当前的A[i]与前面的数据能构成等差数列，此时有`A[i]-A[i-1]=A[i-1]-A[i-2]`,那么此时的等差数列种数为`dp[i-1]+1`

#### 代码
```Java
class Solution {
    public int numberOfArithmeticSlices(int[] A) {
        if (A == null || A.length == 0) {
            return 0;
        }
        int n = A.length;
        int[] dp = new int[n];
        for (int i = 2; i < n; i++) {
            if (A[i] - A[i - 1] == A[i - 1] - A[i - 2]) {
                dp[i] = dp[i - 1] + 1;
            }
        }
        int total = 0;
        for (int cnt : dp) {
            total += cnt;
        }
        return total;
    }
}
```
### 8. 分割整数的最大乘积

&emsp;&emsp;https://leetcode-cn.com/problems/integer-break/

#### 思路
&emsp;&emsp;如果用`dp[i]`代表正整数i拆分后的最大乘积，那么他有三种选择：
1. dp[i]自身
2. 从`1~dp[i]`遍历，然后获得j*dp[i-j]
3. 从`1~dp[i]`遍历，然后获得j*(i-j)

&emsp;&emsp;取三者最大值
#### 代码
```Java
class Solution {
    public int integerBreak(int n) {
        int[] dp = new int[n + 1];
        dp[1] = 1;
        for (int i = 2; i <= n; i++) {
            for (int j = 1; j <= i - 1; j++) {
                dp[i] = Math.max(dp[i], Math.max(j * dp[i - j], j * (i - j)));
            }
        }
        return dp[n];
    }
}
```
### 9. 按平方数来分割整数

&emsp;&emsp;https://leetcode-cn.com/problems/perfect-squares/

#### 思路
&emsp;&emsp;两个思路，一个是采用DP的方式，一个是采用数学定理的方式。
1. DP  
&emsp;&emsp;定义一个函数f(n)表示我们要求的解。f(n)的求解过程为：
f(n) = 1 + min{
  f(n-1^2), f(n-2^2), f(n-3^2), f(n-4^2), ... , f(n-k^2) //(k为满足k^2<=n的最大的k)
}
2. 数学定理  
&emsp;&emsp;四平方定理：任何一个正整数都可以表示成不超过四个整数的平方之和。   
&emsp;&emsp;推论：满足四数平方和定理的数n（四个整数的情况），必定满足 n=4^a(8b+7)

#### 代码
```Java
//DP版本
class Solution {
     public int numSquares(int n) {
        int [] res = new int[n+1];
        for (int i = 1; i <= n; i++){
            int min = Integer.MAX_VALUE;
            for (int j = 1; j*j <= i; j++){
                min = Math.min(min, res[i-j*j]);
            }
            res[i] = min + 1;
        }
        return res[n];
    }
}
//数学定理版
// 由定理可知，结果只有 “1、2、3、4” 四种可能。依次判断下列情况：
// （1）ans = 4 ，判断是否满足推论；（在此过程中，以 4 的倍数缩小 n ，并不影响最后结果）
// （2）ans = 1 ，判断缩小后的 n 是否为平方数；
// （3）ans = 2 ，判断缩小后的 n 是否可以由两个平方数构成；
// （4）ans = 3， 以上都不满足，则结果为 3。
class Solution {
     public int numSquares(int n) {
        while(n%4==0)
            n/=4;
         if(n%8==7)
             return 4;
         for(int i=0;i*i<n;i++)
         {
             int j=(int)Math.sqrt(n-i*i);
             if(j*j+i*i==n)
                 if(i!=0&&j!=0)
                     return 2;
                else
                    return 1;
         }
         return 3;
    }
}
```
### 10. 分割整数构成字母字符串

&emsp;&emsp;https://leetcode-cn.com/problems/decode-ways/

#### 思路
&emsp;&emsp;本题与跳台阶本质上相同，如果我们用`dp[i]`代表当前位可以解码的方式，那么对于第i位有两种选择。  
1. 第i位单独解码，此时`dp[i]=dp[i-1]`
2. 第i位与第i-1位共同解码，此时`dp[i]=dp[i-2]`

&emsp;&emsp;综上，所以`dp[i]=dp[i-1]+dp[i-2]`。  
&emsp;&emsp;但是要注意，0不能单独解码，所以如果s[i-1]是0，那么dp[i-1]也是0。  
&emsp;&emsp;如果s[i-2]是0，则不能加上前一位进行解码，即dp[i-2]是0。  
&emsp;&emsp;同时由于解码数字不会超过26，所以如果最近两位解码结果超过26，那么dp[i-2]也是0。 
#### 代码
```Java
class Solution {
    public int numDecodings(String s) {
        if(s.length()==0||(s.length()==1&&s.charAt(0)=='0'))
            return 0;
        if(s.length()==1)
            return 1;
        int[] dp=new int[s.length()+1];
        dp[0]=1;
        dp[1]=s.charAt(0) == '0' ? 0 : 1;
        for(int i=2;i<=s.length();i++)
        {
            int onestep=s.charAt(i-1)=='0'?0:dp[i-1];
            int twostep=Integer.valueOf(s.substring(i - 2, i));
            if(s.charAt(i-2)=='0')
                twostep=0;
            else
                twostep=twostep>26?0:dp[i-2];
            dp[i]=onestep+twostep;
        }
        return dp[s.length()];
    }
}
```
### 11. 最长递增子序列

&emsp;&emsp;https://leetcode-cn.com/problems/longest-increasing-subsequence/

#### 思路
&emsp;&emsp;如果我们用`dp[i]`代表以第i个数字结尾的最长递增序列的长度，则我们从`0~i`遍历j，对于每一个比`nums[i]`小的`nums[j]`,都要比较`dp[i]=Max(dp[i],dp[j]+1)`。此处注意`dp[i]`的默认值是1，最后取dp数组最大值即可。
#### 代码
```Java
class Solution {
    public int lengthOfLIS(int[] nums) {
        if(nums==null||nums.length==0)
            return 0;
        int[] dp=new int[nums.length];
        dp[0]=1;
        int max=1;
        for(int i=1;i<nums.length;i++)
        {
            dp[i]=1;
            for(int j=0;j<i;j++)
            {
                if(nums[i]>nums[j])
                    dp[i]=Math.max(dp[i],dp[j]+1);
            }
            if(max<dp[i])
                max=dp[i];
        }
        return max;
    }
}
```
### 12. 一组整数对能够构成的最长链

&emsp;&emsp;https://leetcode-cn.com/problems/maximum-length-of-pair-chain/

#### 思路
&emsp;&emsp;与上一题基本一样，本题只是需要先将数对按起点排序，然后遍历找到可以与当前数对形成数链的数对，比较dp。
#### 代码
```Java
class Solution {
    public int findLongestChain(int[][] pairs) {
        if(pairs==null||pairs.length==0)
            return 0;
        Arrays.sort(pairs,(a,b)->(a[0]-b[0]));
        int[] dp=new int[pairs.length];
        dp[0]=1;
        int max=1;
        for(int i=1;i<pairs.length;i++)
        {
            dp[i]=1;
            for(int j=0;j<i;j++)
            {
                if(pairs[j][1]<pairs[i][0])
                    dp[i]=Math.max(dp[i],dp[j]+1);
            }
            if(max<dp[i])
                max=dp[i];
        }
        return max;
    }
}
```
### 13. 最长摆动子序列

&emsp;&emsp;https://leetcode-cn.com/problems/wiggle-subsequence/

#### 思路
&emsp;&emsp;用两个变量up和down分别计算向上摆动和向下摆动的数量，从头遍历数组，如果数组向上了，那么当前的up就是上一位的down+1，如果数组向下了，那么当前的down就是上一位的up+1。
#### 代码
```Java
class Solution {
    public int wiggleMaxLength(int[] nums) {
        if (nums == null || nums.length == 0) {
            return 0;
        }
        int up = 1, down = 1;
        for (int i = 1; i < nums.length; i++) {
            if (nums[i] > nums[i - 1]) {
                up = down + 1;
            } else if (nums[i] < nums[i - 1]) {
                down = up + 1;
            }
        }
        return Math.max(up, down);
    }
}
```
### 14. 划分数组为和相等的两部分

&emsp;&emsp;https://leetcode-cn.com/problems/partition-equal-subset-sum/

#### 思路
&emsp;&emsp;对于背包问题，我们可以用`dp[i][j]`来表示从`0~i`中是否存在满足价值为j的组合。  
&emsp;&emsp;而是否满足主要有两种选择：
1. 如果不选择当前`nums[i]`的价值，则依赖于`dp[i-1][j]`，两者判断状态相同。  
2. 如果选择当前`nums[i]`的价值，则依赖于`dp[i-1][j-nums[i]]`,两者判断状态相同。

&emsp;&emsp;如果有一种情况满足需求，则`dp[i][j]`可以实现。
#### 代码
```Java
class Solution {
    public boolean canPartition(int[] nums) {
        int sum=0;
        for(int i=0;i<nums.length;i++)
            sum+=nums[i];
        if(sum%2!=0)
            return false;
        sum/=2;
        boolean[] dp = new boolean[sum+1];
        dp[0] = true;
        for(int i=0;i<nums.length;i++)
        {
            for(int j=sum;j>=nums[i];j--)
            {
                dp[j]=dp[j]||dp[j-nums[i]];
            }
        }
        return dp[sum];
    }
}
```
### 15. 改变一组数的正负号使得它们的和为一给定数

&emsp;&emsp;https://leetcode-cn.com/problems/target-sum/

#### 思路
&emsp;&emsp;引用CYC推理
```
将数组看成两部分，P 和 N，其中 P 使用正号，N 使用负号，有以下推导：
                  sum(P) - sum(N) = target
sum(P) + sum(N) + sum(P) - sum(N) = target + sum(P) + sum(N)
                       2 * sum(P) = target + sum(nums)
&emsp;&emsp;因此只要找到一个子集，令它们都取正号，并且和等于 (target + sum(nums))/2，就证明存在解。
```
#### 代码
```Java
class Solution {
    public int findTargetSumWays(int[] nums, int S) {
        int sum = 0;
        for(int i=0;i<nums.length;i++)
            sum+=nums[i];
        if (sum < S || (sum + S) % 2 == 1) {
            return 0;
        }
        int W = (sum + S) / 2;
        int[] dp = new int[W + 1];
        dp[0] = 1;
        for (int num : nums) {
            for (int i = W; i >= num; i--) {
                dp[i] = dp[i] + dp[i - num];
            }
        }
        return dp[W];
    }
}
```
### 16. 01 字符构成最多的字符串

&emsp;&emsp;https://leetcode-cn.com/problemset/all/?search=474

#### 思路
&emsp;&emsp;多维费用问题，我们用`dp[i][j]`表示使用i个0和j个1能表示的字符串的最大数量。  
&emsp;&emsp;则`dp[i][j]=Max(dp[i][j],dp[i-zero][j-one]+1)`,其中zero代表当前0的数量，1代表当前1的数量。
#### 代码
```Java
class Solution {
    public int findMaxForm(String[] strs, int m, int n) {
        if (strs.length == 0) {
			return 0;
		}
		int[][] dp =new int[m+1][n+1];
		for(String s :strs) {
			int zeros  = 0,ones = 0;
			for(char c:s.toCharArray()) {
				if (c == '0') {
					zeros++;
				}else {
					ones++;
				}
			}
			for (int i = m; i >=zeros; i--) {
				for (int j = n; j >= ones; j--) {
					dp[i][j] = Math.max(dp[i][j], 1+dp[i-zeros][j-ones]);
				}
			}
		}
		return dp[m][n];
    }
}
```
### 17. 找零钱的最少硬币数

&emsp;&emsp;https://leetcode-cn.com/problems/coin-change/

#### 思路
&emsp;&emsp;背包大小就是所给的amout，占据背包容量的就是硬币面额。   
&emsp;&emsp;`完全背包只需要将 0-1 背包的逆序遍历 dp 数组改为正序遍历即可。`  
&emsp;&emsp;然后取最小数量，对于每个`dp[i][j]`,最小数量有三种情况：
1. 当前硬币就是容量大小，即`coins[i]==j`，那么使用这枚硬币即可完成任务，必然获得最小值1。
2. 不使用当前硬币时，无法满足要求(即`dp[i-1][j]==0`)，但是使用当前硬币可以满足要求(即`dp[i][j-coins[i]]!=0`)，dp[i][j]为`dp[i][j-coins[i]]+1`。
3. 使用当前硬币可以完成要求，不使用也可以完成要求，则要两者比较取最小值。

#### 代码
```Java
class Solution {
    public int coinChange(int[] coins, int amount) {
        if (amount == 0 || coins == null || coins.length == 0) {
            return 0;
        }
        int[] dp = new int[amount + 1];
        for(int i=0;i<coins.length;i++)
        {
            for(int j=coins[i];j<=amount;j++)
            {
                if(j==coins[i])
                    dp[j]=1;
                else
                    if(dp[j]==0&&dp[j-coins[i]]!=0)
                        dp[j]=dp[j-coins[i]]+1;
                else
                    if(dp[j-coins[i]]!=0)
                        dp[j]=Math.min(dp[j],dp[j-coins[i]]+1);
            }
        }
        return dp[amount] == 0 ? -1 : dp[amount];
    }
}
```
### 18. 找零钱的硬币数组合  

&emsp;&emsp;https://leetcode-cn.com/problems/coin-change-2/submissions/

#### 思路
&emsp;&emsp;与上题类似，只不过本题需要求可能获得的组合总数，对于每一个`dp[i][j]`，其状态转移方程为`dp[i][j]=dp[i-1][j]+dp[i][j-coins[i]]`。
#### 代码
```Java
class Solution {
    public int change(int amount, int[] coins) {
        int[] dp=new int[amount+1];
        dp[0]=1;
        for(int i=0;i<coins.length;i++)
        {
            int coin=coins[i];
            for(int j=coin;j<=amount;j++)
            {
                dp[j]+=dp[j-coin];
            }
        }
        return dp[amount];
    }
}
```
### 19. 字符串按单词列表分割

&emsp;&emsp;https://leetcode-cn.com/problems/word-break/

#### 思路
&emsp;&emsp;字典单词是可以重复使用的，故本题为一个完全背包问题，用字典单词来填充背包，所要对比的价值就是字符串。  
&emsp;&emsp;对于有序的背包问题，将物品的迭代放在最里层，背包的迭代放在外层。
#### 代码
```Java
class Solution {
    public boolean wordBreak(String s, List<String> wordDict) {
        int n = s.length();
        boolean[] dp = new boolean[n + 1];
        dp[0] = true;
        for (int i = 1; i <= n; i++) {
            for (String word : wordDict) {   // 对物品的迭代应该放在最里层
                int len = word.length();
                if (len <= i && word.equals(s.substring(i - len, i))) {
                    dp[i] = dp[i] || dp[i - len];
                }
            }
        }
        return dp[n];
    }
}
```
### 20. 组合总和

&emsp;&emsp;https://leetcode-cn.com/problems/combination-sum-iv/

#### 思路
&emsp;&emsp;依然是有序的完全背包问题。
#### 代码
```Java
class Solution {
    public int combinationSum4(int[] nums, int target) {
        Arrays.sort(nums);
        int n=nums.length;
        int[] dp=new int[target+1];
        dp[0]=1;
        for(int i=1;i<=target;i++)
        {
            for(int j=0;j<nums.length&&i>=nums[j];j++)
            {
                dp[i]+=dp[i-nums[j]];
            }
        }
        return dp[target];
    }
}
```
#### 背包问题总结
&emsp;&emsp;对于背包问题，主要有两种，即`0-1背包`or`完全背包`，这其中又可分为`元素有序`与`元素无序`两种。  
&emsp;&emsp;对于无序问题，则对于元素的遍历在外层，对于背包的遍历在内层。我们以`V[]`来代表元素数组，`target`来代表目标价值，则两层循环可以表示为
```Java
for(int i=0;i<nums.length;i++)
    for(int j=target;j>=V[i];j--)
```
&emsp;&emsp;对于完全背包，则需要将内层循环的顺序反序
```Java
for(int i=0;i<nums.length;i++)
    for(int j=V[i];j<=target;j++)
```
&emsp;&emsp;而对于有序问题，需要将两层循环换位，可知循环条件需为`V[i]<=j<=target`,所以完全背包的双层循环可以表示为：
```Java
for(int i=1;i<=target;i++)
    for(int j=0;j<V.length&&j>=V[i];j++)
```
### 21. 需要冷却期的股票交易

&emsp;&emsp;https://leetcode-cn.com/problems/best-time-to-buy-and-sell-stock-with-cooldown/

#### 思路
&emsp;&emsp;sell[i]表示截至第i天，最后一个操作是卖时的最大收益；  
&emsp;&emsp;buy[i]表示截至第i天，最后一个操作是买时的最大收益；  
&emsp;&emsp;cool[i]表示截至第i天，最后一个操作是冷冻期时的最大收益；  
&emsp;&emsp;递推公式：
```  
sell[i] = max(buy[i-1]+prices[i], sell[i-1]) (第一项表示第i天卖出，第二项表示第i天冷冻)  
buy[i] = max(cool[i-1]-prices[i], buy[i-1])  (第一项表示第i天买进，第二项表示第i天冷冻)  
cool[i] = max(sell[i-1], cool[i-1])          (第一项表示第i天卖出，从而变为冷冻期，第二项表示第i天冷冻)  
```
&emsp;&emsp;此外还要注意数组长度只有1个的时候是不买的，利润为0。其他情况buy[0]一定是prices[0]的相反数。
#### 代码
```Java
class Solution {
    public int maxProfit(int[] prices) {
        int[] sell=new int[prices.length];
        int[] buy=new int[prices.length];
        int[] cool=new int[prices.length];
        if(prices==null||prices.length<2)
            return 0;
        buy[0]=-prices[0];
        for(int i=1;i<prices.length;i++)
        {
            sell[i]=Math.max(buy[i-1]+prices[i],sell[i-1]);
            buy[i]=Math.max(cool[i-1]-prices[i],buy[i-1]);
            cool[i]=Math.max(sell[i-1], cool[i-1]);
        }
        return sell[prices.length-1];
    }
}
```
### 22. 需要交易费用的股票交易

&emsp;&emsp;https://leetcode-cn.com/problems/best-time-to-buy-and-sell-stock-with-transaction-fee/

#### 思路
&emsp;&emsp;和上题差不多，只是本次在每次卖出时需要加手续费，以及没有冷却期。
#### 代码
```Java
class Solution {
    public int maxProfit(int[] prices, int fee) {
        int[] sell=new int[prices.length];
        int[] buy=new int[prices.length];
        if(prices==null||prices.length<2)
            return 0;
        buy[0]=-prices[0];
        for(int i=1;i<prices.length;i++)
        {
            sell[i]=Math.max(buy[i-1]+prices[i]-fee,sell[i-1]);
            buy[i]=Math.max(sell[i-1]-prices[i],buy[i-1]);
        }
        return sell[prices.length-1];
    }
}
```
### 23. 只能进行两次的股票交易

&emsp;&emsp;https://leetcode-cn.com/problems/best-time-to-buy-and-sell-stock-iii/

#### 思路
&emsp;&emsp;对于任意一天考虑四个变量:  
&emsp;&emsp;fstBuy: 在该天第一次买入股票可获得的最大收益。   
&emsp;&emsp;fstSell: 在该天第一次卖出股票可获得的最大收益。  
&emsp;&emsp;secBuy: 在该天第二次买入股票可获得的最大收益。  
&emsp;&emsp;secSell: 在该天第二次卖出股票可获得的最大收益。  
&emsp;&emsp;分别对四个变量进行相应的更新, 最后secSell就是最大。  
#### 代码
```Java
class Solution {
    public int maxProfit(int[] prices) {
        int fstBuy = Integer.MIN_VALUE, fstSell = 0;
        int secBuy = Integer.MIN_VALUE, secSell = 0;
        for(int i=0;i<prices.length;i++)
        {
            int p=prices[i];
            fstBuy = Math.max(fstBuy, -p);
            fstSell = Math.max(fstSell, fstBuy + p);
            secBuy = Math.max(secBuy, fstSell - p);
            secSell = Math.max(secSell, secBuy + p); 
        }
        return secSell;
    }
}
```
### 24. 只能进行 k 次的股票交易

&emsp;&emsp;https://leetcode-cn.com/problems/best-time-to-buy-and-sell-stock-iv/

#### 思路
&emsp;&emsp;对上一题进行推广，对每一个买卖次数进行遍历即可。  
&emsp;&emsp;但是由于K不固定，直接DP会MLE，所以对于K大于数组长度一半的情况下，即每天都可以考虑买入卖出的情况下，用贪心即可，既节省空间，又节省时间。
#### 代码
```Java
class Solution {
    public int maxProfit(int k, int[] prices) {
        if(k < 1) return 0;
        if(k >= prices.length/2) return greedy(prices);
        int[][] t = new int[k][2];
        for(int i = 0; i < k; i++)
            t[i][0] = Integer.MIN_VALUE;
        for(int i=0;i<prices.length;i++)
        {
            int p=prices[i];
            t[0][0] = Math.max(t[0][0], -p);
            t[0][1] = Math.max(t[0][1], t[0][0] + p);
            for(int j=1;j<k;j++)
            {
                t[j][0]=Math.max(t[j][0],t[j-1][1]-p);
                t[j][1]=Math.max(t[j][1],t[j][0]+p);
            }
        }
        return t[k-1][1];
    }
    private int greedy(int[] prices) {
        int max = 0;
        for(int i = 1; i < prices.length; ++i) {
            if(prices[i] > prices[i-1])
                max += prices[i] - prices[i-1];
        }
        return max;
    }
}
```
### 25. 删除两个字符串的字符使它们相等

&emsp;&emsp;https://leetcode-cn.com/problems/delete-operation-for-two-strings/

#### 思路
&emsp;&emsp;实际上还是求最长公共子序列，然后用两个字符串长度和减去就是最少删除步数。
#### 代码
```Java
class Solution {
    public int minDistance(String word1, String word2) {
        int m = word1.length(), n = word2.length();
        int[][] dp = new int[m + 1][n + 1];
        for (int i = 1; i <= m; i++) {
            for (int j = 1; j <= n; j++) {
                if (word1.charAt(i - 1) == word2.charAt(j - 1)) {
                    dp[i][j] = dp[i - 1][j - 1] + 1;
                } else {
                    dp[i][j] = Math.max(dp[i][j - 1], dp[i - 1][j]);
                }
            }
        }
        return m + n - 2 * dp[m][n];
    }
}
```
### 26. 编辑距离

&emsp;&emsp;https://leetcode-cn.com/problems/edit-distance/

#### 思路
&emsp;&emsp;如果我们用`dp[i][j]`代表从`word1[0~i]`到`word2[0~j`所需的步数。那么如果要到达`dp[i][j]`的状态，一共可由三种状态转移而来:
1. `从dp[i-1][j-1]`转移而来，如果`从dp[i-1][j-1]=k`，那么如果`word1[i]=word2[j]`，那么仍然只需k步，否则需要替换一个字符，K+1步。
2. `从dp[i][j-1]`转移而来，如果`从dp[i][j-1]=k`，那么我们需要插入一个`word2[j]`即可达到要求,故需要K+1步。
3. `从dp[i-1][j]`转移而来，如果`从dp[i-1][j-1]=k`，那么我们需要删掉一个`word1[i]`即可达到要求，故需要K+1步。

&emsp;&emsp;最后需要注意，将dp数组初始化时上边界和左边界都置为i，因为对于任意一个字符串为空的情况下，将另一个字符串转换过来或转换为另一个字符串，都需要进行字符串长度步数的操作。
#### 代码
```Java
class Solution {
    public int minDistance(String word1, String word2) {
        if (word1 == null || word2 == null) {
            return 0;
        }
        int m = word1.length(), n = word2.length();
        int[][] dp = new int[m + 1][n + 1];
        for (int i = 1; i <= m; i++) {
            dp[i][0] = i;
        }
        for (int i = 1; i <= n; i++) {
            dp[0][i] = i;
        }
        for (int i = 1; i <= m; i++) {
            for (int j = 1; j <= n; j++) {
                if (word1.charAt(i - 1) == word2.charAt(j - 1)) {
                    dp[i][j] = dp[i - 1][j - 1];
                } else {
                    dp[i][j] = Math.min(dp[i - 1][j - 1], Math.min(dp[i][j - 1], dp[i - 1][j])) + 1;
                }
            }
        }
        return dp[m][n];
    }
}
```
### 27. 复制粘贴字符

&emsp;&emsp;https://leetcode-cn.com/problems/2-keys-keyboard/

#### 思路
&emsp;&emsp;对于任意一个数，可以按照是否为质数来区分。
1. 如果他是质数，那就不能通过复制得到，只能每次粘贴1来打到，所以需要n步。
2. 如果不是质数，就可以表示为两个数的乘积A*B，即构建长度为A的序列，然后复制B次(或者相反)，所以步数为A+B。
3. 向下分解，如果AB中有不是质数的，例如B，依然可以表示为m*n，向下一直分解至质数。

&emsp;&emsp;故实际上就是将n分解为m个质数的乘积，且这m个质数的和最小。

&emsp;&emsp;如果用DP的方式，那么对于`dp[i]`，可以转移过来的状态只有当其可以分为A*B时，其`dp[i]=dp[A]+dp[B]`。  
&emsp;&emsp;如果用递归的方式，需要从小到大找因数分解。
#### 代码
```Java
//DP版本
class Solution {
    public int minSteps(int n) {
        int[] dp = new int[n + 1];
        int h = (int) Math.sqrt(n);
        for (int i = 2; i <= n; i++) {
            dp[i] = i;
            for (int j = 2; j <= h; j++) {
                if (i % j == 0) {
                    dp[i] = dp[j] + dp[i / j];
                    break;
                }
            }
        }
        return dp[n];
    }
}
//递归版本
class Solution {
    public int minSteps(int n) {
        if (n == 1) return 0;
        for (int i = 2; i <= Math.sqrt(n); i++) {
            if (n % i == 0) return i + minSteps(n / i);
        }
        return n;
    }
}
```
## 八、数学 
### 1. 生成素数序列

&emsp;&emsp;https://leetcode-cn.com/problems/count-primes/

#### 思路
&emsp;&emsp;埃拉托斯特尼筛法(sieve of Eratosthenes)
```
埃拉托斯特尼筛法，简称埃氏筛或爱氏筛，是一种由希腊数学家埃拉托斯特尼所提出的一种简单检定素数的算法。要得到自然数n以内的全部素数，必须把不大于根号n的所有素数的倍数剔除，剩下的就是素数。
```
#### 代码
```Java
class Solution {
    public int countPrimes(int n) {
         boolean[] notPrimes = new boolean[n + 1];
        int count = 0;
        for (int i = 2; i < n; i++) {
            if (notPrimes[i]) {
                continue;
            }
            count++;
            for (long j = (long) (i) * i; j < n; j += i) {
                notPrimes[(int) j] = true;
            }
        }
        return count;
    }
}
```
### 2. 7进制

&emsp;&emsp;https://leetcode-cn.com/problems/base-7/

#### 思路
&emsp;&emsp;与二进制一样，只需%7然后进位即可。
#### 代码
```Java
class Solution {
    public String convertToBase7(int num) {
        if (num == 0) {
            return "0";
        }
        StringBuilder sb = new StringBuilder();
        boolean isNegative = num < 0;
        if (isNegative) {
            num = -num;
        }
        while (num > 0) {
            sb.append(num % 7);
            num /= 7;
        }
        String ret = sb.reverse().toString();
        return isNegative ? "-" + ret : ret;
    }
}
//Java 中 static String toString(int num, int radix) 可以将一个整数转换为 radix 进制表示的字符串。

public String convertToBase7(int num) {
    return Integer.toString(num, 7);
}
```
### 3. 16 进制

&emsp;&emsp;https://leetcode-cn.com/problems/convert-a-number-to-hexadecimal/

#### 思路
&emsp;&emsp;使用0x0b1111获取num的低4位。  
&emsp;&emsp;算数位移，其中正数右移左边补0，负数右移左边补1。   
&emsp;&emsp;位移运算并不能保证num==0，需要使用32位int保证（对应16进制小于等于8位）。  
#### 代码
```Java
class Solution {
    public String toHex(int num) {
        char[] map = {'0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f'};
        if (num == 0) return "0";
        StringBuilder sb = new StringBuilder();
        while (num != 0) {
            sb.append(map[num & 0b1111]);
            num >>>= 4;
        }
        return sb.reverse().toString();
    }
}
```
### 4. 26 进制

&emsp;&emsp;https://leetcode-cn.com/problems/excel-sheet-column-title/

#### 思路
&emsp;&emsp;类似7进制，但是数据是从1开始，要先减1。
#### 代码
```Java
class Solution {
    public String convertToTitle(int n) {
        if (n <= 0) {
            return "";
        }
        StringBuilder sb = new StringBuilder();
        while (n > 0) {
            n--;
            sb.append((char) (n % 26 + 'A'));
            n =n / 26;
        }
        return sb.reverse().toString();
    }
}
```
### 5. 统计阶乘尾部有多少个 0

&emsp;&emsp;https://leetcode-cn.com/problems/factorial-trailing-zeroes/

#### 思路
&emsp;&emsp;有一个10尾部就有一个0，而10必然可以分解为2*5，2的数量明显多于5的数量，因此只要统计有多少个5即可。
#### 代码
```Java
class Solution {
    public int trailingZeroes(int n) {
        return n == 0 ? 0 : n / 5 + trailingZeroes(n / 5);
    }
}
```
### 6. 二进制加法

&emsp;&emsp;https://leetcode-cn.com/problems/add-binary/

#### 思路
&emsp;&emsp;从后向前遍历，用一个变量代表当前位的值，分别加两个字符串的该位置，然后%2就是当前位加完之后的数，/2就是需要进位的的数。
#### 代码
```Java
class Solution {
    public String addBinary(String a, String b) {
        int i = a.length() - 1, j = b.length() - 1, carry = 0;
        StringBuilder str = new StringBuilder();
        while (carry == 1 || i >= 0 || j >= 0) {
            if (i >= 0 && a.charAt(i--) == '1') {
                carry++;
            }
            if (j >= 0 && b.charAt(j--) == '1') {
                carry++;
            }
            str.append(carry % 2);
            carry /= 2;
        }
        return str.reverse().toString();
    }
}
```
### 7. 字符串加法

&emsp;&emsp;https://leetcode-cn.com/problems/add-strings/

#### 思路
&emsp;&emsp;通上题，把2进位改成10进位即可。
#### 代码
```Java
class Solution {
    public String addStrings(String num1, String num2) {
        StringBuilder str = new StringBuilder();
        int carry = 0, i = num1.length() - 1, j = num2.length() - 1;
        while (carry == 1 || i >= 0 || j >= 0) {
            int x = i < 0 ? 0 : num1.charAt(i--) - '0';
            int y = j < 0 ? 0 : num2.charAt(j--) - '0';
            str.append((x + y + carry) % 10);
            carry = (x + y + carry) / 10;
        }
        return str.reverse().toString();
    }
}
```
### 8. 改变数组元素使所有的数组元素都相等

&emsp;&emsp;https://leetcode-cn.com/problems/minimum-moves-to-equal-array-elements-ii/

#### 思路
&emsp;&emsp;在我们将数组排序之后，移动距离最小的方式是所有元素都移动到中位数。
#### 代码
```Java
class Solution {
    public int minMoves2(int[] nums) {
        Arrays.sort(nums);
        int i=0,j=nums.length-1;
        int res=0;
        while(i<j)
        {
            res+=nums[j--]-nums[i++];
        } 
        return res;
    }
}
```
### 9. 数组中出现次数多于 n / 2 的元素

&emsp;&emsp;https://leetcode-cn.com/problems/majority-element/

#### 思路
&emsp;&emsp;有两种解决方式，一种是简单的，将数组排序，排序之后的中位数一定是超过半数的元素。   
&emsp;&emsp;另一种是利用数学思想，这里用到了摩尔投票算法(Moore majority vote algorithm)。   
&emsp;&emsp;摩尔投票算法：
```
摩尔投票法的基本思想很简单，在每一轮投票过程中，从数组中找出一对不同的元素，将其从数组中删除。这样不断的删除直到无法再进行投票，如果数组为空，则没有任何元素出现的次数超过该数组长度的一半。如果只存在一种元素，那么这个元素则可能为目标元素。
```
&emsp;&emsp;在这道题中，我们从数组头部开始遍历，用一个数来做计数器。  
&emsp;&emsp;取一个标记数，从下一个数开始遍历，如果相同，让计数器+1，来统计一下先前有多少个一样的标记数。  
&emsp;&emsp;如果不同，让计数器-1，代表我们从数组中删除了一对这样的数。  
&emsp;&emsp;如果计数器到0了，说明先前遍历的部分已经让我们删光了，我们要取一个新的标记数。  
&emsp;&emsp;最后剩下的标记数，一定是所需的目标元素。
>而且用摩尔投票法可以解决一般性的频率最高数的问题，而无需一定要大于n/2。
#### 代码
```Java
class Solution {
    public int majorityElement(int[] nums) {
        if(nums==null||nums.length==0)
            return 0;
        int flag=nums[0],count=1;
        for(int i=1;i<nums.length;i++)
        {
            if(flag==nums[i])
                count++;
            else
            {
                count--;
                if(count==0)
                {
                    flag=nums[i];
                    count=1;
                }
            }
        }
        return flag;
    }
}
```
### 10. 平方数

&emsp;&emsp;https://leetcode-cn.com/problems/valid-perfect-square/

#### 思路
&emsp;&emsp;数学定理：
```
完全平方数是一系列奇数之和
1 = 1
4 = 1 + 3
9 = 1 + 3 + 5
16 = 1 + 3 + 5 + 7
25 = 1 + 3 + 5 + 7 + 9
36 = 1 + 3 + 5 + 7 + 9 + 11
....
1+3+...+(2n-1) = (2n-1 + 1)n/2 = n*n
```
#### 代码
```Java
class Solution {
    public boolean isPerfectSquare(int num) {
        for(int i=1;num>=0;i+=2)
        {
            if(num==0)
                return true;
            num-=i;
        }
        return false;
    }
}
```
### 11. 3 的 n 次方

&emsp;&emsp;https://leetcode-cn.com/problems/power-of-three/

#### 思路
&emsp;&emsp;通用方法是不断地/3,看会不会到1。  
&emsp;&emsp;数学思想的方法：
```
3的幂次的质因子只有3，题目整数范围内的3的最大幂次是1162261467。
如果N是3的幂次，那么N一定是1162261467的因子，如果不是，那么也就不是因子。
```
#### 代码
```Java
//通用方法
class Solution {
    public boolean isPowerOfThree(int n) {
        if(n<=0)
            return false;
        if(n==1)
            return true;
        while(n%3==0)
        {
            if(n/3==1)
                return true;
            n/=3;
        }
        return false;
    }
}
//数学思想
class Solution {
    public boolean isPowerOfThree(int n) {
        return n > 0 && (1162261467 % n == 0);
    }
}
```
### 12. 乘积数组

&emsp;&emsp;https://leetcode-cn.com/problems/product-of-array-except-self/

#### 思路
&emsp;&emsp;因为不能用除法，所以不能求总乘积再除。

&emsp;&emsp;故选用一个数组，从左向右遍历一次，output[i]保存output[i]左侧所有数的乘积。  
&emsp;&emsp;从右向左遍历一次，output[i]再乘上右侧所有数的乘积。

&emsp;&emsp;两次遍历之后，output就存下了所需的内容。
#### 代码
```Java
class Solution {
    public int[] productExceptSelf(int[] nums) {
        if(nums==null||nums.length==0)
            return null;
        int[] output=new int[nums.length];
        int left=1,right=1;
        for(int i=0;i<nums.length;i++)
        {
            output[i]=1;
        }
        for(int i=0;i<nums.length;i++)
        {
            output[i]=left;
            left*=nums[i];
        }
        for(int i=nums.length-1;i>=0;i--)
        {
            output[i]*=right;
            right*=nums[i];
        }
        return output;
    }
}
```
### 13. 找出数组中的乘积最大的三个数

&emsp;&emsp;https://leetcode-cn.com/problems/maximum-product-of-three-numbers/

#### 思路
&emsp;&emsp;先排序，然后最大值只会有两种情况：
1. 最大的三个正数乘积。
2. 最大的一个正数和最小的两个负数乘积。

#### 代码
```Java
class Solution {
    public int maximumProduct(int[] nums) {
        Arrays.sort(nums);
        int n=nums.length;
        return Math.max(nums[n-1]*nums[n-2]*nums[n-3],nums[0]*nums[1]*nums[n-1]);
    }
}
```
# 第二部分 数据结构相关
## 一、链表
### 1. 找出两个链表的交点

&emsp;&emsp;https://leetcode-cn.com/problems/intersection-of-two-linked-lists/

#### 思路
&emsp;&emsp;两个单链表一定有共同的尾部，所以差异只是在前面，只要找到前面差的长度，然后补全差异之后同时向后走就可以找到交点。

&emsp;&emsp;CYC用A的链表接上B，B的链表接上A，这样就不用手动寻找长度差异了。
#### 代码
```Java
public class Solution {
    public ListNode getIntersectionNode(ListNode headA, ListNode headB) {
        int Alength=0;
        int Blength=0;
        ListNode temp=null;
        temp=headA;
        while(temp!=null)
        {
            Alength++;
            temp=temp.next;
        }
        temp=headB;
        while(temp!=null)
        {
            Blength++;
            temp=temp.next;
        }
        int diff=Alength-Blength;
        while(diff>0)
        {
            headA=headA.next;
            diff--;
        }
        while(diff<0)
        {
            headB=headB.next;
            diff++;
        }
        while(headA!=headB)
        {
            headA=headA.next;
            headB=headB.next;
        }
        return headA;
    }
    
}
//CYC
public ListNode getIntersectionNode(ListNode headA, ListNode headB) {
    ListNode l1 = headA, l2 = headB;
    while (l1 != l2) {
        l1 = (l1 == null) ? headB : l1.next;
        l2 = (l2 == null) ? headA : l2.next;
    }
    return l1;
}
```
### 2. 链表反转

&emsp;&emsp;https://leetcode-cn.com/problems/reverse-linked-list/

#### 思路
&emsp;&emsp;头部插入
#### 代码
```Java
class Solution {
    public ListNode reverseList(ListNode head) {
        ListNode tail=null;
        while(head!=null)
        {
            ListNode temp=head;
            head=head.next;
            temp.next=tail;
            tail=temp;
        }
        return tail;
    }
}
```
### 3. 归并两个有序的链表

&emsp;&emsp;https://leetcode-cn.com/problems/merge-two-sorted-lists/

#### 思路
&emsp;&emsp;类似归并
#### 代码
```Java
class Solution {
    public ListNode mergeTwoLists(ListNode l1, ListNode l2) {
        ListNode head=new ListNode(0);
        ListNode newlink=head;
        while(l1!=null||l2!=null)
        {
            if(l1==null)
            {
                newlink.next=l2;
                return head.next; 
            }
            else
                if(l2==null)
                {
                    newlink.next=l1;
                    return head.next;
                }
            else
            {
                if(l1.val>l2.val)
                {
                    newlink.next=l2;
                    newlink=newlink.next;
                    l2=l2.next;
                }
                else
                {
                    newlink.next=l1;
                    newlink=newlink.next;
                    l1=l1.next;
                }
            }
        }
        return head.next;
    }
}
```
### 4. 从有序链表中删除重复节点

&emsp;&emsp;https://leetcode-cn.com/problems/remove-duplicates-from-sorted-list/

#### 思路
&emsp;&emsp;一次遍历
#### 代码
```Java
class Solution {
    public ListNode deleteDuplicates(ListNode head) {
        ListNode res=head;
        while(res!=null&&res.next!=null)
        {
            if(res.val==res.next.val)
                res.next=res.next.next;
            else
                res=res.next;
        }
        return head;
    }
}
```
### 5. 删除链表的倒数第 n 个节点

&emsp;&emsp;https://leetcode-cn.com/problems/remove-nth-node-from-end-of-list/

#### 思路
&emsp;&emsp;为了不进行两次遍历，可以用快慢指针，让快指针先走N步，在快指针到结尾时慢指针即是要删除的节点。  
&emsp;&emsp;如果快指针走到了尾部，说明要删除的是头结点，直接返回head.next即可。
#### 代码
```Java
class Solution {
    public ListNode removeNthFromEnd(ListNode head, int n) {
        if(head==null||head.next==null)
            return null;
        ListNode fast=head;
        for(int i=0;i<n;i++)
        {
            fast=fast.next;
        }
        ListNode slow=head;
        if(fast==null)
            return head.next;
        while(fast.next!=null)
        {
            fast=fast.next;
            slow=slow.next;
        }
        slow.next=slow.next.next;
        return head;
    }
}
```
### 6. 交换链表中的相邻结点

&emsp;&emsp;https://leetcode-cn.com/problems/swap-nodes-in-pairs/

#### 思路
&emsp;&emsp;两两交换，交换之后跳过一个。  
&emsp;&emsp;为了交换需要之前前一个节点的指针，故建立一个先导节点。
#### 代码
```Java
class Solution {
    public ListNode swapPairs(ListNode head) {
        ListNode beforehead=new ListNode(0);
        beforehead.next=head;
        ListNode temp=null;
        int count=0;
        ListNode p=beforehead;
        while(p.next!=null&&p.next.next!=null)
        {
            if(count==1)
            {
                count=0;
                p=p.next.next;
            }
            else
            {
                count++;
                temp=p.next.next;
                p.next.next=p.next.next.next;
                temp.next=p.next;
                p.next=temp;
            }
        }
        return beforehead.next;
    }
}
```
### 7. 链表求和

&emsp;&emsp;https://leetcode-cn.com/problems/add-two-numbers-ii/

#### 思路
&emsp;&emsp;用栈来反转链表，然后倒序计算构建新链表。
#### 代码
```Java
class Solution {
    public ListNode addTwoNumbers(ListNode l1, ListNode l2) {
        Stack<Integer> l1Stack = buildStack(l1);
        Stack<Integer> l2Stack = buildStack(l2);
        ListNode head = new ListNode(-1);
        int carry = 0;
        while (!l1Stack.isEmpty() || !l2Stack.isEmpty() || carry != 0) {
            int x = l1Stack.isEmpty() ? 0 : l1Stack.pop();
            int y = l2Stack.isEmpty() ? 0 : l2Stack.pop();
            int sum = x + y + carry;
            ListNode node = new ListNode(sum % 10);
            node.next = head.next;
            head.next = node;
            carry = sum / 10;
        }
        return head.next;
    }

    private Stack<Integer> buildStack(ListNode l) {
        Stack<Integer> stack = new Stack<>();
        while (l != null) {
            stack.push(l.val);
            l = l.next;
        }
        return stack;
    }
}
```
### 8. 回文链表

&emsp;&emsp;https://leetcode-cn.com/problems/palindrome-linked-list/

#### 思路
&emsp;&emsp;快慢指针，2倍速前进，获得中点，截断反转，同步判断。
#### 代码
```Java
class Solution {
    public boolean isPalindrome(ListNode head) {
         if (head == null || head.next == null) {
            return true;
        }
        ListNode fast=head;
        ListNode slow=head;
        while(fast.next!=null&&fast.next.next!=null)
        {
            slow=slow.next;
            fast=fast.next.next;
        }
        slow=reverse(slow.next);
        while(slow!=null)
        {
            if(head.val!=slow.val)
                return false;
            head=head.next;
            slow=slow.next;
        }
        return true;
    }
    private ListNode reverse(ListNode head) {
        ListNode newHead = null;
        while (head != null) {
            ListNode nextNode = head.next;
            head.next = newHead;
            newHead = head;
            head = nextNode;
        }
        return newHead;
    }
}
```
### 9. 分隔链表

&emsp;&emsp;https://leetcode-cn.com/problems/split-linked-list-in-parts/

### 思路
&emsp;&emsp;先遍历求一次链表长度N，然后因为要分隔K段，所以每段长度为N/k,多余的节点数为N%K,把这个N%K个节点给前N%K段每段的长度+1即可。
### 代码
```Java
class Solution {
    public ListNode[] splitListToParts(ListNode root, int k) {
        int N = 0;
        ListNode cur = root;
        while (cur != null) {
            N++;
            cur = cur.next;
        }
        int mod = N % k;
        int size = N / k;
        ListNode[] ret = new ListNode[k];
        cur = root;
        for (int i = 0; cur != null && i < k; i++) {
            ret[i] = cur;
            int curSize = size + (mod-- > 0 ? 1 : 0);
            for (int j = 0; j < curSize - 1; j++) {
                cur = cur.next;
            }
            ListNode next = cur.next;
            cur.next = null;
            cur = next;
        }
        return ret;
    }
}
```
### 10. 链表元素按奇偶聚集

&emsp;&emsp;https://leetcode-cn.com/problems/odd-even-linked-list/

#### 思路
&emsp;&emsp;奇数节点的next是偶数节点指针的next，偶数节点的next是奇数节点指针的next。
#### 代码
```Java
class Solution {
    public ListNode oddEvenList(ListNode head) {
        if(head==null||head.next==null||head.next.next==null)
            return head;
        ListNode singlehead=head;
        ListNode midhead=head.next;
        ListNode doublehead=head.next;
        while (singlehead.next != null && doublehead.next != null) {
            singlehead.next = doublehead.next;
            singlehead =singlehead.next;
            doublehead.next = singlehead.next;
            doublehead = doublehead.next;
        }
        singlehead.next=midhead;
        return head;
    }
}
```
## 二、树
### 1. 树的高度

&emsp;&emsp;https://leetcode-cn.com/problems/maximum-depth-of-binary-tree/submissions/

#### 思路
&emsp;&emsp;递归求解
#### 代码
```Java
class Solution {
    public int maxDepth(TreeNode root) {
        if (root == null) 
            return 0;
        return Math.max(maxDepth(root.left), maxDepth(root.right)) + 1;
    }
}
```
### 2. 平衡树

&emsp;&emsp;https://leetcode-cn.com/problems/balanced-binary-tree/

#### 思路
&emsp;&emsp;递归查找左右子树高度，比较判断高度差。
#### 代码
```Java
class Solution {
    public boolean balance=true;
    public boolean isBalanced(TreeNode root) {
        maxdepth(root);
        return balance;
    }
    int maxdepth(TreeNode root)
    {
        if(root==null)
            return 0;
        int left=maxdepth(root.left);
        int right=maxdepth(root.right);
        if((left-right)>1||(left-right)<-1)
            balance=false;
        return Math.max(left,right)+1;
    }
}
```
### 3. 两节点的最长路径

&emsp;&emsp;https://leetcode-cn.com/problems/diameter-of-binary-tree/

#### 思路
&emsp;&emsp;最长长度一定是某个节点的左右子树长度之和，故只需查找左右子树高度，然后相加找最大值。
#### 代码
```Java
class Solution {
    int max=0;
    public int diameterOfBinaryTree(TreeNode root) {
        maxdepth(root);
        return max;
    }
    int maxdepth(TreeNode root)
    {
        if(root==null)
            return 0;
        int left=maxdepth(root.left);
        int right=maxdepth(root.right);
        max=Math.max(max,left+right);
        return Math.max(left,right)+1;
    }
}
```
### 4. 翻转树

&emsp;&emsp;https://leetcode-cn.com/problems/invert-binary-tree/

#### 思路
&emsp;&emsp;交换左右子树即可。
#### 代码
```Java
class Solution {
    public TreeNode invertTree(TreeNode root) {
        if (root == null) 
            return null;
        // 后面的操作会改变 left 指针，因此先保存下来
        TreeNode left = root.left;  
        root.left = invertTree(root.right);
        root.right = invertTree(left);
        return root;
    }
}
```
### 5. 归并两棵树

&emsp;&emsp;https://leetcode-cn.com/problems/merge-two-binary-trees/

#### 思路
&emsp;&emsp;递归同时遍历两棵树，相同位置节点值相加,如果有一边已经为空，则直接返回节点。
#### 代码
```Java
class Solution {
    public TreeNode mergeTrees(TreeNode t1, TreeNode t2) {
        if(t1==null)
            return t2;
        if(t2==null)
            return t1;
        t1.val=t1.val+t2.val;
        t1.left=mergeTrees(t1.left,t2.left);
        t1.right=mergeTrees(t1.right,t2.right);
        return t1;
    }
}
```
### 6. 判断路径和是否等于一个数

&emsp;&emsp;https://leetcode-cn.com/problems/path-sum/

#### 思路
&emsp;&emsp;左右递归遍历，有一路为真即可，遍历时每层剪掉当前值，看是否为0。
#### 代码
```Java
class Solution {
    public boolean hasPathSum(TreeNode root, int sum) {
        if (root == null) 
           return false;
        if (root.left == null && root.right == null && root.val == sum) 
            return true;
        return hasPathSum(root.left, sum - root.val) || hasPathSum(root.right, sum - root.val);
    }
}
```
### 7. 统计路径和等于一个数的路径数量

&emsp;&emsp;https://leetcode-cn.com/problems/path-sum-iii/

#### 思路
&emsp;&emsp;双重递归，一方面递归所有节点，另一方面递归从当前节点出发，是否有路径满足需求。
#### 代码
```Java
class Solution {
    public int pathSum(TreeNode root, int sum) {
        if(root==null)
            return 0;
        return find(root,sum)+pathSum(root.left,sum)+pathSum(root.right,sum);
    }
    public int find(TreeNode root,int sum)
    {
        if(root==null)
            return 0;
        sum-=root.val;
        int count=0;
        if(sum==0)
            count++;
        count+=find(root.left,sum)+find(root.right,sum);
        return count;
    }
}
```
### 8. 子树

&emsp;&emsp;https://leetcode-cn.com/problems/subtree-of-another-tree/

#### 思路
&emsp;&emsp;双重递归，一次递归所有节点，另一次递归所有从当前节点出发的子树是否和t一样。
#### 代码
```Java
class Solution {
    public boolean issame=false;
    public boolean isSubtree(TreeNode s, TreeNode t) {
        if(s==null&&t==null)
            return true;
        if(s==null&&t!=null)
            return false;
        if(s!=null&&t==null)
            return true;
        return compare(s,t)||isSubtree(s.left,t)||isSubtree(s.right,t);
    }
    public boolean compare(TreeNode s,TreeNode t)
    {
        if(s==null&&t==null)
            return true;
        if(s==null||t==null)
            return false;
        if(s.val!=t.val)
            return false;
        return compare(s.left,t.left)&&compare(s.right,t.right);
    }
}
```
### 9. 树的对称

&emsp;&emsp;https://leetcode-cn.com/problems/symmetric-tree/

#### 思路
&emsp;&emsp;左右子树对称判断是否相等
#### 代码
```Java
class Solution {
    public boolean isSymmetric(TreeNode root) {
        if(root==null)
            return true;
        return issame(root.left,root.right);
    }
    public boolean issame(TreeNode left,TreeNode right)
    {
        if(left==null&&right==null)
            return true;
        if(left==null||right==null)
            return false;
        if(left.val!=right.val)
            return false;
        return issame(left.left,right.right)&&issame(left.right,right.left);
    }
}
```
### 10. 最小路径

&emsp;&emsp;https://leetcode-cn.com/problems/minimum-depth-of-binary-tree/

#### 思路
&emsp;&emsp;向两侧遍历左子树和右子树的深度，取最小值。  
&emsp;&emsp;当遍历到有子节点为空时，两侧皆空为叶子结点，返回0，一侧为空则不符合要求，还需遍历另一侧。
#### 代码
```Java
class Solution {
    public int minDepth(TreeNode root) {
        if (root == null) 
            return 0;
        int left = minDepth(root.left);
        int right = minDepth(root.right);
        if (left == 0 || right == 0) 
            return left + right + 1;
        return Math.min(left, right) + 1;
    }
}
```
### 11. 统计左叶子节点的和

&emsp;&emsp;https://leetcode-cn.com/problems/sum-of-left-leaves/

#### 思路
&emsp;&emsp;用一个辅助函数判断当前节点是否为叶子节点，然后遍历这棵树，对每个节点的左子节点进行一次判断，如果是叶子节点就进行累加。
#### 代码
```Java
class Solution {
    public int sumOfLeftLeaves(TreeNode root) {
        if (root == null) 
            return 0;
        if (isLeaf(root.left)) 
            return root.left.val + sumOfLeftLeaves(root.right);
        return sumOfLeftLeaves(root.left) + sumOfLeftLeaves(root.right);
    }

    private boolean isLeaf(TreeNode node){
        if (node == null) 
            return false;
        return node.left == null && node.right == null;
    }
}
```
### 12. 相同节点值的最大路径长度

&emsp;&emsp;https://leetcode-cn.com/problems/longest-univalue-path/comments/

#### 思路
&emsp;&emsp;对于每个节点，分别遍历左子树与当前节点值相等的长度，右子树与当前节点值相等的长度，然后累和取得当前同值路径长度，再取最大值。
#### 代码
```Java
class Solution {
    public int res=0;
    public int longestUnivaluePath(TreeNode root) {
        findmax(root);
        return res;
    }
    public int findmax(TreeNode root)
    {
        if(root==null)
            return 0;
        int leftlength=findmax(root.left)+1;
        int rightlength=findmax(root.right)+1;
        if(root.left==null||root.left.val!=root.val)
            leftlength=0;
        if(root.right==null||root.right.val!=root.val)
            rightlength=0;
        res=Math.max(res,leftlength+rightlength);
        return Math.max(leftlength,rightlength);
    }
}
```
### 13. 间隔遍历

&emsp;&emsp;https://leetcode-cn.com/problems/house-robber-iii/

#### 思路
&emsp;&emsp;对于当前节点，一共有两种打劫的选择：
1. 打劫当前节点，则收益为当前节点+当前节点的左子节点的左右子节点+当前节点的右节点的左右子节点。
2. 不打劫当前节点，则收益为当前节点的左子节点收益+右子节点收益。

#### 代码
```Java
class Solution {
    public int rob(TreeNode root) {
        if(root==null)
            return 0;
        int option1=root.val;
        if(root.left!=null)
            option1+=rob(root.left.left)+rob(root.left.right);
        if(root.right!=null)
            option1+=rob(root.right.left)+rob(root.right.right);
        int option2=rob(root.left)+rob(root.right);
        return Math.max(option1,option2);
    }
}
```
### 14. 找出二叉树中第二小的节点

&emsp;&emsp;https://leetcode-cn.com/problems/second-minimum-node-in-a-binary-tree/

#### 思路
&emsp;&emsp;因为二叉树的中任何一个节点的值一定不大于其子节点，所以我们从根节点开始左右遍历，分别找到第一个不相同的值，然后比较两侧大小即可。
#### 代码
```Java
class Solution {
    public int min=-1;
    public int findSecondMinimumValue(TreeNode root) {
        if(root==null)
            return min;
        if(root.left!=null)
        {
            if(root.left.val!=root.val)
            {
                if(min==-1)
                    min=root.left.val;
                else
                    min=Math.min(min,root.left.val);
            }
            else
                findSecondMinimumValue(root.left);
                
        }
        if(root.right!=null)
        {
            if(root.right.val!=root.val)
            {
                if(min==-1)
                    min=root.right.val;
                else
                    min=Math.min(min,root.right.val);
            }
            else
                findSecondMinimumValue(root.right);
        }
        return min;
    }
}
```
### 15. 一棵树每层节点的平均数

&emsp;&emsp;https://leetcode-cn.com/problems/average-of-levels-in-binary-tree/

#### 思路
&emsp;&emsp;用Double的List存每层的平均值，用Int的List存每层的节点个数，然后遍历计算。
#### 代码
```Java
class Solution {
    List<Double> res=new ArrayList<Double>();
    List<Integer> count=new ArrayList<Integer>();
    public List<Double> averageOfLevels(TreeNode root) {
        cal(root,0);
        return res;
    }
    public void cal(TreeNode root,int depth)
    {
        if(root==null)
            return ;
        if(res.size()<=depth)
        {
            res.add((double)root.val);
            count.add(1);
        }
        else
        {
            int curcount=count.get(depth);
            double curvalue=res.get(depth);
            res.set(depth,(curvalue*curcount+root.val)/(curcount+1));
            count.set(depth,curcount+1); 
        }
        cal(root.left,depth+1);
        cal(root.right,depth+1);
    }
}
```
### 16. 得到左下角的节点

&emsp;&emsp;https://leetcode-cn.com/problems/find-bottom-left-tree-value/

#### 思路
&emsp;&emsp;用辅助List，存先序遍历每个深度的第一个节点即可。
#### 代码
```Java
class Solution {
    List<Integer> res=new ArrayList<>();
    public int findBottomLeftValue(TreeNode root) {
        assist(root,0);
        return res.get(res.size()-1);
    }
    public void assist(TreeNode root,int depth)
    {
        if(root==null)
            return;
        if(res.size()<=depth)
        {
            res.add(root.val);
        }
        assist(root.left,depth+1);
        assist(root.right,depth+1);
    }
}
```
### 17. 非递归实现二叉树的三种遍历
&emsp;&emsp;前序遍历：  
&emsp;&emsp;https://leetcode-cn.com/problems/binary-tree-preorder-traversal/

&emsp;&emsp;后序遍历：  
&emsp;&emsp;https://leetcode-cn.com/problems/binary-tree-postorder-traversal/

&emsp;&emsp;中序遍历：  
&emsp;&emsp;https://leetcode-cn.com/problems/binary-tree-inorder-traversal/

#### 思路
&emsp;&emsp;用迭代就需要用辅助栈控制遍历顺序。  
&emsp;&emsp;前序遍历是`root->left->right`。  
&emsp;&emsp;后序遍历是`left->right->root`,其倒序是`root->right->left`。可以反向构造。  
&emsp;&emsp;中序遍历是`left->root->right`,需要先把左子树全入栈。
#### 代码
```Java
//前序遍历
class Solution {
    public List<Integer> preorderTraversal(TreeNode root) {
        List<Integer> res = new ArrayList<>();
        Stack<TreeNode> stack = new Stack<>();
        stack.push(root);
        while (!stack.isEmpty()) {
            TreeNode node = stack.pop();
            if (node == null) 
                continue;
            res.add(node.val);
            stack.push(node.right);
            stack.push(node.left);
        }
        return res;
    }
}
//后序遍历
class Solution {
    public List<Integer> postorderTraversal(TreeNode root) {
        LinkedList<Integer> res = new LinkedList<>();
        Stack<TreeNode> stack = new Stack<>();
        stack.push(root);
        while (!stack.isEmpty()) {
            TreeNode node = stack.pop();
            if (node == null) 
                continue;
            res.addFirst(node.val);
            stack.push(node.left);
            stack.push(node.right);
        }
        return res;
    }
}
//中序遍历
class Solution {
    public List<Integer> inorderTraversal(TreeNode root) {
        List<Integer> list = new ArrayList<>();
        Stack<TreeNode> stack = new Stack<>();
        TreeNode cur = root;
        while (cur != null || !stack.isEmpty()) {
            if (cur != null) {
                stack.push(cur);
                cur = cur.left;
            } else {
                cur = stack.pop();
                list.add(cur.val);
                cur = cur.right;
            }
        }
        return list;
    }
}
```
### 18. 修剪二叉查找树

&emsp;&emsp;https://leetcode-cn.com/problems/trim-a-binary-search-tree/submissions/

#### 思路
&emsp;&emsp;如果当前节点符合区间，左右向下递归。    
&emsp;&emsp;如果当前节点小于区间，用当前节点右子树代替当前节点，然后递归。  
&emsp;&emsp;如果当前节点大于区间，用当前节点左子树代替当前节点，然后递归。  
#### 代码
```Java
class Solution {
    public TreeNode trimBST(TreeNode root, int L, int R) {
        if(root==null)
            return null;
        if(root.val>=L&&root.val<=R)
        {
            root.left=trimBST(root.left,L,R);
            root.right=trimBST(root.right,L,R);
        }
        else
        {
            if(root.val<L)
            {
                root=trimBST(root.right,L,R);
            }
            else
            {
                root=trimBST(root.left,L,R);
            }
        }
        return root;
    }
}
```
### 19. 寻找二叉查找树的第 k 个元素

&emsp;&emsp;https://leetcode-cn.com/problems/kth-smallest-element-in-a-bst/

#### 思路
&emsp;&emsp;由二叉搜索树性质可知，对其进行中序遍历就可以还原其大小顺序，所以只需进行中序遍历，第K个元素就是第K小的。
#### 代码
```Java
class Solution {
    int count=0;
    int res=0;
    public int kthSmallest(TreeNode root, int k) {
        if (root == null) 
            return res;
        kthSmallest(root.left, k);
        count++;
        if (count == k) {
            res = root.val;
            return res;
        }
        kthSmallest(root.right, k);
        return res;
    }
}
```
### 20. 把二叉查找树每个节点的值都加上比它大的节点的值

&emsp;&emsp;https://leetcode-cn.com/problems/convert-bst-to-greater-tree/

#### 思路
&emsp;&emsp;因为二叉查找树的大小顺序为`left<root<right`,所以按照`right->root->left`的顺序来遍历，并累加上所有遍历过的值即可。
#### 代码
```Java
class Solution {
    int sum=0;
    public TreeNode convertBST(TreeNode root) {
        if(root==null)
            return null;
        convertBST(root.right);
        sum+=root.val;
        root.val=sum;
        convertBST(root.left);
        return root;
    }
}
```
### 21. 二叉查找树的最近公共祖先

&emsp;&emsp;https://leetcode-cn.com/problems/lowest-common-ancestor-of-a-binary-search-tree/

#### 思路
&emsp;&emsp;由二叉搜索树的特性可知，如果p,q都小于root，说明他们都在左子树，如果都大于root，说明都在右子树。
当`p<=root.val<=q`的时候，说明p，q分布在两侧，则Root是其最近的公共祖先。
#### 代码
```Java
class Solution {
    public TreeNode lowestCommonAncestor(TreeNode root, TreeNode p, TreeNode q) {
        if(root==null)
            return null;
        if(p.val>q.val)
        {
            TreeNode temp=p;
            p=q;
            q=temp;
        }
        if(p.val<=root.val&&q.val>=root.val)
            return root;
        if(q.val<root.val)
            return lowestCommonAncestor(root.left,p,q);
        return lowestCommonAncestor(root.right,p,q);
    
    }
}
```
### 22. 二叉树的最近公共祖先

&emsp;&emsp;https://leetcode-cn.com/problems/lowest-common-ancestor-of-a-binary-tree/ 

#### 思路
&emsp;&emsp;对于任意P，Q如果我们递归进行寻找，直到找到P或者Q或者空，就该返回当前节点了，那么在返回之前，我们会遇到三种情况：
1. 左子树递归为空，右子树递归为空，说明两侧都没有，返回null。
2. 左子树递归不为空，右子树递归不为空，说明两侧一边一个节点，那么当前节点就是最近公共祖先，返回当前节点。
3. 有一边为空，说明两个节点都在另一边，递归该侧。

#### 代码
```Java
class Solution {
    public TreeNode lowestCommonAncestor(TreeNode root, TreeNode p, TreeNode q) {
        if(root==null||root==q||root==p)
            return root;
        TreeNode left=lowestCommonAncestor(root.left,p,q);
        TreeNode right=lowestCommonAncestor(root.right,p,q);
        if(left==null&&right==null)
            return null;
        if(left!=null&&right!=null)
            return root;
        if(left==null)
            return right;
        return left;
    }
}
```
### 23. 从有序数组中构造二叉查找树

&emsp;&emsp;https://leetcode-cn.com/problems/convert-sorted-array-to-binary-search-tree/

#### 思路
&emsp;&emsp;因为数组有序，故可以进行二分，用中值做根节点，左半部分构建左子树，右半部分构建右子树。
#### 代码
```Java
class Solution {
    public TreeNode sortedArrayToBST(int[] nums) {
        if(nums==null)
            return null;
        return build(nums,0,nums.length-1);
    }
    TreeNode build(int[] nums,int left,int right)
    {
        if(left>right)
            return null;
        int mid=left+(right-left)/2;
        TreeNode root=new TreeNode(nums[mid]);
        root.left=build(nums,left,mid-1);
        root.right=build(nums,mid+1,right);
        return root;
    }
}
```
### 24. 根据有序链表构造平衡的二叉查找树

&emsp;&emsp;https://leetcode-cn.com/problems/convert-sorted-list-to-binary-search-tree/

#### 思路
&emsp;&emsp;因为单链表有序，所以递归思想是：从单链表中部截断，取中间节点为root，两侧两段单链表分别构成左右子树，进行递归。
#### 代码
```Java
class Solution {
    public TreeNode sortedListToBST(ListNode head) {
        if(head == null) 
            return null;
        if(head.next == null) 
            return new TreeNode(head.val);
        ListNode slow=head;
        ListNode fast=head;
        ListNode beforeslow=null;
        while(fast!=null&&fast.next!=null)
        {
            beforeslow=slow;
            slow=slow.next;
            fast=fast.next.next;
        }        
        TreeNode root=new TreeNode(slow.val);
        beforeslow.next=null;
        root.left=sortedListToBST(head);
        root.right=sortedListToBST(slow.next);
        return root;
    }
}
```
### 25. 在二叉查找树中寻找两个节点，使它们的和为一个给定值

&emsp;&emsp;https://leetcode-cn.com/problems/two-sum-iv-input-is-a-bst/

#### 思路
&emsp;&emsp;遍历二叉树，用辅助List保存所有节点值，因为是二叉搜索树，所以使用中序遍历可以获得有序List。  
&emsp;&emsp;然后查找List即可。
#### 代码
```Java
class Solution {
    List<Integer> nodes=new ArrayList<>();
    public boolean findTarget(TreeNode root, int k) {
        Traversal(root);
        for(int i=0,j=nodes.size()-1;i<j;)
        {
            int sum=nodes.get(i)+nodes.get(j);
            if(sum==k)
                return true;
            if(sum<k)
                i++;
            else
                j--;
        }
        return false;
    }
    public void Traversal(TreeNode root)
    {
        if(root==null)
            return ;
        Traversal(root.left);
        nodes.add(root.val);
        Traversal(root.right);
    }
}
```
### 26. 在二叉查找树中查找两个节点之差的最小绝对值

&emsp;&emsp;https://leetcode-cn.com/problems/minimum-absolute-difference-in-bst/

#### 思路
1. 构造辅助List存放所有节点，然后遍历相邻两位的差值，取最小值。
2. 利用二叉查找树的中序遍历为有序的性质，计算中序遍历中临近的两个节点之差的绝对值，取最小值。

#### 代码
```Java
//辅助List
class Solution {
    List<Integer> nodes=new ArrayList<>();
    public int getMinimumDifference(TreeNode root) {
        Traversal(root);
        if(nodes.size()<2)
            return 0;
        int min=Math.abs(nodes.get(0)-nodes.get(1));
        for(int i=1;i<nodes.size();i++)
        {
            min=Math.min(min,Math.abs(nodes.get(i)-nodes.get(i-1)));
        }
        return min;
    }
    public void Traversal(TreeNode root)
    {
        if(root==null)
            return ;
        Traversal(root.left);
        nodes.add(root.val);
        Traversal(root.right);
    }
}
//直接计算
class Solution {
    private int minDiff = Integer.MAX_VALUE;
    private TreeNode preNode = null;
    public int getMinimumDifference(TreeNode root) {
        inOrder(root);
        return minDiff;
    }
    private void inOrder(TreeNode node) {
        if (node == null) 
            return;
        inOrder(node.left);
        if (preNode != null) 
            minDiff = Math.min(minDiff, node.val - preNode.val);
        preNode = node;
        inOrder(node.right);
    }
}
```
### 27. 寻找二叉查找树中出现次数最多的值

&emsp;&emsp;https://leetcode-cn.com/problems/find-mode-in-binary-search-tree/

#### 思路
&emsp;&emsp;对二叉搜索树用中序遍历一定是有序的，故直接中序遍历，然后统计长度。
#### 代码
```Java
class Solution {
    int curcount=0;
    int count=0;
    TreeNode prenode=null;
    List<Integer> maxlist=new ArrayList<>();
    public int[] findMode(TreeNode root) {
        Traversal(root);
        int[] res=new int[maxlist.size()];
        for(int i=0;i<maxlist.size();i++)
            res[i]=maxlist.get(i);
        return res;
    }
    public void Traversal(TreeNode root)
    {
        if(root==null)
            return ;
        Traversal(root.left);
        if(prenode!=null)
        {
            if(prenode.val==root.val)
                curcount++;
            else
            {
                curcount=0;
            }
        }
        if(curcount>count)
        {
            maxlist.clear();
            maxlist.add(root.val);
            count=curcount;
        }
        else
            if(curcount==count)
                maxlist.add(root.val);
        prenode=root;
        Traversal(root.right);
    }
}
```
### 28. 实现一个 Trie

&emsp;&emsp;https://leetcode-cn.com/problems/implement-trie-prefix-tree/

#### 思路
&emsp;&emsp;Trie(字典树)是一种在字符串查找，前缀匹配等方面应用广泛的算法，它在查找字符串时只与被查询的字符串长度有关，所以它在查找时只有O(1)的时间复杂度，但随之而来的较大的空间复杂度。  
&emsp;&emsp;Trie的主要结构如下图所示：

![](https://pic002.cnblogs.com/images/2012/363302/2012091115401883.jpg)  

&emsp;&emsp;根节点不带有任何字母，然后每个节点向下都可以有26个分支(对于小写字母字典树而言，如果需要扩大范围，分支数量也会扩大)。分别对应了26个字母的选择，然后每个分支还会有26个字母，从而构建了一个从任何字母出发，可以到达任意字母的链路，从而可以用于表示任意单词。
#### 代码
```Java
class Trie {
    private TrieNode root;
    //定义字典类的数据结构
    private class TrieNode {
        char val;
        TrieNode[] children;
        //初始化节点，为当前节点赋值，并生成大小为27的数组，前26个用于存放子节点，第27个来代表是否为叶子节点。
        public TrieNode(char val) {
            this.val = val;
            children = new TrieNode[27];
        }
    }
    /**
     * Initialize your data structure here.
     */
    //用'0'来代表当前为叶子节点。
    public Trie() {
        root = new TrieNode('0');
    }
    /**
     * Inserts a word into the trie.
     */
    public void insert(String word) {
        int length = word.length();
        TrieNode node = root;
        //构造一个以字符串长度为深度的树
        for (int i = 0; i < length; i++) {
            char c = word.charAt(i);
            int position = c - 'a';
            //如果当前还不存在该节点，构造该节点
            if (node.children[position] == null) {
                node.children[position] = new TrieNode(c);
            }
            //指针向下层移动
            node = node.children[position];
        }
        //设置为叶子结点
        node.children[26] = new TrieNode('0');
    }

    /**
     * Returns if the word is in the trie.
     */
    public boolean search(String word) {
        int length = word.length();
        TrieNode node = root;

        for (int i = 0; i < length; i++) {
            char c = word.charAt(i);
            int position = c - 'a';
            //搜索到一半树中止了，false
            if (node.children[position] == null) {
                return false;
            } else {
                //向下层遍历
                node = node.children[position];
            }
        }
        //看是否是叶子节点
        if (node.children[26] != null) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns if there is any word in the trie that starts with the given prefix.
     */
    public boolean startsWith(String prefix) {
        int length = prefix.length();
        TrieNode node = root;

        for (int i = 0; i < length; i++) {
            char c = prefix.charAt(i);
            int position = c - 'a';

            if (node.children[position] == null) {
                return false;
            } else {
                node = node.children[position];
            }
        }
        return true;
    }

    
}
```
### 29. 实现一个 Trie，用来求前缀和

&emsp;&emsp;https://leetcode-cn.com/problems/map-sum-pairs/

#### 思路
&emsp;&emsp;与上题类似，本质是构造一个字典树，本次对于每个节点都构造一个变量存放当前的sum值，父节点在遍历时与先前值进行累加，即可得到以当前父节点为前缀的总和。  
&emsp;&emsp;在插入时先检索是否有完全匹配的路径，有的话需要先剪掉当前值再累和，从而实现覆盖。
#### 代码
```Java
class MapSum {
    private TrieNode root;
    //定义字典类的数据结构
    private class TrieNode {
        char val;
        int cursum;
        TrieNode[] children;
        //初始化节点，为当前节点赋值，并生成大小为27的数组，前26个用于存放子节点，第27个来代表是否为叶子节点。
        public TrieNode(char val,int sum) {
            this.val = val;
            this.cursum=sum;
            children = new TrieNode[27];
        }
    }
    /** Initialize your data structure here. */
    public MapSum() {
        root = new TrieNode('0',0);
    }
    
    public void insert(String key, int val) {
        int curval=search(key);        
        int length = key.length();
        TrieNode node = root;
        //构造一个以字符串长度为深度的树
        for (int i = 0; i < length; i++) {
            char c = key.charAt(i);
            int position = c - 'a';
            //如果当前还不存在该节点，构造该节点
            if (node.children[position] == null) {
                node.children[position] = new TrieNode(c,val);
            }
            else
                node.children[position].cursum+=val-curval;
            //指针向下层移动
            node = node.children[position];
        }
        //设置为叶子结点
        node.children[26] = new TrieNode('0',0);
    }
    
    public int sum(String prefix) {
        int length = prefix.length();
        TrieNode node = root;
        for (int i = 0; i < length; i++) {
            char c = prefix.charAt(i);
            int position = c - 'a';

            if (node.children[position] == null) {
                return 0;
            } else {
                node = node.children[position];
            }
        }
        return node.cursum;
    }
    public int search(String word) {
        int length = word.length();
        TrieNode node = root;
        for (int i = 0; i < length; i++) {
            char c = word.charAt(i);
            int position = c - 'a';
            //搜索到一半树中止了，false
            if (node.children[position] == null) {
                return 0;
            } else {
                //向下层遍历
                node = node.children[position];
            }
        }
        //看是否是叶子节点
        if (node.children[26] != null) {
            return node.cursum;
        } else {
            return 0;
        }
    }
}
```
## 三、栈和队列
### 1. 用栈实现队列

&emsp;&emsp;https://leetcode-cn.com/problems/implement-queue-using-stacks/

#### 思路
&emsp;&emsp;队列的特性是先进先出，而栈是先进后出，所以在出栈时需要先颠倒原栈顺序。
#### 代码
```Java
class MyQueue {
    Stack<Integer> que=new Stack<>();
    Stack<Integer> sup=new Stack<>();
    /** Initialize your data structure here. */
    public MyQueue() {
        
    }
    
    /** Push element x to the back of queue. */
    public void push(int x) {
        sup.push(x);
    }
    
    /** Removes the element from in front of queue and returns that element. */
    public int pop() {
        reverse();
        return que.pop();
    }
    
    /** Get the front element. */
    public int peek() {
        reverse();
        return que.peek();
    }
    
    /** Returns whether the queue is empty. */
    public boolean empty() {
        return que.isEmpty()&&sup.isEmpty();
    }
    void reverse()
    {
        if (que.isEmpty()) {
             while(!sup.isEmpty())
            {
                que.push(sup.pop());
            }
        }
    }
}
```
### 2. 用队列实现栈

&emsp;&emsp;https://leetcode-cn.com/problems/implement-stack-using-queues/

#### 思路
&emsp;&emsp;新插入元素之后，将除了新元素之外的所有元素都出队列再入队列，即可以维护顺序。  
&emsp;&emsp;`就是这些函数名不太好记`
#### 代码
```Java
class MyStack {
    Queue<Integer> que=new LinkedList<>();
    /** Initialize your data structure here. */
    public MyStack() {
        
    }
    
    /** Push element x onto stack. */
    public void push(int x) {
        int cursize=que.size();
        que.add(x);
        for(int i=0;i<cursize;i++)
        {
            que.add(que.poll());
        }
    }
    
    /** Removes the element on top of the stack and returns that element. */
    public int pop() {
        return que.remove();
    }
    
    /** Get the top element. */
    public int top() {
        return que.peek();
    }
    
    /** Returns whether the stack is empty. */
    public boolean empty() {
        return que.isEmpty();
    }
}
```
### 3. 最小值栈

&emsp;&emsp;https://leetcode-cn.com/problems/min-stack/

#### 思路
&emsp;&emsp;维护一个最小值栈，然后每次push的时候同步push当前最小值，pop的时候也同步pop即可。
#### 代码
```Java
class MinStack {
    private Stack<Integer> dataStack=new Stack<>();
    private Stack<Integer> minStack=new Stack<>();
    private int min;
    public MinStack() {
        min = Integer.MAX_VALUE;
    }

    public void push(int x) {
        dataStack.add(x);
        min = Math.min(min, x);
        minStack.add(min);
    }

    public void pop() {
        dataStack.pop();
        minStack.pop();
        min = minStack.isEmpty() ? Integer.MAX_VALUE : minStack.peek();
    }

    public int top() {
        return dataStack.peek();
    }

    public int getMin() {
        return minStack.peek();
    }
}
```
### 4. 用栈实现括号匹配

&emsp;&emsp;https://leetcode-cn.com/problems/valid-parentheses/

#### 思路
&emsp;&emsp;左括号push入栈，右括号则将元素pop出栈进行比较，能够配对则成功，不能则失败。
#### 代码
```Java
class Solution {
    public boolean isValid(String s) {
        Stack<Character> st=new Stack<>();
        for(int i=0;i<s.length();i++)
        {
            char ch=s.charAt(i);
            if(ch=='('||ch=='['||ch=='{')
            {
                st.push(ch);
            }
            else
            {
                if(ch==')'||ch==']'||ch=='}')
                {
                    if(st.isEmpty())
                        return false;
                    char cur=st.pop();
                    if(ch==')'&&cur!='(')
                        return false;
                    if(ch==']'&&cur!='[')
                        return false;
                    if(ch=='}'&&cur!='{')
                        return false;
                }
            }
        }
        if(st.isEmpty())
            return true;
        return false;
    }
}
```
### 5. 数组中元素与下一个比它大的元素之间的距离

&emsp;&emsp;https://leetcode-cn.com/problems/daily-temperatures/

#### 思路
&emsp;&emsp;维护一个递减栈，在遍历数组的同时进行维护，如果当前遍历的元素:
1. 小于栈顶元素，那么就入栈，从而保证了栈内元素在目前为止没有在各自的位置后面找到比他们大的数。
2. 大于栈顶元素，那么就开始出栈，意味着当前所遍历的元素，对于所有因为小于他而出栈的元素而言，都是第一次遇到的比他们大的数。两者下标相减，就可以获得天数差值,最后将当前元素入栈。
3. 最后的元素位置应该用0代替，遍历之后还未出栈的元素，说明没有比他大的元素，也要用0代替，但是数组初始化为0，不用再多赋值。

#### 代码
```Java
class Solution {
    public int[] dailyTemperatures(int[] T) {
        int[] res=new int[T.length];
        Stack<Integer> supStack=new Stack<>();
        for(int i=0;i<T.length;i++)
        {
            if(supStack.isEmpty()||T[i]<=T[supStack.peek()])
               supStack.push(i);
            else
            {
                while(!supStack.isEmpty()&&T[i]>T[supStack.peek()])
                {
                    int index=supStack.pop();
                    res[index]=i-index;
                }
                supStack.push(i);
            }
        }
        return res;
    }
}
```
### 6. 循环数组中比当前元素大的下一个元素

&emsp;&emsp;https://leetcode-cn.com/problems/next-greater-element-ii/

#### 思路
&emsp;&emsp;和上题类似，只是本题需要将待检测数组重复一遍用来实现循环比较，并且在数组中不再保存下标差而是实际值。
#### 代码
```Java
class Solution {
    public int[] nextGreaterElements(int[] nums) {
        int[] res=new int[nums.length];
        Arrays.fill(res,-1);
        Stack<Integer> supStack=new Stack<>();
        for(int i=0;i<nums.length*2;i++)
        {
            int realindex=i%nums.length;
            
            if(supStack.isEmpty()||nums[realindex]<nums[supStack.peek()])
               supStack.push(realindex);
            else
            {
                while(!supStack.isEmpty()&&nums[realindex]>nums[supStack.peek()])
                {
                    int index=supStack.pop();
                    res[index]=nums[realindex];
                }
                supStack.push(realindex);
            }
        }
        return res;
    }
}
```
## 四、哈希表
### 1. 数组中两个数的和为给定值

&emsp;&emsp;https://leetcode-cn.com/problems/two-sum/

#### 思路
&emsp;&emsp;可以先对数组进行排序，然后使用双指针方法或者二分查找方法。这样做的时间复杂度为 O(NlogN)，空间复杂度为 O(1)。

&emsp;&emsp;用 HashMap 存储数组元素和索引的映射，在访问到 nums[i] 时，判断 HashMap 中是否存在 target - nums[i]，如果存在说明 target - nums[i] 所在的索引和 i 就是要找的两个数。该方法的时间复杂度为 O(N)，空间复杂度为 O(N)，使用空间来换取时间。

#### 代码
```Java
class Solution {
    public int[] twoSum(int[] nums, int target) {
        HashMap<Integer,Integer> index=new HashMap<>();
        for(int i=0;i<nums.length;i++)
        {
            if(index.containsKey(target-nums[i]))
                return new int[]{i,index.get(target-nums[i])};
            else
                index.put(nums[i],i);
        }
        return null;
    }
}
```
### 2. 判断数组是否含有重复元素

&emsp;&emsp;https://leetcode-cn.com/problems/contains-duplicate/

#### 思路
&emsp;&emsp;利用HashSet不保存重复元素的特性，每个元素都保存一次，最后看Set的大小。
#### 代码
```Java
class Solution {
    public boolean containsDuplicate(int[] nums) {
        Set<Integer> res=new HashSet<>();
        for(int i=0;i<nums.length;i++)
        {
            res.add(nums[i]);
        }
        return res.size()==nums.length?false:true;
    }
}
```
### 3. 最长和谐序列

&emsp;&emsp;https://leetcode-cn.com/problems/longest-harmonious-subsequence/

#### 思路
&emsp;&emsp;先遍历一遍数组，用HashMap保存各个数字的出现频率，然后再遍历一次，获取相邻两数的频次和，找最大值。
#### 代码
```Java
class Solution {
    public int findLHS(int[] nums) {
        Map<Integer,Integer> res=new HashMap<>();
        for(int i=0;i<nums.length;i++)
        {
            res.put(nums[i],res.getOrDefault(nums[i],0)+1);
        }
        int max=0;
        for(int keys : res.keySet())
        {
            if(res.containsKey(keys+1))
                max=Math.max(max,res.get(keys)+res.get(keys+1));
        }
        return max;
    }
}
```
### 4. 最长连续序列

&emsp;&emsp;https://leetcode-cn.com/problems/longest-consecutive-sequence/

#### 思路
&emsp;&emsp;对于O(n)的复杂度，我们遍历一次数组，然后用辅助的Hash表进行存储，对于任意遍历到的元素，会有两种情况：
1. 表中已经存在，因为题目要求的序列元素是不重复的，所以无需处理。
2. 表中不存在当前元素，需要查找该元素左右相邻数的最长连续区间长度，然后现在的最长连续区间长度就是left+right+1，随后更新两边端点的长度值以及要求的最大值即可。

#### 代码
```Java
class Solution {
    public int longestConsecutive(int[] nums) {
        HashMap<Integer,Integer> len=new HashMap<>();
        int res=0;
        for(int num : nums)
        {
            if(!len.containsKey(num))
            {
                int left=len.getOrDefault(num-1,0);
                int right=len.getOrDefault(num+1,0);
                int curlength=left+right+1;
                if(res<curlength)
                    res=curlength;
                len.put(num-left,curlength);
                len.put(num+right,curlength);
                len.put(num,curlength);
            }
        }
        return res;
    }
}
```
## 五、字符串
### 1. 两个字符串包含的字符是否完全相同

&emsp;&emsp;https://leetcode-cn.com/problems/valid-anagram

#### 思路
&emsp;&emsp;用一个字符数组，遍历两个字符串，统计各数字出现的频次，如果频次对不上则false。
#### 代码
```Java
class Solution {
    public boolean isAnagram(String s, String t) {
        int[] dic=new int[26];
        for(int i=0;i<s.length();i++)
        {
            dic[s.charAt(i)-'a']++;
        }
        for(int i=0;i<t.length();i++)
        {
            dic[t.charAt(i)-'a']--;
        }
        for(int i=0;i<26;i++)
            if(dic[i]!=0)
                return false;
        return true;
    }
}
```
### 2. 计算一组字符集合可以组成的回文字符串的最大长度

&emsp;&emsp;https://leetcode-cn.com/problems/longest-palindrome

#### 思路
&emsp;&emsp;遍历一次字符串统计各字符个数，偶数个的直接加，奇数个的-1再加，并且在最后加上一个多余的奇数个字符放在最中间。
#### 代码
```Java
class Solution {
    public int longestPalindrome(String s) {
        int[] dic=new int[26*2];
        for(int i=0;i<s.length();i++)
        {
            char ch=s.charAt(i);
            if(ch<'a')
                dic[ch-'A']++;
            else
                dic[ch-'a'+26]++;
        }
        int res=0;
        int hassingle=0;
        for(int i=0;i<dic.length;i++)
        {
            if(dic[i]%2==0&&dic[i]!=0)
                res+=dic[i];
            if(dic[i]%2==1)
            {
                hassingle=1;
                res+=dic[i]-1;
            }
        }
        return res+hassingle;
    }
}
```
### 3. 字符串同构

&emsp;&emsp;https://leetcode-cn.com/problems/isomorphic-strings/

#### 思路
&emsp;&emsp;对于每个字符记录其上一次出现的位置，然后对比两个字符串位置是否一样，不一样就false。  
&emsp;&emsp;但是保存的时候位置要+1，不然就和0重复了。
#### 代码
```Java
class Solution {
    public boolean isIsomorphic(String s, String t) {
        int[] sdic=new int[128];
        int[] tdic=new int[128];
        if(s.length()!=t.length())
            return false;
        for(int i=0;i<s.length();i++)
        {
            char sc=s.charAt(i);
            char tc=t.charAt(i);
            if(sdic[sc]!=tdic[tc])
                return false;
            sdic[sc]=i+1;
            tdic[tc]=i+1;
        }
        return true;
    }
}
```
### 4. 回文子字符串个数

&emsp;&emsp;https://leetcode-cn.com/problems/palindromic-substrings/

#### 思路
&emsp;&emsp;遍历字符串，从每个字符开始尝试向两边扩展。
#### 代码
```Java
class Solution {
    int res=0;
    public int countSubstrings(String s) {
        for(int i=0;i<s.length();i++)
        {
            sup(s,i,i);
            sup(s,i,i+1);
        }
        return res;
    }
    public void sup(String s,int start,int end)
    {
        while(start>=0&&end<s.length()&&s.charAt(start)==s.charAt(end))
        {
            start--;
            end++;
            res++;
        }
    }
}
```
### 5. 判断一个整数是否是回文数

&emsp;&emsp;https://leetcode-cn.com/problems/palindrome-number/

#### 思路
&emsp;&emsp;为了不使用字符串，我们可以取出数字的后半段然后反转进行比较。  
&emsp;&emsp;我们将数字/10取出1位，然后*10再加下一位，这样就可以把数字的尾部反转，直到反转数>=剩余数，说明已经到达中点或者已经过了中点，此时对两部分数字进行比较即可。  
&emsp;&emsp;如果是回文数，比较时会有两种情况：
1. 两侧正好相等。
2. 因为原数是的位数是奇数，所有反转数会比剩余数多一位，此时只要/10然后比较即可。

#### 代码
```Java
class Solution {
    public boolean isPalindrome(int x) {
        if(x==0)
            return true;
        if(x<0||x%10==0)
            return false;
        int left=x;
        int right=0;
        while(right<left)
        {
            right=right*10+left%10;
            left/=10;
        }
        return left==right||left==right/10;
        
    }
}
```
### 6. 统计二进制字符串中连续 1 和连续 0 数量相同的子字符串个数

&emsp;&emsp;https://leetcode-cn.com/problems/count-binary-substrings/

#### 思路
&emsp;&emsp;遍历保存当前连续的0或1的个数为precount，然后遍历紧邻的相反数字的个数为curcount，那么只要precount>=curcount，那么curcount每增加1，回文子串就会增加1,这样遍历下来，就会获得回文子串的数量，当数字反转时，重置状态。
#### 代码
```Java
class Solution {
    public int countBinarySubstrings(String s) {
        int res=0;
        int curcount=1,precount=0;
        for(int i=1;i<s.length();i++)
        {
            if(s.charAt(i)==s.charAt(i-1))
                curcount++;
            else
            {
                precount=curcount;
                curcount=1;
            }
            if(curcount<=precount)
                res++;
        }
        return res;
    }
}
```
## 六、数组与矩阵

### 1. 把数组中的 0 移到末尾

&emsp;&emsp;https://leetcode-cn.com/problems/move-zeroes/

#### 思路
&emsp;&emsp;简单位移，剩余部分填充0。
#### 代码
```Java
class Solution {
    public void moveZeroes(int[] nums) {
        int index=0;
        for(int i=0;i<nums.length;i++)
        {
            if(nums[i]!=0)
                nums[index++]=nums[i];
        }
        for(int i=index;i<nums.length;i++)
        {
            nums[i]=0;
        }
    }
}
```
### 2. 改变矩阵维度

&emsp;&emsp;https://leetcode-cn.com/problems/reshape-the-matrix/

#### 思路
&emsp;&emsp;手动控制目标数组进位。
#### 代码
```Java
class Solution {
    public int[][] matrixReshape(int[][] nums, int r, int c) {
        int[][] res=new int[r][c];
        int row=0,col=0;
        if(r*c!=nums.length*nums[0].length)
            return nums;
        for(int i=0;i<nums.length;i++)
        {
            for(int j=0;j<nums[0].length;j++)
            {
                res[row][col++]=nums[i][j];
                if(col==c)
                {
                    row++;
                    col=0;
                }
            }
        }
        return res;
    }
}
```
### 3. 找出数组中最长的连续 1

&emsp;&emsp;https://leetcode-cn.com/problems/max-consecutive-ones/

#### 思路
&emsp;&emsp;单次遍历查找连续的1。
#### 代码
```Java
class Solution {
    public int findMaxConsecutiveOnes(int[] nums) {
        int max=0;
        int curcount=0;
        for(int i=0;i<nums.length;i++)
        {
            if(nums[i]==1)
                curcount++;
            if(curcount>max)
                max=curcount;
            if(nums[i]!=1)
                curcount=0;
        }
        return max;
    }
}
```
### 4. 有序矩阵查找

&emsp;&emsp;https://leetcode-cn.com/problems/search-a-2d-matrix-ii/

#### 思路
&emsp;&emsp;左下角的元素是这一行中最小的元素，同时又是这一列中最大的元素。比较左下角元素和目标：
1. 若左下角元素等于目标，则找到
2. 若左下角元素大于目标，则目标不可能存在于当前矩阵的最后一行，问题规模可以减小为在去掉最后一行的子矩阵中寻找目标
3. 若左下角元素小于目标，则目标不可能存在于当前矩阵的第一列，问题规模可以减小为在去掉第一列的子矩阵中寻找目标
4. 若最后矩阵减小为空，则说明不存在

#### 代码
```Java
class Solution {
    public boolean searchMatrix(int[][] matrix, int target) {
        if(matrix==null||matrix.length==0||matrix[0].length==0)
            return false;
        int m=matrix.length;
        int n=matrix[0].length;
        for(int i=0,j=n-1;i<m&&j>=0;)
        {
            if(target==matrix[i][j])
                return true;
            if(target<matrix[i][j])
                j--;
            else
                i++;
        }
        return false;
    }
}
```
### 5. 有序矩阵的 Kth Element

&emsp;&emsp;https://leetcode-cn.com/problems/kth-smallest-element-in-a-sorted-matrix/

#### 思路
&emsp;&emsp;没看懂二分，很尴尬，用K个指针实现了最笨的版本，执行用时:73 ms, 在所有 Java 提交中击败了14.78%的用户。
#### 代码
```Java
class Solution {
    public int kthSmallest(int[][] matrix, int k) {
        int count=0;
        int[] p=new int[matrix.length];
        while(true)
        {
            int curcol=0;
            int min=Integer.MAX_VALUE;
            for(int i=0;i<matrix.length;i++)
            {
                if(p[i]>=matrix[0].length)
                    continue;
                if(min>matrix[i][p[i]])
                {
                    min=matrix[i][p[i]];
                    curcol=i;
                }
            }
            count++;
            if(count==k)
                return min;
            p[curcol]++;
        }
    }
}
```
### 6. 一个数组元素在 [1, n] 之间，其中一个数被替换为另一个数，找出重复的数和丢失的数

&emsp;&emsp;https://leetcode-cn.com/problems/set-mismatch/

#### 思路
&emsp;&emsp;遍历一次并替换，将当前元素与应该在的正确位置的元素进行替换，结束替换应该有两个条件。
1. 当前元素位置正确，向下一位移动。
2. 当前元素与目标位置元素相同，向下一位移动，否则会死循环。

#### 代码
```Java
public int[] findErrorNums(int[] nums) {
    for (int i = 0; i < nums.length; i++) {
        while (nums[i] != i + 1 && nums[nums[i] - 1] != nums[i]) {
            swap(nums, i, nums[i] - 1);
        }
    }
    for (int i = 0; i < nums.length; i++) {
        if (nums[i] != i + 1) {
            return new int[]{nums[i], i + 1};
        }
    }
    return null;
}

private void swap(int[] nums, int i, int j) {
    int tmp = nums[i];
    nums[i] = nums[j];
    nums[j] = tmp;
}
```
### 7. 找出数组中重复的数，数组值在 [1, n] 之间

&emsp;&emsp;https://leetcode-cn.com/problems/find-the-duplicate-number/

#### 思路
&emsp;&emsp;将数组看成链表，val是结点值也是下个节点的地址。因此这个问题就可以转换成判断链表有环，且找出入口节点---使用快慢指针，一个时间复杂度为O(N)的算法。

1. 对于链表问题，使用快慢指针可以判断是否有环。

2. 本题可以使用数组配合下标，抽象成链表问题。但是难点是要定位环的入口位置。  
&emsp;&emsp;举个例子：nums = [2,5, 9 ,6,9,3,8, 9 ,7,1]，构造成链表就是：2->[9]->1->5->3->6->8->7->[9]，也就是在[9]处循环。
3. 快慢指针问题，会在环内的[9]->1->5->3->6->8->7->[9]任何一个节点追上，不一定是在[9]处相碰，事实上会在7处碰上。
4. 必须另起一个for循环定位环入口位置[9]。假设从头节点开始，走M步可以访问到循环的入口位置，那么当快慢指针相遇时，慢指针走了N步，则快指针就走了2N步，假设从入口位置到相遇位置还要走P步，那么克制，慢指针如果再走(N-P)步，就可以到达入口节点，而N-P=M，故如果此时用一个指针从0开始与慢指针一起走，两个指针会在循环入口处相遇。

#### 代码
```Java
class Solution {
    public int findDuplicate(int[] nums) {
        int slow = nums[0], fast = nums[nums[0]];
        while (slow != fast) {
            slow = nums[slow];
            fast = nums[nums[fast]];
        }
        fast = 0;
        while (slow != fast) {
            slow = nums[slow];
            fast = nums[fast];
        }
        return slow;
    }
}
```
### 8. 数组相邻差值的个数

&emsp;&emsp;https://leetcode-cn.com/problems/beautiful-arrangement-ii/

#### 思路
&emsp;&emsp;在区间`[0,k]`内，偶数下标填充`[1,2,3,...]`奇数下标填充`[k+1,k,k-1,...]`，剩下的按顺序填充。
#### 代码
```Java
class Solution {
    public int[] constructArray(int n, int k) {
        int[] res=new int[n];
        int sin=1,dou=k+1;
        for(int i=0;i<=k;i++)
        {
            if(i%2==0)
                res[i]=sin++;
            else
                res[i]=dou--;
        }
        for(int i=k+1;i<n;i++)
            res[i]=i+1;
        return res;
    }
}
```
### 9. 数组的度

&emsp;&emsp;https://leetcode-cn.com/problems/degree-of-an-array/

#### 思路
&emsp;&emsp;用Map来保存每个数的出现频次与左右边界，最后遍历查找频次最高且左右边界距离最近的。
#### 代码
```Java
class Solution {
    public int findShortestSubArray(int[] nums) {
        Map<Integer,int[]> res=new HashMap<>();
        for(int i=0;i<nums.length;i++)
        {
            if(!res.containsKey(nums[i]))
            {
                res.put(nums[i],new int[]{1,i,i});
            }
            else
            {
                int[] temp=res.get(nums[i]);
                temp[0]++;
                temp[2]=i;
                res.put(nums[i],temp);
            }
        }
        int max=0,left=0,right=0;
        for(int i=0;i<nums.length;i++)
        {
            int[] temp=res.get(nums[i]);
            if(max<temp[0])
            {
                max=temp[0];
                left=temp[1];
                right=temp[2];
            }
            if(max==temp[0])
            {
                if((right-left)>(temp[2]-temp[1]))
                {
                    left=temp[1];
                    right=temp[2];
                }
            }
        }
        return right-left+1;
    }
}
//数组版
class Solution {
    public int findShortestSubArray(int[] nums) {
     
       int max = 0;
        for (int num : nums) {
            max = Math.max(max, num);
        }
        int[] mapSize = new int[max + 1];
        int[] mapMin = new int[max + 1];
        int[] mapMax = new int[max + 1];
        int maxSize = 1;
        for (int i = 0; i < nums.length; i++) {
            int num = nums[i];
            if (mapSize[num] == 0) {
                mapMin[num] = i;
                mapMax[num] = i;
            } else {
                mapMax[num] = i;
            }
            maxSize = Math.max(maxSize, ++mapSize[num]);
        }
        int ans = Integer.MAX_VALUE;
        for (int num = 0; num <= max; num++) {
            if (maxSize == mapSize[num]) {
                ans = Math.min(ans, mapMax[num] - mapMin[num] + 1);
            }
        }
        return ans;
    }
}
```
### 10. 对角元素相等的矩阵

&emsp;&emsp;https://leetcode-cn.com/problems/toeplitz-matrix/

#### 思路
&emsp;&emsp;只需比较当前行除最后一个元素外的其他元素与下一行除第一个元素外的其他元素是否对应想等即可。
#### 代码
```Java
class Solution {
    public boolean isToeplitzMatrix(int[][] matrix) {
        int m=matrix.length;
        int n=matrix[0].length;
        for(int i=0;i<m-1;i++)
        {
            for(int j=0;j<n-1;j++)
                if(matrix[i][j]!=matrix[i+1][j+1])
                    return false;
        }
        return true;
    }
}
```
### 11. 嵌套数组

&emsp;&emsp;https://leetcode-cn.com/problems/array-nesting/

#### 思路
&emsp;&emsp;题意可以理解为在数组中寻找最大环路，那么就从头开始遍历数组，对于每个访问到的数字都打上标记，这样一来可以在环形访问时发现形成环，二来可以在后续遍历时停止访问，因为如果先前遍历时已经访问过，那么这个数字的环路解已经被考虑过了。
#### 代码
```Java
class Solution {
    public int arrayNesting(int[] nums) {
        int max = 0;
        for (int i = 0; i < nums.length; i++) {
            int cnt = 0;
            for (int j = i; nums[j] != -1; ) {
                cnt++;
                int t = nums[j];
                nums[j] = -1; // 标记该位置已经被访问
                j = t;

            }
            max = Math.max(max, cnt);
        }
        return max;
    }
}
```
### 12. 分隔数组

&emsp;&emsp;https://leetcode-cn.com/problems/max-chunks-to-make-sorted/

#### 思路
&emsp;&emsp;在任意切分点，如果想要切分，必然需要当前块元素都小于下一块的元素。  
&emsp;&emsp;那么对于如何寻找切分点，只要寻找当前块的最大值，当最大值等于遍历下标时，即满足了条件。
#### 代码
```Java
class Solution {
    public int maxChunksToSorted(int[] arr) {
        int max=-1;
        int res=0;
        for(int i=0;i<arr.length;i++)
        {
            max=Math.max(max,arr[i]);
            if(max==i)
            {
                res++;
            }
        }
        return res;
    }
}
```
## 七、图
&emsp;&emsp;超出了我的能力范围，题做的超级艰难。
### 1. 判断是否为二分图

&emsp;&emsp;https://leetcode-cn.com/problems/is-graph-bipartite/

#### 思路
&emsp;&emsp;用辅助数组来标记每个节点染色情况，然后从头开始遍历所有节点，从当前节点出发，若已染色则无需遍历，若未染色则染A色，然后遍历所有可达节点进行查询，若节点未染色则染反色，若已染色则判断是否为反色。
#### 代码
```Java
class Solution {
    public boolean isBipartite(int[][] graph) {
        int[] res=new int[graph.length];
        for(int i=0;i<graph.length;i++)
        {
            if (res[i] == 0 && !sup(i, 1, res, graph)) {
                return false;
            }
        }
        return true;
    }
    private boolean sup(int i,int flag,int[] res,int[][] graph)
    {
        if(res[i]!=0)
            return res[i]==flag;
        res[i]=flag;
        for(int j=0;j<graph[i].length;j++)
        {
            if(!sup(graph[i][j],-flag,res,graph))
                    return false;
        }
        return true;
    }
}
```
### 2. 课程安排的合法性

&emsp;&emsp;https://leetcode-cn.com/problems/course-schedule/

#### 思路
&emsp;&emsp;先遍历一次图，获得每个课程的入度(被指次数)，也就是需要的前驱课程的数量。  
&emsp;&emsp;对于入度为0的课程，他们不需要任何前驱，所以可以从这些课程作为起点，开始向后遍历，把他们指向的课程入度都-1，然后判断这些课程是否入度会变为0，如果变为0说明这些课程是可以满足需求的，故可以继续入队列作为条件向下判断。  
&emsp;&emsp;最后看是否所有课程都满足需求。
#### 代码
```Java
class Solution {
    public boolean canFinish(int numCourses, int[][] prerequisites) {
        int[] in=new int[numCourses];
        for(int i=0;i<prerequisites.length;i++)
        {
            in[prerequisites[i][0]]++;
        }
        LinkedList<Integer> queue = new LinkedList<>();
        for(int i=0;i<numCourses;i++){
            if(in[i]==0) queue.addLast(i);
        }
        while(!queue.isEmpty())
        {
            int safe=queue.remove();
            numCourses--;
            for(int i=0;i<prerequisites.length;i++)
            {
                if(prerequisites[i][1]==safe)
                {
                    in[prerequisites[i][0]]--;
                    if(in[prerequisites[i][0]]==0)
                        queue.add(prerequisites[i][0]);
                }
            }
        }
        return numCourses == 0;
    }
}
```
### 3. 课程安排的顺序

&emsp;&emsp;https://leetcode-cn.com/problems/course-schedule-ii/

#### 思路
&emsp;&emsp;与上一题一致，对于入度为0的课程就是可以学习的课程，所以只需要保存我们队列中的顺序即可。
#### 代码
```Java
class Solution {
    public int[] findOrder(int numCourses, int[][] prerequisites) {
        int[] res=new int[numCourses];
        int rescount=0;
        int[] in=new int[numCourses];
        for(int i=0;i<prerequisites.length;i++)
        {
            in[prerequisites[i][0]]++;
        }
        LinkedList<Integer> queue = new LinkedList<>();
        for(int i=0;i<numCourses;i++){
            if(in[i]==0) 
            {
                queue.addLast(i);
                res[rescount++]=i;
            }
        }
        while(!queue.isEmpty())
        {
            int safe=queue.remove();
            numCourses--;
            for(int i=0;i<prerequisites.length;i++)
            {
                if(prerequisites[i][1]==safe)
                {
                    in[prerequisites[i][0]]--;
                    if(in[prerequisites[i][0]]==0)
                    {
                        queue.add(prerequisites[i][0]);
                        res[rescount++]=prerequisites[i][0];
                    }
                }
            }
        }
        if(numCourses==0)
            return res;
        return new int[0];
    }
}
```
### 4. 冗余连接

&emsp;&emsp;https://leetcode-cn.com/problems/redundant-connection/

#### 思路
&emsp;&emsp;利用并查集，保存每个边的通路，寻找父节点，最后如果找到父节点相同的两个点，这条边就是多余的。
#### 代码
```Java
class Solution {
    public int[] findRedundantConnection(int[][] edges) {
        int[] father = new int[edges.length+1];		//并查集
        for ( int i = 1; i < father.length; i++ ) 
            father[i] = i;	//初始化并查集
        for (int[] edge : edges) {
        	int fIndex1 = findFather(father, edge[0]);	//找到顶点的组先
        	int fIndex2 = findFather(father, edge[1]);
        	if ( fIndex1 != fIndex2 ) {	
                //如果祖先不同，说明不是多余的边
        		father[fIndex1] = fIndex2;
        	} else {	
                //祖先相同，说明是多余的边
        		return new int[]{edge[0],edge[1]};
        	}
		}
        return new int[2];
    }
	public int findFather( int[] father, int index ) {	//路径压缩
		if ( index == father[index] ) return index;
		else {
			int temp = findFather(father, father[index]);
			father[index] = temp;
			return temp;
		}
	}
}
```
## 八、位运算
```
Java 中的位操作

static int Integer.bitCount();           // 统计 1 的数量
static int Integer.highestOneBit();      // 获得最高位
static String toBinaryString(int i);     // 转换为二进制表示的字符串
```
### 1. 统计两个数的二进制表示有多少位不同

&emsp;&emsp;https://leetcode-cn.com/problems/hamming-distance/

#### 思路
&emsp;&emsp;对两个数进行异或操作，位级表示不同的那一位为 1，统计有多少个 1 即可。
#### 代码
```Java
class Solution {
    public int hammingDistance(int x, int y) {
        int temp=x^y;
        int count=0;
        while(temp!=0)
        {
           if((temp&1)==1)
               count++;
            temp=temp>>1;
        }
        return count;
    }
}
```
### 2. 数组中唯一一个不重复的元素

&emsp;&emsp;https://leetcode-cn.com/problems/single-number/

#### 思路
&emsp;&emsp;遍历异或，相同的异或是0。
#### 代码
```Java
class Solution {
    public int singleNumber(int[] nums) {
        int res=nums[0];
        for(int i=1;i<nums.length;i++)
        {
            res^=nums[i];
        }
        return res;
    }
}
```
### 3. 找出数组中缺失的那个数

&emsp;&emsp;https://leetcode-cn.com/problems/missing-number/

#### 思路
&emsp;&emsp;如果在数组的基础上再加上0~n,就变成了找数组中不重复的元素。
#### 代码
```Java
class Solution {
    public int missingNumber(int[] nums) {
        int res=0;
        for(int i=0;i<nums.length;i++)
        {
            res^=i^nums[i];
        }
        return res^nums.length;
    }
}
```
### 4. 只出现一次的元素

#### 思路
&emsp;&emsp;假设有一个数为x,那么则有如下规律：  
```
0 ^ x = x,
x ^ x = 0；
x & ~x = 0,
x & ~0 =x;
```
&emsp;&emsp;对于
```Java
a = (a ^ num) & ~b;
b = (b ^ num) & ~a;
```
1. 初始状态：`a=0,b=0`;  
2. x第一次出现后，`a=(a^x)&~b=(0^x)&~0=x`,`b=(b^x)&~a=(0^x)&~x=0`。  
3. x第二次出现：`a=(a^x)&~b=(x^x)&~0=0`; `b=(b^x)&~a=(0^x)&~0=x`;  
4. x第三次出现：`a=(a^x)&~b=(0^x)&~x=0`; `b=(b^x)&~a=(x^x)&~0=0`;所以出现三次同一个数，a和b最终都变回了0.  
5. 只出现一次的数，按照上面x第一次出现的规律可知`a=x,b=0`;因此最后返回a.

#### 代码
```Java
class Solution {
    public int singleNumber(int[] nums) {
        int a=0,b=0;
        for(int i=0;i<nums.length;i++)
        {
            a=(a^nums[i])&~b;
            b=(b^nums[i])&~a;
        }
        return a;
    }
}
```
### 5. 数组中不重复的两个元素

&emsp;&emsp;https://leetcode-cn.com/problems/single-number-iii/

#### 思路
&emsp;&emsp;先全部异或一次,得到的结果就是这两个只出现一次的两个数的异或值， 考察其的某个非0位(比如最高非0位), 那么只出现一次的两个数中, 在这个位上一个为0, 一个为1, 由此可以将数组中的元素分成两部分,重新遍历, 求两个异或值。
#### 代码
```Java
class Solution {
    public int[] singleNumber(int[] nums) {
        int res=0;
        for(int i=0;i<nums.length;i++)
            res=res^nums[i];
        res=res&(~res+1);
        int left=0,right=0;
        for(int i=0;i<nums.length;i++)
        {
            if((nums[i]&res)==0)
                left^=nums[i];
            else
                right^=nums[i];
        }
        return new int[]{left,right};
    }
}
```
### 6. 翻转一个数的比特位

#### 思路
&emsp;&emsp;移位进位
#### 代码
```Java
public class Solution {
    // you need treat n as an unsigned value
    public int reverseBits(int n) {
        int res=0;
        for(int i=0;i<32;i++)
        {
            res<<=1;
            res+=n&1;
            n>>>=1;
        }
        return res;
    }
}
```
#### 额外内容
&emsp;&emsp;Integer.reverse()源码：
```Java
public static int reverse(int i) {
        // HD, Figure 7-1
        i = (i & 0x55555555) << 1 | (i >>> 1) & 0x55555555;//第一步
        i = (i & 0x33333333) << 2 | (i >>> 2) & 0x33333333;//第二步
        i = (i & 0x0f0f0f0f) << 4 | (i >>> 4) & 0x0f0f0f0f;//第三步
        i = (i << 24) | ((i & 0xff00) << 8) |((i >>> 8) & 0xff00) | (i >>> 24);//第四步
        return i;
}
```
### 7. 判断一个数是不是 2 的 n 次方

&emsp;&emsp;https://leetcode-cn.com/problems/power-of-two/

#### 思路
&emsp;&emsp;2的幂次的二进制一定是10000.....，如果-1的话就变成了1111111....
#### 代码
```Java
class Solution {
    public boolean isPowerOfTwo(int n) {
        return (n>0)&&(n&(n-1))==0;
    }
}
```
### 8. 判断一个数是不是 4 的 n 次方

&emsp;&emsp;https://leetcode-cn.com/problems/power-of-four/

#### 代码
```Java
class Solution {
    public boolean isPowerOfFour(int num) {
        int res=0;
        if(num<=0)
            return false;
        res=num&(num-1);
        if((res==0)&&(num%3==1))
            return true;
        return false;
    }
}
```
### 9. 判断一个数的位级表示是否不会出现连续的 0 和 1

&emsp;&emsp;https://leetcode-cn.com/problems/binary-number-with-alternating-bits/

#### 思路
&emsp;&emsp;如果一个数是满足条件的，那么一定是1010101......，如果将其右移一位，再和原数异或，就会得到11111111，把这个数+1就会得到100000并比原数多一位，再&的话就会得到0。
#### 代码
```Java
class Solution {
    public boolean hasAlternatingBits(int n) {
        int res=n^(n>>1);
        return (res&(res+1))==0;
    }
}
```
### 10. 求一个数的补码

&emsp;&emsp;https://leetcode-cn.com/problems/number-complement/

#### 思路
&emsp;&emsp;加入这个数转换二进制一共有K位，那么就可以构造一个K位的111111....,然后两个数进行异或，就可以得到取反的数。
#### 代码
```Java
class Solution {
    public int findComplement(int num) {
        int n=num;
        int res=0;
        while(num>0)
        {
            num>>=1;
            //注意移位优先级低于算数运算符，要套括号
            res=(res<<1)+1;
        }
        return res^n;
    }
}
```
### 11. 实现整数的加法

&emsp;&emsp;https://leetcode-cn.com/problems/sum-of-two-integers/

#### 思路
&emsp;&emsp;对于任意数`a,b`,`a^b`是其各位相加而不进行进位的结果，而`a&b`得到了各位是否应该进位的情况。  
&emsp;&emsp;所以`a&b<<1`就得到了各个位所需要的进位，再与`a^b`相加就完成了一轮进位，但是相加后可能还需要进位，所以一直递归调用，知道无需进位。
#### 代码
```Java
class Solution {
    public int getSum(int a, int b) {
        return b==0?a:getSum(a^b,(a&b)<<1);
    }
}
```
### 12. 字符串数组最大乘积

&emsp;&emsp;https://leetcode-cn.com/problems/maximum-product-of-word-lengths/

#### 思路
&emsp;&emsp;对于每一个字符串，我们用一个数二进制的26个位置来代表，如果出现了对应的字母就`|1<<(char-'a')`来构造一个对应位置的1。  
&emsp;&emsp;这样再遍历两个字符串，如果两个数的标志数组&的结果是0，说明没有共同的位，然后再找最大值。
#### 代码
```Java
class Solution {
    public int maxProduct(String[] words) {
        int[] res=new int[words.length];
        for(int i=0;i<words.length;i++)
        {
            String s=words[i];
            for(int j=0;j<s.length();j++)
            {
                res[i] |=1<<(s.charAt(j)-'a');
            }
        }
        int max=0;
        for(int i=0;i<words.length;i++)
        {
            for(int j=i+1;j<words.length;j++)
            {
                if((res[i]&res[j])==0)
                    max=Math.max(max,words[i].length()*words[j].length());
            }
        }
        return max;
    }
}
```
### 13. 统计从 0 ~ n 每个数的二进制表示中 1 的个数

&emsp;&emsp;https://leetcode-cn.com/problems/counting-bits/

#### 思路
&emsp;&emsp;`i&(i-1)`可以去掉i最右边的一个1（如果有），因此`i&(i-1)`是比i小的，而且`i&(i-1)`的1的个数已经在前面算过了，所以i的1的个数就是`i&(i-1)`的1的个数加上1。   
&emsp;&emsp;所以动态转移方程为：`dp[i]=dp[i&(i-1)]+1`。  
#### 代码
```Java
class Solution {
    public int[] countBits(int num) {
        int[] res=new int[num+1];
        for(int i=1;i<=num;i++)
        {
            res[i]=res[i&(i-1)]+1;
        }
        return res;
    }
}
```