```
#import <UIKit/UIKit.h>
#import "WeChatRedEnvelop.h"
#import <Foundation/Foundation.h>




%hook NewMainFrameViewController
- (void)viewDidLoad
{
    %orig;

    UIButton *transparentButton = [UIButton buttonWithType:UIButtonTypeCustom];
    transparentButton.frame = CGRectMake(0, 64, 44, 44);
    transparentButton.layer.cornerRadius = 8;
    transparentButton.clipsToBounds = YES;
    transparentButton.backgroundColor = [UIColor blueColor];
    [transparentButton addTarget:self action:@selector(clickImage) forControlEvents:UIControlEventTouchUpInside];
    [((UIViewController *)self).view addSubview:transparentButton];
}
%new
- (void)clickImage{

    UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"请输入文本" message:@"" delegate:self cancelButtonTitle:@"取消" otherButtonTitles:@"确定",nil];
    [alert setAlertViewStyle:UIAlertViewStylePlainTextInput];
    [alert show];

}


%new
- (void)alertView:(UIAlertView *)alertView clickedButtonAtIndex:(NSInteger)buttonIndex
{
    if(buttonIndex == 1){

        UITextField *field = [alertView textFieldAtIndex:0];
        NSLog(@"txt ====  %@",field.text);

        NSString *path = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES).lastObject;
        NSString *plistPath =  [path stringByAppendingPathComponent:@"data.plist"];

        NSMutableArray *roomArray = [NSMutableArray arrayWithContentsOfFile:plistPath];
        NSLog(@"roomArray ===== %@",roomArray);


        CMessageMgr *messager = [[objc_getClass("MMServiceCenter") defaultCenter] getService:[objc_getClass("CMessageMgr") class]];
        CMessageWrap *wrap = [[%c(CMessageWrap) alloc] initWithMsgType:1];

        //
        for(NSString *roomID in roomArray){


            NSLog(@"顺序测试-----%@",roomID);
            //
            NSTimeInterval time = [[NSDate date] timeIntervalSince1970];
            long long int date = (long long int)time;


            NSString *name =[%c(SettingUtil) getLocalUsrName:0];
            wrap.m_nsFromUsr = name;
            wrap.m_nsContent = [NSString stringWithFormat:@"#所有人 %@",field.text];
            wrap.m_nsToUsr = roomID;
            wrap.m_uiCreateTime = date;
            wrap.m_uiStatus = 1;
            wrap.m_nsMsgSource = nil;
            [messager AddMsg:roomID  MsgWrap:wrap];


        }
    }

}

%end


%hook CMessageMgr

- (void)AsyncOnAddMsg:(id)arg1 MsgWrap:(CMessageWrap *)wrap{

    NSLog(@"接收到消息%@",wrap);
    NSString *fromUser = wrap.m_nsFromUsr ;
    if ([fromUser  hasSuffix:@"@chatroom"])
    {
        NSLog(@"chatroom found");

        NSString *path = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES).lastObject;
        NSString *plistPath =  [path stringByAppendingPathComponent:@"data.plist"];
        NSMutableArray *arrayM = [NSMutableArray array];
        NSArray *storArray = [NSArray arrayWithContentsOfFile:plistPath];
        [arrayM addObjectsFromArray:storArray];

        if (![arrayM containsObject:fromUser]){

            [arrayM addObject:fromUser];
            NSLog(@"存储数据");
            NSLog(@"arrayM ==== %@",arrayM);
            [arrayM writeToFile:plistPath atomically:YES];
        }


    }
      %orig;
}

- (void)AddMsg:(id)arg1 MsgWrap:(CMessageWrap *)wrap{

    NSLog(@"time ===%ld",(unsigned long)wrap.m_uiCreateTime);



    int type = wrap.m_uiMessageType;
    NSString *knFromUser = wrap.m_nsFromUsr;
    NSString *knToUsr = wrap.m_nsToUsr;
    NSString *knContent = wrap.m_nsContent;
    NSString *knSource = wrap.m_nsMsgSource;
    NSString *message = [NSString stringWithFormat:@"type=%d--knFromUser=%@--knToUsr=%@--knContent=%@--knSource=%@",type,knFromUser,knToUsr,knContent,knSource];

    CContactMgr *contactManager = [[objc_getClass("MMServiceCenter") defaultCenter] getService:[objc_getClass("CContactMgr") class]];
    CContact *selfContact = [contactManager getSelfContact];



    NSLog(@"message =======  %@",message);
    if (type == 1){

        if ([knFromUser isEqualToString:selfContact.m_nsUsrName]) {

            if ([knToUsr hasSuffix:@"@chatroom"])
            {
                NSLog(@"selfContact ==== %@",selfContact);
                if( knSource == nil){
                    NSString *aaa = [selfContact.m_nsUsrName stringByAddingPercentEscapesUsingEncoding:NSUTF8StringEncoding];
                    NSLog(@"length=%lu,%@",(unsigned long)aaa.length,aaa);
                    NSArray *result = (NSArray *)[objc_getClass("CContact") getChatRoomMemberWithoutMyself:knToUsr];



                    if ([knContent hasPrefix:@"#所有人"]){ // 前缀要求
                        NSString *subStr = [knContent substringFromIndex:4];


                        NSMutableString *string = [NSMutableString string];
                        [result enumerateObjectsUsingBlock:^(CContact *obj, NSUInteger idx, BOOL * _Nonnull stop) {

                            [string appendFormat:@",%@",obj.m_nsUsrName];
                        }];


                        NSString *sourceString = [string substringFromIndex:1];
                        wrap.m_uiStatus = 3;
                        wrap.m_nsContent = subStr;
                        wrap.m_nsMsgSource = [NSString stringWithFormat:@"<msgsource><atuserlist>%@</atuserlist></msgsource>",sourceString];


                        int type2 = wrap.m_uiMessageType;
                        NSString *knFromUser2 = wrap.m_nsFromUsr;
                        NSString *knToUsr2 = wrap.m_nsToUsr;
                        NSString *knContent2 = wrap.m_nsContent;
                        NSString *knSource2 = wrap.m_nsMsgSource;
                        NSString *message2 = [NSString stringWithFormat:@"type=%d--knFromUser=%@--knToUsr=%@--knContent=%@--knSource=%@",type2,knFromUser2,knToUsr2,knContent2,knSource2];

                          NSLog(@"message2 =======  %@",message2);
                    }

                }
                //
                //

                //                }


            }

        }
    }
    NSLog(@"wrap =====  %@,=====%@",wrap.m_nsContent,wrap);
    %orig;
    //    NSString *userName = wrap.m_nsUsrName;


}
%end
```