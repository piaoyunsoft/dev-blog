iOS目前只能通过代码层面。
类VMP. Upx 的东西会破坏代码签名afaik

# 方案1: 代码混淆
* class-dump到处头文件

```
./class-dump -H FunBike.app -o FunBike heads
./class-dump -H LuoJiFM-IOS.app -o LuoJiFM-IOS heads
```
