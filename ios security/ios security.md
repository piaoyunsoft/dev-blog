# ios security

## 1.代码混淆

> 使用classdump对原程序进行dump，可以dump出所有源程序的函数所有信息.
>
> 这会导致他人了解了程序结构方便逆向。



[代码混淆](http://www.jianshu.com/p/98227950a474)

* confuse.sh

```shell
#!/usr/bin/env bash

STRING_SYMBOL_FILE="$PROJECT_DIR/func.list"
HEAD_FILE="$PROJECT_DIR/$PROJECT_NAME/codeObfuscation.h"
export LC_CTYPE=C

// 随机生成字符串名
ramdomString() {
openssl rand -base64 64 | tr -cd 'a-zA-Z' |head -c 16
}

rm -f $HEAD_FILE
touch $HEAD_FILE

echo '#ifndef ZhongCai500_codeObfuscation_h
#define ZhongCai500_codeObfuscation_h' >> $HEAD_FILE
echo "//confuse string at `date`" >> $HEAD_FILE

cat "$STRING_SYMBOL_FILE" | while read -ra line; do
if [[ ! -z "$line" ]]; then
ramdom=`ramdomString`
echo $line $ramdom
echo "#define $line $ramdom" >> $HEAD_FILE
fi
done

echo "#endif" >> $HEAD_FILE
```

## 2.反动态库注入

> 动态注入是利用了 iOS系统中 **DYLD_INSERT_LIBRARIES**这个环境变量,如果设置了**DYLD_INSERT_LIBRARIES** 环境变量，那么在程序运行时，动态链接器会先加载该环境变量所指定的动态库；也就是说，这个动态库的加载优先于任何其它的库，包括 libc。

我们可以在代码中通过判断环境变量来检测是不是被注入：

```objective-c
char *env = getenv("DYLD_INSERT_LIBRARIES");
```

如果方法返回非空，我们可以做一些上报之类的.

**后面两个工具都是用来注入的**：

insert_dylib通过向 mach-o文件的 loadcommand段插入 LC_LOAD_DYLIB数据来加载第三方库。

对于 insert_dylib，我们可以通过在 Xcode的Build Settings中找到“Other Linker Flags”在其中加上

```shell
-Wl,-sectcreate,__RESTRICT,__restrict,/dev/null
```

指令来绕过 dylib加载额外的第三方库

(但是破解这一招也非常简单，上面的链接也说了，用 0xED打开二进制文件，把`__RESTRICT`全局替换成其它名字即可。)

## 3.反动态调试

> 当程序运行后，使用 debugserver *:1234 -a yourappName ，附加进程出现 segmentfault 11时，程序内部一般来说调用了ptrace.

* ptrace防动态调试的方法:

```objective-c
#import <dlfcn.h>
#import <sys/types.h>

typedef int (*ptrace_ptr_t)(int _request, pid_t _pid, caddr_t _addr, int _data);
#if !defined(PT_DENY_ATTACH)
#define PT_DENY_ATTACH 31
#endif

void disable_gdb() {
    void* handle = dlopen(0, RTLD_GLOBAL | RTLD_NOW);
    if (handle==NULL) {
        return;
    }
    ptrace_ptr_t ptrace_ptr = dlsym(handle, "ptrace");
    if (ptrace_ptr!=NULL) {
        ptrace_ptr(PT_DENY_ATTACH, 0, 0, 0);
    }
    dlclose(handle);
}
```

* 调用

```objective-c
int main(int argc, char *argv[])
{
    @autoreleasepool {
        #ifndef RELEASE  
            disable_gdb();  
        #endif
        int retVal = UIApplicationMain(argc, argv, nil, nil);
        return retVal;
    }
}
```

