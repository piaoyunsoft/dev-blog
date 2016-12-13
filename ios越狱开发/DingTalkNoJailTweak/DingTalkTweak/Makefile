THEOS_DEVICE_IP = localhost
THEOS_DEVICE_PORT = 2223

ARCHS = armv7

include /opt/theos/makefiles/common.mk

TWEAK_NAME = DingTalkTweak
DingTalkTweak_FILES = Tweak.xm

include /opt/theos/makefiles/tweak.mk

after-install::
	install.exec "killall -9 DingTalk"
