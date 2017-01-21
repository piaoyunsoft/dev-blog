
@interface WCPayInfoItem: NSObject
@property(retain, nonatomic) NSString *m_c2cNativeUrl;
@end

@interface CMessageWrap : NSObject // 微信消息
@property (retain, nonatomic) WCPayInfoItem *m_oWCPayInfoItem;
@property (assign, nonatomic) NSUInteger m_uiMesLocalID;
@property (retain, nonatomic) NSString* m_nsFromUsr; // 发信人，可能是群或个人
@property (retain, nonatomic) NSString* m_nsToUsr; // 收信人
@property (assign, nonatomic) NSUInteger m_uiStatus;
@property (retain, nonatomic) NSString* m_nsContent; // 消息内容
@property (retain, nonatomic) NSString* m_nsRealChatUsr; // 群消息的发信人，具体是群里的哪个人
@property (nonatomic) NSUInteger m_uiMessageType;
@property (nonatomic) long long m_n64MesSvrID;
@property (nonatomic) NSUInteger m_uiCreateTime;
@property (retain, nonatomic) NSString *m_nsDesc;
@property (retain, nonatomic) NSString *m_nsAppExtInfo;
@property (nonatomic) NSUInteger m_uiAppDataSize;
@property (nonatomic) NSUInteger m_uiAppMsgInnerType;
@property (retain, nonatomic) NSString *m_nsShareOpenUrl;
@property (retain, nonatomic) NSString *m_nsShareOriginUrl;
@property (retain, nonatomic) NSString *m_nsJsAppId;
@property (retain, nonatomic) NSString *m_nsPrePublishId;
@property (retain, nonatomic) NSString *m_nsAppID;
@property (retain, nonatomic) NSString *m_nsAppName;
@property (retain, nonatomic) NSString *m_nsThumbUrl;
@property (retain, nonatomic) NSString *m_nsAppMediaUrl;
@property (retain, nonatomic) NSData *m_dtThumbnail;
@property (retain, nonatomic) NSString *m_nsTitle;
@property (retain, nonatomic) NSString *m_nsMsgSource;
- (instancetype)initWithMsgType:(int)msgType;
+ (UIImage *)getMsgImg:(CMessageWrap *)arg1;
+ (NSData *)getMsgImgData:(CMessageWrap *)arg1;
+ (NSString *)getPathOfMsgImg:(CMessageWrap *)arg1;
- (UIImage *)GetImg;
- (BOOL)IsImgMsg;
- (BOOL)IsAtMe;
+ (void)GetPathOfAppThumb:(NSString *)senderID LocalID:(NSUInteger)mesLocalID retStrPath:(NSString **)pathBuffer;
@end

@interface WCRedEnvelopesControlData : NSObject
@property(retain, nonatomic) CMessageWrap *m_oSelectedMessageWrap;
@end

@interface MMServiceCenter : NSObject
+ (instancetype)defaultCenter;
- (id)getService:(Class)service;
@end

@interface WCRedEnvelopesLogicMgr: NSObject
- (void)OpenRedEnvelopesRequest:(id)params;
@end

@interface MMMsgLogicManager: NSObject
- (id)GetCurrentLogicController;
@end

@interface CContact: NSObject
@property(retain, nonatomic) NSString *m_nsUsrName;
@property(retain, nonatomic) NSString *m_nsHeadImgUrl;
@property(retain, nonatomic) NSString *m_nsNickName;


- (id)getContactDisplayName;
@end

@interface CContactMgr : NSObject
- (id)getSelfContact;
@end

@interface NSMutableDictionary (SafeInsert)
- (void)safeSetObject:(id)arg1 forKey:(id)arg2;
@end

@interface MMTableViewInfo

- (id)getTableView;
- (void)addSection:(id)arg1;
- (void)insertSection:(id)arg1 At:(unsigned int)arg2;

@end

@interface MMTableViewSectionInfo

+ (id)sectionInfoDefaut;
- (void)addCell:(id)arg1;

@end

@interface MMTableViewCellInfo

+ (id)normalCellForSel:(SEL)arg1 target:(id)arg2 title:(id)arg3 accessoryType:(long long)arg4;
+ (id)switchCellForSel:(SEL)arg1 target:(id)arg2 title:(id)arg3 on:(_Bool)arg4;
+ (id)normalCellForSel:(SEL)arg1 target:(id)arg2 title:(id)arg3 rightValue:(id)arg4 accessoryType:(long long)arg5;
+ (id)normalCellForTitle:(id)arg1 rightValue:(id)arg2;

@end


@interface MMTableView: UITableView

@end

@interface NewSettingViewController: UINavigationController

- (void)reloadTableData;

@end

@interface UINavigationController (LogicController)

- (void)PushViewController:(id)arg1 animated:(_Bool)arg2;

@end

@interface ScanQRCodeLogicController: NSObject

@property(nonatomic) unsigned int fromScene;
- (id)initWithViewController:(id)arg1 CodeType:(int)arg2;
- (void)tryScanOnePicture:(id)arg1;
- (void)doScanQRCode:(id)arg1;
- (void)showScanResult;

@end


#pragma mark timeline

@interface FIFOFileQueue : NSObject
-(BOOL)push:(id)push;
-(id)getAll;
@end

@interface WCCommentUploadMgr : NSObject
-(void)tryStartNextTask;
-(void)pushBackTopTask;
-(void)popTopTask;
-(void)addComment:(id)comment;
-(void)addCommentToWCObjectCache:(id)wcobjectCache;
@end

@interface WCCommentItem : NSObject
@property(retain, nonatomic) NSString* clientID;
@property(assign, nonatomic) unsigned inQueueTime;
@property(assign, nonatomic) unsigned createTime;
@property(assign, nonatomic) int source;
@property(assign, nonatomic) int type;
@property(retain, nonatomic) NSString* itemID;
@property(retain, nonatomic) NSString* toUserName;
@property(retain, nonatomic) NSString *content;
@end

@interface WCAdvertiseInfo : NSObject
@end
//@class MMUIButton;
@interface WCDataItem : NSObject
@property(assign, nonatomic) BOOL likeFlag;
@property(retain, nonatomic) NSString* username;
@property(retain, nonatomic) NSString* tid;
@property(retain, nonatomic) NSString *contentDesc;
@property(retain, nonatomic) WCAdvertiseInfo* advertiseInfo;
-(id)itemID;
@end

@interface WCLikeButton : NSObject
@property(retain, nonatomic) WCDataItem* m_item;
//点赞取消触发方法
-(void)LikeBtnReduceEnd;
//用于初始化
-(id)initWithDataItem:(id)dataItem;
@end


@interface SettingUtil : NSObject
+(id)getCurUsrName;
@end

@interface WCFacade : NSObject
-(WCDataItem *)getTimelineDataItemOfIndex:(int)index;
//点赞内部调用,这里不用到
-(void)likeObject:(id)object ofUser:(id)user source:(int)source;
////点赞内部调用,这里不用到
-(BOOL)unLikeObject:(id)object ofUser:(id)user;
@end

@interface WCTimeLineViewController : NSObject
-(int)calcDataItemIndex:(int)index;
-(void)ccStartAutoLike;
-(void)ccUpdateDataItemsWithNumber:(int)number;
-(void)ccStartQueueToLike;
-(void)reloadTableView;
@end
