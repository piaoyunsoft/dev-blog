# QQ电话适配iOS10 Callkit框架分享

**share from DEV Club tencent**

> 嘉宾简介：段定龙 腾讯QQ音视频 目前负责腾讯QQ音视频iOS客户端的开发工作
>
> 内容简介：苹果在iOS 10开放了系统电话权限，全新的Callkit框架能够让音视频的第三方应用获得系统级的通话体验，本次分享将主要介绍如何应用Callkit框架和一些适配经验。

## 1 Callkit概述  

> 苹果在2016年的WWDC大会上推出了iOS10，提供了一系列更加开放的新特性，其中最吸引我们的就是Callkit，这个框架能够让第三方应用获得系统电话的权限以及体验。什么概念呢？

首先得介绍一下Callkit的框架。他分为三大模块：VoIP，CallCenter和来电屏蔽，要实现上述功能我们只需要关注Voip模块。Voip模块里主要有两个类：CXProvider和CXCallController。

![WechatIMG19.jpeg](http://upload-images.jianshu.io/upload_images/546464-04b35003dde1733f.jpeg?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

* CXProvider可以理解为处理系统电话界面有关的逻辑，比如来电呼起系统电话界面或者将用户在系统电话界面上的操作通知给App。
  CXCallController则是将用户在App界面上的操作通知给系统。
* 更具体地，网络通话适配Callkit主要包含四个流程：收到来电主动通知Callkit、用户在Callkit界面点击接听、用户在手Q界面点击挂断、用户在系统通讯录发起新的通话。下面将通过四个流程来介绍CXProvider、CXCallController、INIntent事件的使用，举一反三。

### (1)首先我们看最简单的收到来电主动通知Callkit：

![WechatIMG23.jpeg](http://upload-images.jianshu.io/upload_images/546464-81c353dbef7cfe7d.jpeg?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

收到服务器信令通知后只需要调用CXProvider的reportNewIncomingCall就可以了。调用过后系统通讯录会自动沉淀，系统电话界面会展示。

![WechatIMG25.jpeg](http://upload-images.jianshu.io/upload_images/546464-3b4ede554e087369.jpeg?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

图中的setCategory是为了避免出现无声问题，这个在后面会进行解释。

### (2)然后是用户在Callkit界面点击接听，这里的流程通用于用户对Callkit的操作回调：

![WechatIMG28.jpeg](http://upload-images.jianshu.io/upload_images/546464-3ebd1453a11321ad.jpeg?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

用户点击接听后，我们会受到CXAnswerCallAction的回调，只需要在这里面添加App原来的音视频通话逻辑，再调用fulfill，整个流程就完成了。

![WechatIMG30.jpeg](http://upload-images.jianshu.io/upload_images/546464-7f55b79cf41e9caa.jpeg?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

### (3)再然后是用户在App内点击挂断

![WechatIMG32.jpeg](http://upload-images.jianshu.io/upload_images/546464-db455823bb3d95de.jpeg?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

这时候我们需要添加一个CXEndCallAction到CXTransaction并调用requestTransaction请求执行，之后的流程与Callkit界面点击接听类似，收到CXEndCallAction回调，执行相应逻辑，调用fulfill完成流程。所有用户在app内的操作都以这种方式通知Callkit。

![WechatIMG34.jpeg](http://upload-images.jianshu.io/upload_images/546464-5fa2da58f0168535.jpeg?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

### (4)最后我们来看一下如何从App外部发起，以系统通讯录为例子（Siri其实是一样样的）

![WechatIMG36.jpeg](http://upload-images.jianshu.io/upload_images/546464-3c4d50d6b135c55e.jpeg?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

用户在点击系统通讯录的沉淀后，我们会收到系统事件通知（INStartAudioCallIntent或者INStartVideoCallIntent），然后就类似于用户在App内点击挂断的流程，只不过这次换成发起了，添加CXStartCallAction到CXTransaction并调用requestTransaction请求执行，收到CXStartCallAction的回调，执行相应逻辑后调用fulfill完成流程。

![WechatIMG38.jpeg](http://upload-images.jianshu.io/upload_images/546464-621d042f87451a1c.jpeg?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

![WechatIMG39.jpeg](http://upload-images.jianshu.io/upload_images/546464-c14d6aa131931067.jpeg?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

以上便是网络通话中主要的4个场景流程，不知道大家对CXProvider和CXCallController的功能和使用场景是否已经有一个大致的了解。最后用一张图来再解释一下：

![WechatIMG41.jpeg](http://upload-images.jianshu.io/upload_images/546464-e6c72dd6260b18b2.jpeg?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

适配过总的结构如图所示，系统界面由系统自己控制，我们没有办法直接对其进行操作，这里有点坑，有很多苹果的BUG无法避免，我们需要CXCallController去通知系统更新，并通过CXProvider的回调处理在系统界面上的操作。

## 2 Callkit框架 

> 回顾了一下整个Callkit的架构后，下面将分享一些适配时候的经验，包括ID的对应和无声问题的处理

Callkit的架构里有两个ID标志，UUID和CXHandle，前者是用于表示每一次通话，后者则是用于标识具体的用户，比如reportNewIncomingCall的时候我们需要新的UUID去标识这次通话，而在系统通讯录沉淀的时候，则使用CXHandle区分用户。

QQ号码是一套独立的ID体系，区别于手机号，所以我们需要定义特殊的CXHandle字符串，并将UUID，CXHandle和QQ自己的AVID联系起来，统一管理。

## 3 遇到的问题 

整个适配过程中，我们遇到最大的问题就是出现通话无声问题，由于没有任何文档，在无数次的尝试后得出结论，苹果对于Callkit和App的音频接口调用顺序有严格的要求，如果不按照一下顺序来调用会出现无声问题甚至Crash：

![WechatIMG47.jpeg](http://upload-images.jianshu.io/upload_images/546464-a11295450ce3df38.jpeg?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

> 图中不同颜色代表不同的流程，系统音频模块（AVAudioSession）分为六个操作：
> 初始化（AudioUnitInit），
> 去初始化（AudioUnitUninit），
> 通知激活（didActivateSession），
> 开启音频（AudioUnitStart），
> 通知结束（didDeativateSession），
> 关闭音频（AudioUnitStop）

重点其实就两个：
1 在流程开始前setCategory为PlayAndRecord
2 调用音频模块函数的时机：
发起通话：Callkit回调 -> 初始化 -> fulfill -> 通知激活 -> 开启音频 
结束通话：Callkit回调 -> fulfill -> 通知结束 -> 关闭音频 -> 去初始化

> 最后提一下Pushkit通道的使用可以保证用户杀进程或者退后台了，依然可以后台唤起进程，完成通话，不过这不是今天的重点，就带过了。

## 4 更多资料

> 由于苹果对整个架构真的没有什么文档解释，所有的工作都是在适配的过程中进行摸索，每个beta版本的接口都有所变动，太细节性的东西今天就不一一介绍了。

当然我们可以从一下途径获得更多资料（聊胜于无）
[官方文档](https://developer.apple.com/reference/callkit)

[WWDC2016视频介绍](https://developer.apple.com/videos/play/wwdc2016/230/)

[WWDC2016演示文档](http://devstreaming.apple.com/videos/wwdc/2016/230b83wfxc7m69dm90q/230/230_enhancing_voip_apps_with_callkit.pdf)
[SpeakerBox: Using Callkit to create a VoIP app（9.13有更新的版本）](https://developer.apple.com/library/prerelease/content/samplecode/Speakerbox/Introduction/Intro.html)

