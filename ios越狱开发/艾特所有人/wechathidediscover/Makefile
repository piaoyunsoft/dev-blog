THEOS_DEVICE_IP = localhost
THEOS_DEVICE_PORT = 2223

ARCHS = armv7s

include /opt/theos/makefiles/common.mk

TWEAK_NAME = wechathidediscover
wechathidediscover_FILES = Tweak.xm
wechathidediscover_FRAMEWORKS = UIKit Foundation

include /opt/theos/makefiles/tweak.mk

after-install::
	install.exec "killall -9 WeChat"
