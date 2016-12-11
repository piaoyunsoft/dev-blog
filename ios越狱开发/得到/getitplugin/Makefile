THEOS_DEVICE_IP = localhost
THEOS_DEVICE_PORT = 2223

ARCHS = armv7

include /opt/theos/makefiles/common.mk

TWEAK_NAME = getitplugin
getitplugin_FILES = Tweak.xm
getitplugin_FRAMEWORKS = UIKit Foundation

include /opt/theos/makefiles/tweak.mk

after-install::
	install.exec "killall -9 LuoJiFM-IOS"
