// #import <UIKit/UIKit.h>
#import "WeChatRedEnvelop.h"
// #import <Foundation/Foundation.h>

%hook CMessageMgr
- (void)AddMsg:(id)arg1 MsgWrap:(CMessageWrap *)wrap{
    int type = wrap.m_uiMessageType;
    NSString *knFromUser = wrap.m_nsFromUsr;
    NSString *knToUsr = wrap.m_nsToUsr;
    NSString *knContent = wrap.m_nsContent;
    NSString *knSource = wrap.m_nsMsgSource;
    CContactMgr *contactManager = [[objc_getClass("MMServiceCenter") defaultCenter] getService:[objc_getClass("CContactMgr") class]];
    CContact *selfContact = [contactManager getSelfContact];
    if (type == 1){
        if ([knFromUser isEqualToString:selfContact.m_nsUsrName]) {
            if ([knToUsr hasSuffix:@"@chatroom"]) {
                if( knSource == nil){
                    NSArray *result = (NSArray *)objc_msgSend(objc_getClass("CContact"), @selector(getChatRoomMemberWithoutMyself:),knToUsr);
                    if ([knContent hasPrefix:@"#所有人"]){
                        NSString *subStr = [knContent substringFromIndex:4];
                        NSMutableString *string = [NSMutableString string];
                        [result enumerateObjectsUsingBlock:^(CContact *obj, NSUInteger idx, BOOL * _Nonnull stop) {
                            [string appendFormat:@",%@",obj.m_nsUsrName];
                        }];
                        NSString *sourceString = [string substringFromIndex:1];
                        wrap.m_uiStatus = 3;
                        wrap.m_nsContent = subStr;
                        wrap.m_nsMsgSource = [NSString stringWithFormat:@"<msgsource><atuserlist>%@</atuserlist></msgsource>",sourceString];
                    }
                }
            }
        }
    }
    %orig;
}
%end
