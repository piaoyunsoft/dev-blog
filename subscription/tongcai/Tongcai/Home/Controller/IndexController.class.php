<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $timestamp = $_GET['timestamp'];
        $nonce = $_GET['nonce'];
        $token = 'tongcai';
        $signature = $_GET['signature'];
        $array = array($timestamp,$nonce,$token);
        sort($array);
        $tmpstr = implode('', $array);
        $tmpstr = sha1($tmpstr);
        if ($tmpstr == $signature && $echostr) {
            echo $_GET['echostr'];
            exit;
        } else {
            $this->responseMsg();
        }
    }

    public function responseMsg() {
    	$postArr = $GLOBALS['HTTP_RAW_POST_DATA'];
        $tmpStr = $postArr;
    	$postObj = simplexml_load_string($postArr);
    	if (strtolower($postObj->MsgType) == 'event') {
    		if (strtolower($postObj->Event) == 'subscribe') {
                $content = "感谢关注通彩app团队,这里会不定期发布我们团队的技术文章!";
    			$Index = D('Index');
                $Index->responseSubscribe($postObj,$content);
    		}
            // reScan
            if (strtolower($postObj->Event) == 'scan') {
                if ($postObj->EventKey == 3000) {
                    $res = $this->getUserDetail();
                    // $this->savedUserToDB($res['openid']);
                    $content = '签到成功';
                    $Index = D('Index');
                    $Index->responseText($postObj,$content);
                }
            }
    	}

        if (strtolower($postObj->MsgType) == 'text' && 
          trim($postObj->Content) == 'ios') {
          $arr = array(
              array(  
                  'title'=>'APP缓存数据线程安全问题探讨',
                  'description'=>"网上找的ios技术文章",
                  'url'=>'https://wereadteam.github.io/2016/11/22/DataCache/',
                  'picUrl'=>'https://wereadteam.github.io/img/cache2.png',
              ),
          );
          $Index = D('Index');
          $Index->responseNews($postObj,$arr);
        }

        // if (strtolower($postObj->MsgType) == 'text' && 
        //     trim($postObj->Content) == '简介') {
        //     $arr = array(
        //         array(  
        //             'title'=>'inspire',
        //             'description'=>"inspire is very good",
        //             'url'=>'http://www.baidu.com',
        //             'picUrl'=>'https://ss0.bdstatic.com/5aV1bjqh_Q23odCf/static/superman/img/logo/bd_logo1_31bdc765.png'
        //         )
        //     );
        //     $Index = D('Index');
        //     $Index->responseNews($postObj,$arr);
        // } else {
        //     if (strtolower($postObj->MsgType) == 'text') {
        //         $ch = curl_init();
        //         $url = 'http://apis.baidu.com/apistore/weatherservice/cityname?cityname='.urlencode($postObj->Content).'';
        //         $header = array(
        //             'apikey: a79124c4594c2e5a0799a39ea8f64c87',
        //         );
        //         curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
        //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //         curl_setopt($ch , CURLOPT_URL , $url);
        //         $res = curl_exec($ch);
        //         $arr = json_decode($res,true);
        //         $content = $arr['retData']['city']."\n".$arr['retData']['date']."\n".$arr['retData']['weather']."\n".$arr['retData']['temp'];

        //         $Index = D('Index');
        //         $Index->responseText($postObj,$content);
        //     }
        // }
    }

    function http_curl($url,$type='get',$res='json',$arr='') {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($type == 'post') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $arr);
        }
        $output = curl_exec($ch);
        curl_close($ch);
        if ($res == 'json') {
            if (curl_errno($ch)) {
                return curl_errno($ch);
            } else {
                return json_decode($output,true);
            }
        }
    }

    function getWxServerIp() {
        $accessToken = "Olv5_vD-j8z5G_UyFNEFjT-rn2hpzDiGAzYQw0EGUukqioqmFMCR7-8kIXvvBhjRbGOI9UAXjLG4mt-3BsBifIzQh_-qySsmqMlzhqi-5d1f-fgL2jo097_98Qv6bNyNMCAiAAASIG";
        $url = "https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=".$accessToken."";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($ch);
        curl_close($ch);

        if (curl_errno($ch)) {
            var_dump(curl_errno($ch));
        }
        echo json_encode(json_decode($res));
    }

    public function getWxAccessToken() {
        // if ($_SESSION['access_token'] && $_SESSION['expire_time'] > time()) {
        //     return $_SESSION['access_token'];
        // } else {
            $appid = 'wx2708c404b0c52465';
            $appsecret = '4b9d327c6b2027ba8782faf6f05e80a8';
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret."";
            $res = $this->http_curl($url,'get','json');
            $_SESSION['access_token'] = $res['access_token'];
            $_SESSION['expire_time'] = time() + 7000;
            return $res['access_token'];
        // }
    }

    public function definedItem() {
        $access_token = $this->getWxAccessToken(); 
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$access_token.'';
        echo '<br />';
        $postArr = array(
            'button'=>array(
                array(
                    'name'=>urlencode('主页1'),
                    'type'=>'click',
                    'key'=>'item1'
                ),
                array(
                    'name'=>urlencode('娱乐1'),
                    'sub_button'=>array(
                        array(
                            'name'=>'song',
                            'type'=>'click',
                            'key'=>'songs'
                        ),
                        array(
                            'name'=>'film',
                            'type'=>'view',
                            'url'=>'http://www.baidu.com'
                        )
                    ),
                ),
                array(
                    'name'=>urlencode('我的'),
                    'type'=>'view',
                    'url'=>"http://wuxueying.xyz/tongcai/tongcai.php/Home/Index/getUserDetail",
                ),
            ),
        );
        echo  $postJson = urldecode(json_encode($postArr));
        $res = $this->http_curl($url,'post','json',$postJson);
        var_dump($res);
    }

    function sendMsgAll() {
        header("Content-Type:text/html; charset=utf-8");
        echo $access_token = $this->getWxAccessToken(); 
        echo '<hr /><hr />';
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/preview?access_token='.$access_token.'';
        // 1.text
        $array = array(
            'touser'=>'oqiVYwlFONhwtmY6QiLA1wFu6Dmk',
            'text'=>array('content'=>'no update today'),
            'msgtype'=>'text'
        );
        $postJson = json_encode($array);
        var_dump($postJson);
        echo '<hr /><hr />';
        $res = $this->http_curl($url,'post','json',$postJson);
        var_dump($res);
    }

    function uploadWxImage() {
        $access_token = $this->getWxAccessToken(); 
        $url = 'https://api.weixin.qq.com/cgi-bin/material/add_material?access_token='.$access_token.'';
        $file = dirname(__FILE__).'/test.jpg';
        $postArr = array('media'=>"@".$file);
        $res = $this->http_curl($url,'post','json',json_encode($postArr));
        var_dump($res);
    }

    function sendTemplateMsg() {
        $access_token = $this->getWxAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$access_token.'';
        $array = array(
            'touser'=>'oqiVYwlFONhwtmY6QiLA1wFu6Dmk',
            'template_id'=>'ccdMYnaGlHD6QYUgLNmkdl_apDVymLC229EwwCj4_SA',
            'url'=>'http://www.qq.com',
            'data'=>array(
                'count'=>array('value'=>12,'color'=>'#173177'),
                'all'=>array('value'=>100,'color'=>'#173177'),
                'time'=>array('value'=>date('Y-m-d H:i:s'),'color'=>'#173177'),
            ),
        );
        $postJson = json_encode($array);
        $res = $this->http_curl($url,'post','json',$postJson);
        var_dump($res);
    }

    function getBaseInfo() {
        $appid = 'wxb3ec6f894118fc2b';
        $redirect_uri = urlencode("http://wuxueying.xyz/inspire/inspire.php/Home/Index/getUserOpenId");
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.$redirect_uri.'&response_type=code&scope=snsapi_base&state=123#wechat_redirect';
        header('location:'.$url);
    }

    function getUserOpenId() {
        $appid = 'wxb3ec6f894118fc2b';
        $appsecret = '6eafac39ad20bbc595d957656c349a9e';
        $code = $_GET['code'];
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code';
        $res = $this->http_curl($url,'get');
        var_dump($res);
    }

    function getUserDetail() {
        $appid = 'wx2708c404b0c52465';
        $redirect_uri = urlencode("http://wuxueying.xyz/inspire/inspire.php/Home/Index/getUserInfo");
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.$redirect_uri.'&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect';
        header('location:'.$url);
    }

    function getUserInfo() {
        header("Content-Type:text/html; charset=utf-8");
        $appid = 'wx2708c404b0c52465';
        $appsecret = '4b9d327c6b2027ba8782faf6f05e80a8';
        $code = $_GET['code'];
        if ($_SESSION[$code]) {
            $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code';
            $res = $this->http_curl($url,'get');
            $access_token = $res['access_token'];
            $openid = $res['openid'];
            $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
            $res = $this->http_curl($url);
            var_dump($res);
        } else {
            $_SESSION[$code] = $code;
        }
    }

    function getJsApiTicket() {
        if ($_SESSION['jsapi_ticket'] && $_SESSION['jsapi_ticket_expire_time'] > time()) {
            $jsapi_ticket = $_SESSION['jsapi_ticket'];
        } else {
            $access_token = $this->getWxAccessToken();
            $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$access_token.'&type=jsapi';
            $res = $this->http_curl($url);
            $jsapi_ticket = $res['ticket'];
            $_SESSION['jsapi_ticket'] = $jsapi_ticket;
            $_SESSION['jsapi_ticket_expire_time'] = time() + 7000;
        }
        return $jsapi_ticket;
    } 

    function getRandCode($num=16) {
        $array = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','0','1','2','3','4','5','6','7','8','9');
        $tmpstr = '';
        $max = count($array);
        for ($i=1;$i<=$num;$i++) {
            $key = rand(0,$max-1);
            $tmpstr .= $array[$key];
        }
        return $tmpstr;
    }

    function shareWx() {
        $jsapi_ticket = $this->getJsApiTicket();
        $timestamp = time();
        $noncestr = $this->getRandCode();
        $protocol = (!empty($_SERVER[HTTPS]) && $_SERVER[HTTPS] !== off || $_SERVER[SERVER_PORT] == 443) ? "https://" : "http://";
        $url = $protocol.$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI];
        $signature = 'jsapi_ticket='.$jsapi_ticket.'&noncestr='.$noncestr.'&timestamp='.$timestamp.'&url='.$url;
        $signature = sha1($signature);
        $this->assign('name','inspire');
        $this->assign('timestamp',$timestamp);
        $this->assign('noncestr',$noncestr);
        $this->assign('signature',$signature);
        $this->display('share');
    }

    function getTimeQrCode() {
        // 1.get ticket
        $access_token = $this->getWxAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$access_token;
        $postArr = array(
            'expire_seconds'=> 604800,
            'action_name'=>"QR_SCENE",
            'action_info'=>array(
                'scene'=>array('scene_id'=>2000),
            ),
        );
        $postJson = json_encode($postArr);
        $res = $this->http_curl($url,'post','json',$postJson);
        $ticket = $res['ticket'];
        // 2.get qrcode image from ticket
        $url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($ticket);
        echo "time qrcode";
        echo "<img src='".$url."' />";
    }

    function getForeverQrCode() {
        $access_token = $this->getWxAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$access_token;
        $postArr = array(
            'action_name'=>"QR_LIMIT_SCENE",
            'action_info'=>array(
                'scene'=>array('scene_id'=>3000),
            ),
        );
        $postJson = json_encode($postArr);
        $res = $this->http_curl($url,'post','json',$postJson);
        $ticket = $res['ticket'];
        $url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($ticket);
        echo "forever qrcode";
        echo "<img src='".$url."' />";
    }


}