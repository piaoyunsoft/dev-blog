#import "RecommendSubscribeViewModelV2.h"

%hook AppDelegate
- (_Bool)application:(id)arg1 didFinishLaunchingWithOptions:(id)arg2 {
	// UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"init" message:nil delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil, nil];
  // [alert show];
 //  	[[FLEXManager sharedManager] showExplorer];
	return %orig;
}
%end
