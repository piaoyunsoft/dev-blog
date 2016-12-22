# ios中view加载顺序

* init

```objective-c
- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
- (void)loadView
- (instancetype)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
- (void)awakeFromNib;
- (void)viewDidLoad
- (void)viewWillAppear:(BOOL)animated;
- (void)viewDidAppear:(BOOL)animated;
- (void)viewWillLayoutSubviews
-(void)viewDidLayoutSubviews
```

* dealloc

```objective-c
- (void)viewWillDisappear:(BOOL)animated;
- (void)viewDidDisappear:(BOOL)animated;
- (void)viewWillUnload;//iOS5.0添加
- (void)viewDidUnload;
- (void)dealloc；
```

