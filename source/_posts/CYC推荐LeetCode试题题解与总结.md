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
### 1. 最短单词路径

https://leetcode-cn.com/problems/word-ladder/

#### 思路
说实话，看题解思路看懂了，自己做还是没头绪。  
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
## 七、动态规划
### 1. 爬楼梯

https://leetcode-cn.com/problems/climbing-stairs/

#### 思路
如果用`dp[i]`代表到第i级台阶的跳法，由于一次可以跳一级或者两级，所以`dp[i]=dp[i-1]+dp[i-2]`
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

https://leetcode-cn.com/problems/house-robber/

#### 思路
如果用`dp[i]`代表在当前房屋所能获取的最大收益，那么对于房屋i，一共有两种选择，即偷和不偷。如果偷的话，获得的收益就是`dp[i-2]+nums[i]`,如果不偷，那么收益就是`dp[i-1]`，两者取大者，就是当前房屋的最大收益，随后遍历即可。
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

https://leetcode-cn.com/problems/house-robber-ii/

#### 思路
与非环抢劫的区别就是要单独处理首尾，第一个与最后一个只能选一个抢。
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

https://leetcode-cn.com/problems/minimum-path-sum/

#### 思路
对于矩阵每一行进行一次遍历，每行的每个格子都只有从上走下来和从左走过来两种选择，所以`dp[i][j]=min(dp[i][j-1],dp[i-1][j])+grid[i][j]`。
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

https://leetcode-cn.com/problems/unique-paths/

#### 思路
对于每个`dp[i][j]`，到达该格子有从左和从上两个方式到达，最顶上和最左边的边界只有一种方式到达。
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

https://leetcode-cn.com/problems/range-sum-query-immutable/

#### 思路
因为会多次调用，所以每次都累加是不合适的。遍历数组一遍，保存每次到i的累和，然后返回两个边界的累和差值即可。
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

https://leetcode-cn.com/problems/arithmetic-slices/

#### 思路
用`dp[i]`代表以`A[i]`为结尾的等差数列的数量，那么只有两种情况：
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

https://leetcode-cn.com/problems/integer-break/

#### 思路
如果用`dp[i]`代表正整数i拆分后的最大乘积，那么他有三种选择：
1. dp[i]自身
2. 从`1~dp[i]`遍历，然后获得j*dp[i-j]
3. 从`1~dp[i]`遍历，然后获得j*(i-j)

取三者最大值
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

https://leetcode-cn.com/problems/perfect-squares/

#### 思路
两个思路，一个是采用DP的方式，一个是采用数学定理的方式。
1. DP  
定义一个函数f(n)表示我们要求的解。f(n)的求解过程为：
f(n) = 1 + min{
  f(n-1^2), f(n-2^2), f(n-3^2), f(n-4^2), ... , f(n-k^2) //(k为满足k^2<=n的最大的k)
}
2. 数学定理  
四平方定理：任何一个正整数都可以表示成不超过四个整数的平方之和。   
推论：满足四数平方和定理的数n（四个整数的情况），必定满足 n=4^a(8b+7)
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

https://leetcode-cn.com/problems/decode-ways/

#### 思路
本题与跳台阶本质上相同，如果我们用`dp[i]`代表当前位可以解码的方式，那么对于第i位有两种选择。  
1. 第i位单独解码，此时`dp[i]=dp[i-1]`
2. 第i位与第i-1位共同解码，此时`dp[i]=dp[i-2]`

综上，所以`dp[i]=dp[i-1]+dp[i-2]`。  
但是要注意，0不能单独解码，所以如果s[i-1]是0，那么dp[i-1]也是0。  
如果s[i-2]是0，则不能加上前一位进行解码，即dp[i-2]是0。  
同时由于解码数字不会超过26，所以如果最近两位解码结果超过26，那么dp[i-2]也是0。 
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

https://leetcode-cn.com/problems/longest-increasing-subsequence/

#### 思路
如果我们用`dp[i]`代表以第i个数字结尾的最长递增序列的长度，则我们从`0~i`遍历j，对于每一个比`nums[i]`小的`nums[j]`,都要比较`dp[i]=Max(dp[i],dp[j]+1)`。此处注意`dp[i]`的默认值是1，最后取dp数组最大值即可。
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

https://leetcode-cn.com/problems/maximum-length-of-pair-chain/

#### 思路
与上一题基本一样，本题只是需要先将数对按起点排序，然后遍历找到可以与当前数对形成数链的数对，比较dp
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

https://leetcode-cn.com/problems/wiggle-subsequence/

#### 思路
用两个变量up和down分别计算向上摆动和向下摆动的数量，从头遍历数组，如果数组向上了，那么当前的up就是上一位的down+1，如果数组向下了，那么当前的down就是上一位的up+1。
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

https://leetcode-cn.com/problems/partition-equal-subset-sum/

#### 思路
对于背包问题，我们可以用`dp[i][j]`来表示从`0~i`中是否存在满足价值为j的组合。  
而是否满足主要有两种选择：
1. 如果不选择当前`nums[i]`的价值，则依赖于`dp[i-1][j]`，两者判断状态相同。  
2. 如果选择当前`nums[i]`的价值，则依赖于`dp[i-1][j-nums[i]]`,两者判断状态相同。

