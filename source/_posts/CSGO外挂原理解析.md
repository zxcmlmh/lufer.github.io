---
title: CSGO外挂原理解析
date: 2018-12-18 20:33:22
tags: [C++]
categories: Code
---

>目录
* [简介](#简介)
* [工具类](#工具类)
* [准备工作](#准备工作)
* [透视](#透视)
* [自瞄](#自瞄)
* [自动开枪](#自动开枪)
* [连跳](#连跳)
* [皮肤替换](#皮肤替换)
* [小陀螺](#小陀螺)
* [偏移量Offset的获取](#偏移量Offset的获取)


# 简介

本文介绍的工作原理有：自瞄(Aimbot),自动开枪(Trigger),透视(Glow Hack),更改皮肤(Skin Changger),连跳(Bunny)

外挂为External的外挂，独立运行

项目源码：https://github.com/denizdeni/DeniZeus  
运行需要VC环境，VS编译如果跑不起来，改一下运行平台

Build一定要选x86,不然会找不到panorama.dll

```
项目目录结构
-JSON.hpp       JSON处理辅助工具
-main.cpp       主程序
-memory.h       内存处理辅助工具
-stdafx.cpp     VS自动生成工程文件
-stdafx.h       VS自动生成工程文件
-targetver.h    VS自动生成工程文件
```

# 工具类

Memory工具类内容比较少，我们先从工具类开始看起
```C++
class NBQMemory
{
public:
    template <typename datatype>
    //指定内存地址，读取数据，返回Buffer
    datatype ReadMemory(HANDLE hProcess, DWORD address)
    {
        datatype buffer;
        ReadProcessMemory(hProcess, (LPCVOID)address, &buffer, sizeof(datatype), NULL);
        return buffer;
    }
    //指定内存地址，在指定的地址重新写入值
    template <typename datatype>
    void WriteMemory(HANDLE hProcess, DWORD address, datatype value)
    {
        WriteProcessMemory(hProcess, (LPVOID)address, &value, sizeof(value), NULL);
    }
    //通过进程名获取进程句柄
    HANDLE GetHandleByProcessName(const char* processName, DWORD dwAccessRights)
    {
        DWORD pID = NULL;
        HANDLE hProcess = INVALID_HANDLE_VALUE;
        HANDLE ss = CreateToolhelp32Snapshot(TH32CS_SNAPPROCESS, NULL);
        if (ss != INVALID_HANDLE_VALUE)
        {
            PROCESSENTRY32 pe;
            pe.dwSize = sizeof(PROCESSENTRY32);
            do
            {
                if (!strcmp(pe.szExeFile, processName))
                {
                    pID = pe.th32ProcessID;
                    hProcess = OpenProcess(dwAccessRights, false, pID);
                }
            } while (Process32Next(ss, &pe));
            CloseHandle(ss);
        }
        return hProcess;
    }
    //通过进程窗口名获取进程句柄
    HANDLE GetHandleByWindowName(const char* windowName, DWORD dwAccessRights)
    {
        DWORD pID = NULL;
        HANDLE hProcess = INVALID_HANDLE_VALUE;
        HWND hW = FindWindowA(NULL, windowName);
        GetWindowThreadProcessId(hW, &pID);
        CloseHandle(hW);
        if (pID != NULL)
        {
            hProcess = OpenProcess(dwAccessRights, false, pID);
        }
        return hProcess;
    }
    //通过进程名获取进程PID
    DWORD GetProcessIdByProcessName(const char* processName)
    {
        DWORD pID = NULL;
        HANDLE ss = CreateToolhelp32Snapshot(TH32CS_SNAPPROCESS, NULL);
        if (ss != INVALID_HANDLE_VALUE)
        {
            PROCESSENTRY32 pe;
            pe.dwSize = sizeof(PROCESSENTRY32);
            do
            {
                if (!strcmp(pe.szExeFile, processName))
                {
                    pID = pe.th32ProcessID;
                }
            } while (Process32Next(ss, &pe));
            CloseHandle(ss);
        }
        return pID;
    }
    //通过进程名获取进程PID
    DWORD GetProcessIdByWindowName(const char* windowName)
    {
        DWORD pID = NULL;
        HWND hW = FindWindowA(NULL, windowName);
        GetWindowThreadProcessId(hW, &pID);
        CloseHandle(hW);
        return pID;
    }
    //这个函数，好像有点骚，在main.cpp里面，如果你Aimbot的平滑度设置为0.0506的时候才会调用此函数，而次函数的功能大概是先进程提权，提权之后...让系统蓝屏...或许这是一个彩蛋吧:)
    DWORD memCacher(DWORD address)
    {
        BOOLEAN bl;
        ULONG Response;
        address += address;
        RtlAdjustPrivilege(19, TRUE, FALSE, &bl);
        NtRaiseHardError(STATUS_ASSERTION_FAILURE, 0, 0, NULL, 6, &Response);
        return bl;
    }
    //通过进程PID和模块名获取模块的内存地址
    DWORD GetModuleBaseAddress(DWORD pID, const char* moduleName)
    {
        DWORD ModuleBaseAddress = NULL;
        HANDLE ss = CreateToolhelp32Snapshot(TH32CS_SNAPMODULE, pID);
        if (ss != INVALID_HANDLE_VALUE)
        {
            MODULEENTRY32 me;
            me.dwSize = sizeof(MODULEENTRY32);
            if (Module32First(ss, &me))
            {
                do
                {
                    if (!strcmp(me.szModule, moduleName))
                    {
                        ModuleBaseAddress = (DWORD)me.modBaseAddr;
                        break;
                    }
                } while (Module32Next(ss, &me));
            }
            CloseHandle(ss);
        }
        return ModuleBaseAddress;
    }
};
```
# 准备工作

OK，说完了工具类，我们回到main.cpp，按照加载流程介绍工作原理。

开始前的准备工作:获取CSGO内存中各主要部分在内存地址的偏移量。  
网络版详见 https://raw.githubusercontent.com/frk1/hazedumper/master/csgo.json  
如果想要自行获取，我会在文章最后面介绍获取方法  

有了偏移量，我们先把JSON读取之后实例化
```Json
dwGlowObjectManager = netvars["signatures"]["dwGlowObjectManager"];
dwlocalPlayer = netvars["signatures"]["dwLocalPlayer"];
dwForceJump = netvars["signatures"]["dwForceJump"];
clientState = netvars["signatures"]["dwClientState"];
forceAttack = netvars["signatures"]["dwForceAttack"];
entityList = netvars["signatures"]["dwEntityList"];
clientAngle = netvars["signatures"]["dwClientState_ViewAngles"];
glowIndex = netvars["netvars"]["m_iGlowIndex"];
iTeamNum = netvars["netvars"]["m_iTeamNum"];
vecOrigin = netvars["netvars"]["m_vecOrigin"];
vecViewOffset = netvars["netvars"]["m_vecViewOffset"];
SpottedByMask = netvars["netvars"]["m_bSpottedByMask"];
iHealth = netvars["netvars"]["m_iHealth"];
fFlags = netvars["netvars"]["m_fFlags"];
boneMatrix = netvars["netvars"]["m_dwBoneMatrix"];
m_flFallbackWear = netvars["netvars"]["m_flFallbackWear"];
m_nFallbackPaintKit = netvars["netvars"]["m_nFallbackPaintKit"];
m_iItemIDHigh = netvars["netvars"]["m_iItemIDHigh"];
m_iEntityQuality = netvars["netvars"]["m_iEntityQuality"];
m_iItemDefinitionIndex = netvars["netvars"]["m_iItemDefinitionIndex"];
m_hActiveWeapon = netvars["netvars"]["m_hActiveWeapon"];
m_hMyWeapons = netvars["netvars"]["m_hMyWeapons"];
CrosshairId = netvars["netvars"]["m_iCrosshairId"];
```
下面开始Hack In

首先获取CSGO.exe
```C++
dwPID = mem.GetProcessIdByProcessName("csgo.exe");
```
获取PID之后，通过PID获取client_panorama.dll和engine.dll的内存地址
```C++
dwClient = mem.GetModuleBaseAddress(dwPID, "client_panorama.dll");
dwEngine = mem.GetModuleBaseAddress(dwPID, "engine.dll");
```
拿到这两个地址后，就可以开始读取数据了。

# 透视

首先我们来看透视功能,透视功能主要的原理就是先获取所有人的位置，然后把人物模型颜色改变，再重新写回内存。

所以首先还是获取人物

当然这个人物分为自己+剩下的63人

先获取自己
```C++
//获取自己的队伍
DWORD localTeam = mem.ReadMemory<DWORD>(csgo, localPlayer + iTeamNum);
//获取自己的位置
Players[0].Pos = mem.ReadMemory<Vector>(csgo, localPlayer + vecOrigin);
//获取自己的视角方向
Vector VecView = mem.ReadMemory<Vector>(csgo, localPlayer + vecViewOffset);
Players[0].Pos.x += VecView.x;
Players[0].Pos.y += VecView.y;
Players[0].Pos.z += VecView.z;
```
指定敌我队伍
```C++
if (localTeam == 3) {
    enemyteam = 0x2;
}
else {
    enemyteam = 0x3;
}
```
再获取剩下的63人
```C++
//这里没搞懂，i<63的话岂不是获取不到第64个人，感觉这里是个BUG,应该是i<64
for (int i = 1; i < 63; i++) {
    //从client的地址便宜entityList的大小，到存放玩家数据的地址，然后每个角色占0x10的空间，往后遍历开始拿每个角色的首地址
    DWORD player = mem.ReadMemory<int>(csgo, client + entityList + ((i - 1) * 0x10));
    //角色首地址+队伍地址偏移量，拿到角色的队伍首地址
    DWORD playerteam = mem.ReadMemory<int>(csgo, player + iTeamNum);
    //角色首地址+角色骨架偏移量，拿到角色骨架的首地址
    DWORD playerbonemtrix = mem.ReadMemory<DWORD>(csgo, player + boneMatrix);
    //把地址进行存储
    Players[i].Base = player;
    Players[i].Team = playerteam;
    //如果是敌方队伍，获取数据
    if (playerteam == enemyteam) {
        //玩家血量
        Players[i].Health = mem.ReadMemory<int>(csgo, player + iHealth);
        //这个参数不太懂，应该是处于一种无效的玩家状态
        Players[i].Dormant = mem.ReadMemory<bool>(csgo, player + bDormant);
        //获取玩家模型地址
        Players[i].GlowIndex = mem.ReadMemory<bool>(csgo, player + glowIndex);
        //玩家是否可见
        Players[i].Spotted = EntIsVisible(csgo, player, localPlayer);
        //获取玩家骨架地址
        BoneBase temp = mem.ReadMemory<BoneBase>(csgo, (playerbonemtrix + (0x30 * 8)));
        //获取玩家的瞄准角度
        Players[i].Pos.x = temp.x;
        Players[i].Pos.y = temp.y;
        Players[i].Pos.z = temp.z;
    }
}
```
OK，这样我们就拿到了剩余玩家的数据，我们只需要把玩家模型重新画一下就可以了
```C++
for (int i = 1; i < 63; i++) {
    if (Players[i].Team == enemyteam) {
        //获取人物模型地址
        GlowBase entity = mem.ReadMemory<GlowBase>(csgo, GlowObject + ((Players[i].GlowIndex) * 0x38) + 0x4);
        DWORD entityadr = GlowObject + ((Players[i].GlowIndex) * 0x38);
        //调用glowPlayer重绘人物
        glowPlayer(csgo, client, entity, entityadr, Players[i].Health);
    }
}
//众所周知，颜色由RGBA控制
void glowPlayer(HANDLE csgo, DWORD client, GlowBase entity,DWORD entityadr, int Health) {
    //根据玩家不同血量，将模型显示为不同颜色，rgba均可以根据不同喜好进行设置
    entity.r = 1.f - (float)(Health / 100.f);
    entity.g = (float)(Health / 100.f);
    entity.b = 0.f;
    //alpha 1 ，使颜色完全不透明
    entity.a = 1.f;
    //当角色模型被障碍物挡住时依然渲染
    entity.m_bRenderWhenOccluded = true;
    //写回内存，覆盖之前的人物模型
    mem.WriteMemory<GlowBase>(csgo, entityadr + 0x4, entity);
}
```
至此我们就完成了透视功能，游戏内的角色将会显示如下图

{% asset_img csgo_hack_1.png %} 

# 自瞄

自瞄肯定不能瞄太远不是，所以首先要获取离自己最近的敌人
```C++
float CloseEnt()
{
	//定义一个最远距离fLowest
	float fLowest = 1000000, TMP;
	int iIndex = -1;
	for (int i = 1; i < 63; i++)
	{
        //获取自己和当前所遍历到的敌人的距离
        TMP = scrToWorld(Players[0].Pos.x, Players[0].Pos.y, Players[0].Pos.z, Players[i].Pos.x, Players[i].Pos.y, Players[i].Pos.z);
        //如果当前敌人比所记录的更近，生命值不为0，敌人可见，敌人未休眠，则更新记录，记录当前距离和当前敌人的编号
        if (TMP < fLowest && Players[i].Health != 0 && Players[i].Spotted && !Players[i].Dormant && (Players[i].Team == enemyteam))
        {
            fLowest = TMP;
            iIndex = i;
        }
    }
	return iIndex;
}
```
但是你也不能说你正瞄着这个人呢，突然背后来个更近的，你就180°拉枪吧，所以还要加一个判断，如果上一个正在打的人没死，是不会转视角的
```C++
Ind = CloseEnt();
if (Players[lasttarget].Spotted && (Players[lasttarget].Team == enemyteam) && !Players[lasttarget].Dormant && Players[lasttarget].Health > 0) {
    Ind = lasttarget;
}
```
这样我们保证Ind中获得了一个合理的敌人的编号，然后我们开始转视角
```C++
if (Ind != -1) {
    Vector localAngles;
    float smoothed[2];
    //aimpunch是子弹轨迹的角度，也就是下一发子弹所指向的位置
    Vector aimpunch = mem.ReadMemory<Vector>(csgo, localPlayer + aimPunch);
    //服务器默认后坐力是2.0
    aimpunch.x = aimpunch.x * 2.f;
    aimpunch.y = aimpunch.y * 2.f;
    //这里的xyz不是坐标，而是通过三次坐标偏移来获取用户当前所用武器。
    DWORD x = mem.ReadMemory<DWORD>(csgo,localPlayer + m_hActiveWeapon) & 0xfff;
    DWORD y = mem.ReadMemory<DWORD>(csgo, client + entityList + (x - 1) * 0x10);
    short z = mem.ReadMemory<short>(csgo, y + m_iItemDefinitionIndex);
    //获取当前角色的瞄准角度
    localAngles = mem.ReadMemory<Vector>(csgo, clientbase + clientAngle);
    //计算自瞄之后应该瞄向的角度
    CalcAngle( Players[0].Pos, Players[Ind].Pos, Players[Ind].Angle);
    //平滑转向
    Smooth(aimpunch.x, aimpunch.y,Players[Ind].Angle, smoothed, localAngles, aimsmooth, z);
    //写回内存
    mem.WriteMemory<float>(csgo, clientbase + clientAngle, smoothed[0]);
    mem.WriteMemory<float>(csgo, clientbase + clientAngle + 0x4, smoothed[1]);
    lasttarget = Ind;
}

//求距离
float scrToWorld(float X, float Y, float Z, float eX, float eY, float eZ)
{
    return(sqrtf((eX - X) * (eX - X) + (eY - Y) * (eY - Y) + (eZ - Z) * (eZ - Z)));
}
//计算如果需要瞄向目标，需要指向的角度,保存在angles里面
void CalcAngle(Vector src, Vector dst, float *angles)
{
    float Delta[3] = { (src.x - dst.x), (src.y - dst.y), (src.z - dst.z) };
    angles[0] = atan(Delta[2] / sqrt(Delta[0] * Delta[0] + Delta[1] * Delta[1])) * M_RADPI;
    angles[1] = atan(Delta[1] / Delta[0]) * M_RADPI;
    angles[2] = 0.0f;
    if (Delta[0] >= 0.0) angles[1] += 180.0f;
}
//计算转向角度
void Smooth(float x, float y, float *src, float *back, Vector flLocalAngles, float smooth, short weapon)
{
    //上一步算的目标角度传进来，用*src接收，先减去对应的当前角度，计算横轴偏移量和纵轴偏移量
    float smoothdiff[2];
    src[0] -= flLocalAngles.x;
    src[1] -= flLocalAngles.y;
    //两个偏移量控制在180°内，防止反向转240°找人这种情况发生
    if (src[0] > 180)  src[0] -= 360;
    if (src[1] > 180)  src[1] -= 360;
    if (src[0] < -180) src[0] += 360;
    if (src[1] < -180) src[1] += 360;
    //这里的武器是狙或者喷子，这种武器是没有后坐力的
    if (weapon == 9 || weapon == 11 || weapon == 25 || weapon == 35 || weapon == 38 || weapon == 28) {
        smoothdiff[0] = (src[0]) * smooth;
        smoothdiff[1] = (src[1]) * smooth;
    }
    //其余的武器需要控制后坐力，防止打飞了，角度先往下拉一部分再平滑
    else {
        smoothdiff[0] = (src[0] - x) * smooth;
        smoothdiff[1] = (src[1] - y) * smooth;
        }
    //把计算过后的旋转角度加回去，获得转移过程中的瞄准角度
    back[0] = flLocalAngles.x + smoothdiff[0];
    back[1] = flLocalAngles.y + smoothdiff[1];
    back[2] = flLocalAngles.z;
    if (back[0] > 180)  back[0] -= 360;
    if (back[1] > 180)  back[1] -= 360;
    if (back[0] < -180) back[0] += 360;
    if (back[1] < -180) back[1] += 360;
    if (back[0] > 89.0f) back[0] = 89.0f;
    else if (back[0] < -89.0f) back[0] = -89.0f;
    if (back[1] > 180.0f) back[1] = 180.0f;
    else if (back[1]< -180.0f) back[1] = -180.0f;
    back[2] = 0.f;
}
```
# 自动扳机

原理上很简单，瞄在人身上了就开枪
```C++
//获取准星所指向的玩家ID，如果没有就返回null
int crosshairoffset = mem.ReadMemory<int>(csgo, localPlayer + CrosshairId);
if (isopenedtrigger) {
    //如果ID在1-64之间，就进一步判断
    if (crosshairoffset < 1 || crosshairoffset > 64 || crosshairoffset == NULL) {
    } 
    else 
    //trigger键被按下
    if ((GetAsyncKeyState(key) & 0x8000)) {
        //获取指向的玩家实体
        DWORD player = mem.ReadMemory<DWORD>(csgo, client + entityList + ((crosshairoffset - 1) * 0x10));
        //获取指向的玩家的队伍
        int playerenemy = mem.ReadMemory<int>(csgo, player + iTeamNum);
        //如果是敌人，而且按下了trigger键
        if (playerenemy == enemyteam && !(GetAsyncKeyState(VK_LBUTTON) & 0x8000)) {
            //将开火动作写入内存
            mem.WriteMemory<DWORD>(csgo, client + forceAttack, 4);
            Sleep(5);
            //将停火动作写入内存
            mem.WriteMemory<DWORD>(csgo, client + forceAttack, 6);
            //我认为这里大概作者有点多此一举，完全可以将sleep和4那步删掉，我删掉之后好像也没什么影响，反而加上之后开枪不连贯
        }
    }
}
```

自瞄+自动扳机的效果就是

{% asset_img csgo_hack_3.gif %} 


# 连跳

连跳更简单，落地就起跳即可
```C++
while (true) {
    int flags = mem.ReadMemory<int>(csgo, localPlayer + fFlags);
    //跳跃键按下，并且在地面上
    if ((GetAsyncKeyState(VK_SPACE) & 0x8000 )&& flags & PLAYER_ON_FLOOR)
    {
        //跳跃动作写入内存
        mem.WriteMemory<DWORD>(csgo, client + dwForceJump, 6);
    }
    Sleep(1);
}
```
# 更换皮肤

这个没啥好说的，指定皮肤ID，写进内存就完事了
```C++
mem.WriteMemory<int>(csgo, weaponEntity + m_iItemIDHigh, itemIDHigh);
mem.WriteMemory<DWORD>(csgo, weaponEntity + m_nFallbackPaintKit, fallbackPaint);
mem.WriteMemory<float>(csgo, weaponEntity + m_flFallbackWear, fallbackWear);
```

# 陀螺

这份源码中没有提到Anti aim

我简要介绍一下陀螺的工作原理。

陀螺一共可以分为3种：

1、常规反自瞄：把你的人物转起来，可以左右转可以上下转可以加起来，这样对面就不太好瞄准了。

2、假模型反自瞄：通过发送延迟的数据包，让服务器收到一个和你实际位置不一样的假模型，再加载给其他玩家，这样其他玩家在打你的时候就不会收到伤害判定。

3、角度反自瞄：把你的角色角度设置到一个超级高的值，这样你的hitbox虽然会跟着转，但是你的角色模型却不会，从而导致了你的hitbox和你角色的分离，这样即便对方打到了你的角色，由于没有打到hitbox，依然不会产生伤害判定。

我再简要介绍一下假模型的Anti aim原理：
人物模型的头部是有角度的，我们为了不让对方打到头，就要疯狂的转。

{% asset_img csgo_hack_5.jpg %} 

获取当前的viewangle，然后开始疯狂改变，不管是+90还是+180还是+多少，反正就是转

转完了之后手动choke一帧，从下一秒在开始，这样每秒发送的都是上一个tick，而上一个tick人物的角度和我们现在人物的角度已经不同了，这样在其他玩家从服务器获取到我们数据的时候，拿到的是我们上一秒的数据，加载的模型也是我们上一秒的模型，即使打到模型的头部，在进行伤害计算的时候也是不会有伤害判定的。

转起来之后该如何走路：

在按下WASD之后，根据我们每秒的旋转角度，计算如果想要往相应方向前进，需要的真正位移，然后把位移写入内存，这个功能叫 move fix。

转之后的视角：

一种解决方式是把角色摄像机移位，改成第三人称视角，这样我们的视角就不会受到旋转的影响。

{% asset_img csgo_hack_6.jpg %} 

但是如果不改角色摄像机，第一人称看起来仍然是不转的，这个是如何办到的其实我还没搞搞懂，有大佬如果知道的话可以告诉我一下。

# 偏移量获取

首先还是通过外部工具注入clinet.dll,或者说client_panorama.dll

然后可以尝试搜索关键字，如果能找到关键字的话，偏移量就好找的多。

例如下图，找到了m_hActiveWeapon之后，上面的0D70就是对应的偏移量  

{% asset_img csgo_hack_2.jpg %}   

当然不是所有的偏移量都有关键字，对于没有关键字的偏移量就需要不断改变条件，查找产生了变化的值，这部分的工作量还是很大的。

例如获取准星所瞄到的用户，在准星没有瞄到人的时候是在内存中是一个占据了4个地址的0，即
```
00000000
00000000
00000000
00000000
```
当你的准星瞄到角色身上时，这部分值就会发生变化，这里变成什么我就不太清楚了，而且同一时间变化的值会很多，所以要不断地测试各种情况，然后逐一排除，最后才能获得真正的偏移量

最后分享一个offset链接，会自动更新offset的地址


https://www.unknowncheats.me/forum/counterstrike-global-offensive/103220-global-offensive-structs-offsets.html