
@interface RecommendSubscribeViewModelV2 : NSObject
{
    _Bool _hasMoreData;
    NSMutableArray *_recommendArray;
    long long _currentPage;
}

@property(nonatomic) _Bool hasMoreData; // @synthesize hasMoreData=_hasMoreData;
@property(nonatomic) long long currentPage; // @synthesize currentPage=_currentPage;
@property(retain, nonatomic) NSMutableArray *recommendArray; // @synthesize recommendArray=_recommendArray;
@end
