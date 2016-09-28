# 教你抓取使用https的app的网络请求(ios)

> 当ios中app是采用https后,因为你没有对应的证书,这时候你是抓取不到它的网络数据的.
>
> 这里我采用的思路是hook网络请求回调的方法,然后在控制台里数据返回的数据.



## 1.以得到app为例

![](http://upload-images.jianshu.io/upload_images/546464-5a2416571241ac97.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

> 需要工具:一部越狱机器,当然不越狱,每次注入重签名也行(就是有点麻烦)

## 2.砸壳

* (1)下载以下文件,然后解压,make后生成dumpdecrypted.dylib

[https://github.com/stefanesser/dumpdecrypted/archive/master.zip](https://github.com/stefanesser/dumpdecrypted/archive/master.zip)

* (2)把dumpdecrypted.dylib文件放到得到app中的Documents目录里

> cd进去对应的Documents的目录

找到对应的运行二进制文件

![Paste_Image.png](http://upload-images.jianshu.io/upload_images/546464-6a70c34062f91b2e.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

然后运行砸壳命令:

```shell
DYLD_INSERT_LIBRARIES=dumpdecrypted.dylib /var/containers/Bundle/Application/9C5988AE-CF8C-4D84-BA04-F930DACD6600/LuoJiFM-IOS.app/LuoJiFM-IOS
```

得到砸壳后的文件:

![Paste_Image.png](http://upload-images.jianshu.io/upload_images/546464-72cc203637b5d248.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

用ifunbox把它拖出来,不嫌麻烦用scp命令也行

然后用class-dump把头文件导出来:

```shell
./class-dump -H LuoJiFM-IOS.decrypted -o heads
```

然后得到所有的头文件:

![Paste_Image.png](http://upload-images.jianshu.io/upload_images/546464-4538208681468a22.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

## 3.找到对应的网络回调方法

> 可以建个空工程用xcode打开头文件

搜索connection有对应的文件

![Paste_Image.png](http://upload-images.jianshu.io/upload_images/546464-1e9059a2fa10e63b.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

这里为什么有AFURLConnectionOperation和APURLConnectionOperation两个方法,到底是用哪个的?两个都试试,然后发现是用了AFURLConnectionOperation.

查看里面的方法,懂ios开发的都是回调是下面这个方法

![DE3384AB-9F8A-4848-B590-9EB278196B77.jpeg](http://upload-images.jianshu.io/upload_images/546464-808668f571361365.jpeg?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)



## 4.写theos程序插入

* (1)创建

![C91A9671-A6B5-4BD7-B8B1-37ABA0702A3D.jpeg](http://upload-images.jianshu.io/upload_images/546464-2a82574074d8f7e3.jpeg?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

* (2)Tweak.xm代码

```objective-c
%hook AFURLConnectionOperation

- (void)connection:(NSURLConnection *)arg1 didReceiveData:(NSData *)arg2 {
  %log;
  %orig;
  id dict = [NSJSONSerialization JSONObjectWithData:arg2 options:kNilOptions error:nil];
  NSLog(@"dddddd1: %@",arg1);
  NSLog(@"dddddd2: %@",dict);
}

%end
```

* (3)运行安装

```shell
make package install
```

## 5.通过Xcode device控制台查看输出的网络数据

![CB02E3B1-F183-4D38-822B-795141B19610.jpeg](http://upload-images.jianshu.io/upload_images/546464-dfc5096cfac30625.jpeg?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

 ## 6.这个方法适用于其他app