如果有一种情况满足需求，则`dp[i][j]`可以实现。
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

https://leetcode-cn.com/problems/target-sum/

#### 思路
引用CYC推理
```
将数组看成两部分，P 和 N，其中 P 使用正号，N 使用负号，有以下推导：
                  sum(P) - sum(N) = target
sum(P) + sum(N) + sum(P) - sum(N) = target + sum(P) + sum(N)
                       2 * sum(P) = target + sum(nums)
因此只要找到一个子集，令它们都取正号，并且和等于 (target + sum(nums))/2，就证明存在解。
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

https://leetcode-cn.com/problemset/all/?search=474

#### 思路
多维费用问题，我们用`dp[i][j]`表示使用i个0和j个1能表示的字符串的最大数量。  
则`dp[i][j]=Max(dp[i][j],dp[i-zero][j-one]+1)`,其中zero代表当前0的数量，1代表当前1的数量。
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

https://leetcode-cn.com/problems/coin-change/

#### 思路
背包大小就是所给的amout，占据背包容量的就是硬币面额。   
`完全背包只需要将 0-1 背包的逆序遍历 dp 数组改为正序遍历即可。`
然后取最小数量，对于每个`dp[i][j]`,最小数量有三种情况：
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

https://leetcode-cn.com/problems/coin-change-2/submissions/

#### 思路
与上题类似，只不过本题需要求可能获得的组合总数，对于每一个`dp[i][j]`，其状态转移方程为`dp[i][j]=dp[i-1][j]+dp[i][j-coins[i]]`。
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

https://leetcode-cn.com/problems/word-break/

#### 思路
字典单词是可以重复使用的，故本题为一个完全背包问题，用字典单词来填充背包，所要对比的价值就是字符串。  
对于有序的背包问题，将物品的迭代放在最里层，背包的迭代放在外层。
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

https://leetcode-cn.com/problems/combination-sum-iv/

#### 思路
依然是有序的完全背包问题。
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
对于背包问题，主要有两种，即`0-1背包`or`完全背包`，这其中又可分为`元素有序`与`元素无序`两种。
对于无序问题，则对于元素的遍历在外层，对于背包的遍历在内层。我们以`V[]`来代表元素数组，`target`来代表目标价值，则两层循环可以表示为
```Java
for(int i=0;i<nums.length;i++)
    for(int j=target;j>=V[i];j--)
```
对于完全背包，则需要将内层循环的顺序反序
```Java
for(int i=0;i<nums.length;i++)
    for(int j=V[i];j<=target;j++)
```
而对于有序问题，需要将两层循环换位，可知循环条件需为`V[i]<=j<=target`,所以完全背包的双层循环可以表示为：、
```Java
for(int i=1;i<=target;i++)
    for(int j=0;j<V.length&&j>=V[i];j++)
```
### 21. 需要冷却期的股票交易

https://leetcode-cn.com/problems/best-time-to-buy-and-sell-stock-with-cooldown/comments/

#### 思路
sell[i]表示截至第i天，最后一个操作是卖时的最大收益；  
buy[i]表示截至第i天，最后一个操作是买时的最大收益；  
cool[i]表示截至第i天，最后一个操作是冷冻期时的最大收益；  
递推公式：
```  
sell[i] = max(buy[i-1]+prices[i], sell[i-1]) (第一项表示第i天卖出，第二项表示第i天冷冻)  
buy[i] = max(cool[i-1]-prices[i], buy[i-1])  (第一项表示第i天买进，第二项表示第i天冷冻)  
cool[i] = max(sell[i-1], cool[i-1])          (第一项表示第i天卖出，从而变为冷冻期，第二项表示第i天冷冻)  
```
此外还要注意数组长度只有1个的时候是不买的，利润为0。其他情况buy[0]一定是prices[0]的相反数。
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

https://leetcode-cn.com/problems/best-time-to-buy-and-sell-stock-with-transaction-fee/

#### 思路
和上题差不多，只是本次在每次卖出时需要加手续费，以及没有冷却期
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

https://leetcode-cn.com/problems/best-time-to-buy-and-sell-stock-iii/

#### 思路
对于任意一天考虑四个变量:  
fstBuy: 在该天第一次买入股票可获得的最大收益   
fstSell: 在该天第一次卖出股票可获得的最大收益  
secBuy: 在该天第二次买入股票可获得的最大收益  
secSell: 在该天第二次卖出股票可获得的最大收益  
分别对四个变量进行相应的更新, 最后secSell就是最大  
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

https://leetcode-cn.com/problems/best-time-to-buy-and-sell-stock-iv/

#### 思路
对上一题进行推广，对每一个买卖次数进行遍历即可。
但是由于K不固定，直接DP会MLE，所以对于K大于数组长度一半的情况下，即每天都可以考虑买入卖出的情况下，用贪心即可，既节省空间，又节省时间。
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