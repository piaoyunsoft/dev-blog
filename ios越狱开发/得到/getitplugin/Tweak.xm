#import "flex/FLEX.h"
#import "RecommendSubscribeViewModelV2.h"

%hook AppDelegate
- (_Bool)application:(id)arg1 didFinishLaunchingWithOptions:(id)arg2 {
	// UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"init" message:nil delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil, nil];
  // [alert show];
	return %orig;
}
%end

%hook RecommendSubscribeViewControllerV2
- (id)viewModel {
  RecommendSubscribeViewModelV2 *model = %orig;
  NSLog(@"aaaaaaaaaaaaa : %@",[model recommendArray]);
  return %orig;
}
%end

%hook SubscribeDetailViewControllerV2
- (long long)subscribeId {
  NSString *idStr = [[NSNumber numberWithLong:%orig] stringValue];
  UIAlertView *alert = [[UIAlertView alloc] initWithTitle:(idStr.length > 0)?idStr:@"" message:nil delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil, nil];
  [alert show];
  return %orig;
}
- (_Bool)enablePayBtn {
  return NO;
}
- (void)gotoSubscribeListVC:(_Bool)arg1 {
  arg1 = YES;
  %orig;
}
%end

%hook AlreadySubscribeViewControllerV2
- (void)callBackAction:(unsigned long long)arg1 info:(id)arg2 {
  NSLog(@"bbbbbb %@",arg2);
  %orig;
}
%end
