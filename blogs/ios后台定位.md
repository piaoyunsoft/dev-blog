# ios后台定位



## 1.open background mode

![](http://upload-images.jianshu.io/upload_images/327661-3c3bc2466c1362ea.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)



## 2.info.plist

* add

  ```xml
  <key>NSAppTransportSecurity</key>
   <dict>
    <key>NSAllowsArbitraryLoads</key>
    <true/>
   </dict>
   
   <key>NSLocationWhenInUseUsageDescription</key>
   <string>YES</string>
   <key>NSLocationAlwaysUsageDescription</key>
   <string>YES</string>
  ```

  ​

## 3.objc code

```objective-c
#import <CoreLocation/CoreLocation.h>

@interface ViewController () <CLLocationManagerDelegate>
@property (nonatomic, strong) CLLocationManager *locationManager;
@end
@implementation ViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    self.locationManager = [[CLLocationManager alloc] init];
    self.locationManager.delegate = self;
    [self.locationManager setDesiredAccuracy:kCLLocationAccuracyBest];
    if ([[UIDevice currentDevice].systemVersion floatValue] > 8) {
        [self.locationManager requestAlwaysAuthorization];
    }
    if ([[UIDevice currentDevice].systemVersion floatValue] > 9) {
        [self.locationManager setAllowsBackgroundLocationUpdates:YES];
    }
    [self.locationManager startUpdatingLocation];
}

- (void)locationManager:(CLLocationManager *)manager
     didUpdateLocations:(NSArray<CLLocation *> *)locations {
    CLLocation *loc = [locations objectAtIndex:0];
    NSLog(@"lat and lon:  %f  %f ",loc.coordinate.latitude,loc.coordinate.longitude);
    NSURLSession *session = [NSURLSession sharedSession];
    NSURLSessionDataTask *task = [session dataTaskWithRequest:[NSURLRequest requestWithURL:[NSURL URLWithString:@""]] completionHandler:^(NSData * _Nullable data, NSURLResponse * _Nullable response, NSError * _Nullable error) {
        NSString *result = [[NSString alloc] initWithData:data encoding:NSUTF8StringEncoding];
        NSLog(@"result %@",result);
    }];
    [task resume];
}
```