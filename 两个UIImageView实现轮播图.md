# 两个UIImageView实现轮播图



## 1.思路

* 通过手势来判断向左还是向右,然后把副Image放左还是放右.

## 2.code

* init

```objective-c
- (instancetype)initWithImageArray:(NSArray <NSString *>*)imageArray
                              fram:(CGRect)frame {
    if (self = [super initWithFrame:frame]) {
        _imageNames = imageArray;
        _currentIndex = 0;
        _currentImage = [[UIImageView alloc] initWithFrame:self.bounds];
        _currentImage.userInteractionEnabled = YES;
        _currentImage.image = [self getImageForIndex:_currentIndex];
        [self insertSubview:_currentImage atIndex:1];
        _nextImage = [[UIImageView alloc] init];
        _nextImage.userInteractionEnabled = YES;
        [self insertSubview:_nextImage atIndex:0];
        _pan = [[UIPanGestureRecognizer alloc] initWithTarget:self action:@selector(panChangeImage:)];
        [self addGestureRecognizer:_pan];
        [self addTime];
    }
    return self;
}
```

* gesture event

```objective-c
- (void)panChangeImage:(UIPanGestureRecognizer *)pan {
    [self.changeImageTime invalidate];
    self.changeImageTime = nil;
    CGPoint panOffSet = [pan translationInView:self];
    float changeX = panOffSet.x;
    CGRect frame = _currentImage.frame;
    [_pan setTranslation:(CGPointZero) inView:self];
    float resulet = frame.origin.x + (changeX < 0 ? - DBL_EPSILON : DBL_EPSILON);
    resulet <= 0 ? [self leftScroll:changeX frame:frame] : [self rightScroll:changeX frame:frame] ;
}
```

* 根据手势放左还是放右

```objective-c
- (void)leftScroll:(float)offX
             frame:(CGRect)frame {
    float tempX = frame.origin.x + offX;
    _currentImage.frame = CGRectMake(tempX, frame.origin.y, frame.size.width, frame.size.height);
    _nextImage.image = [self getImageForIndex:self.nextIndex];
    _nextImage.frame = CGRectOffset(_currentImage.frame, kScreenW, 0);
    if (_pan.state == UIGestureRecognizerStateEnded) {
        [self addTime];
        MoveDirection result = tempX <= - kScreenW / 2 ? [self leftOut:_currentImage rightIn:_nextImage duration:0.3f] : [self leftIn:_currentImage rightOut:_nextImage duration:0.3f];
        if (result == MoveDirectionLeft) {
            _currentIndex = self.nextIndex;
            UIImageView *temp = _nextImage;
            _nextImage = _currentImage;
            _currentImage = temp;
        }
    }
}

- (void)rightScroll:(float)offX
              frame:(CGRect)frame {
    float tempX = frame.origin.x + offX;
    _currentImage.frame = CGRectMake(tempX, frame.origin.y, frame.size.width, frame.size.height);
    _nextImage.image = [self getImageForIndex:self.previousIndex];
    _nextImage.frame = CGRectOffset(_currentImage.frame, -kScreenW, 0);
    if (_pan.state == UIGestureRecognizerStateEnded) {
        [self addTime];
        MoveDirection result = tempX <= kScreenW / 2 ? [self leftOut:_nextImage rightIn:_currentImage duration:0.3f] : [self leftIn:_nextImage rightOut:_currentImage duration:0.3f];
        if (result == MoveDirectionRight) {
            _currentIndex = self.previousIndex;
            UIImageView *temp = _nextImage;
            _nextImage = _currentImage;
            _currentImage = temp;
        }
    }
}
```

* 停止后的动画

```objective-c
- (MoveDirection)leftOut:(UIImageView *)leftView
                 rightIn:(UIImageView *)rightView
                duration:(NSTimeInterval)duration {
    [UIView animateWithDuration:duration animations:^{
        leftView.frame = CGRectOffset(self.bounds, - kScreenW, 0);
        rightView.frame = self.bounds;
    }];
    return MoveDirectionLeft;
}

- (MoveDirection)leftIn:(UIImageView *)leftView
               rightOut:(UIImageView *)rightView
               duration:(NSTimeInterval)duration {
    [UIView animateWithDuration:duration animations:^{
        rightView.frame = CGRectOffset(self.bounds, kScreenW, 0);
        leftView.frame = self.bounds;
    }];
    return MoveDirectionRight;
}
```



* ps:这篇只是作为记录,看到别人的实现,觉得别人写的好凌乱,代码也凌乱,所以自己记一下.



