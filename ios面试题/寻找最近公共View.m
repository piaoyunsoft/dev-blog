+ (NSArray *)superViews:(UIView *)view {
	if (view == nil) {
		return @[];
	}
	NSMutableArray *result = [NSMutableArray array];
	while (view != nil) {
		[result addObject:view];
		view = view.superview;
	}
	return [result copy];
}

// 利用set求取
+ (UIView *)commonView:(UIView *)viewA andView:(UIView *)viewB {
	NSArray *arr1 = [self superViews:viewA];
	NSArray *arr2 = [self superViews:viewB];
	NSSet *set = [NSSet setWithArray:arr2];
	for (NSUInter i=0;i<arr1.count;++i) {
		UIView *targetView = arr1[i];
		if ([set containsObject:targetView]) {
			return targetView;
		}
	}
	return nil;
}