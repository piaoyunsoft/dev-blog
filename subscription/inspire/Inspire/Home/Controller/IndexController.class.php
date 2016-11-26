<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $timestamp = $_GET['timestamp'];
        $nonce = $_GET['nonce'];
        $token = 'weixin';
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
                $arr = array(
                    array(
                        'title'=>'welcome',
                        'description'=>"inspire is very good",
                        'url'=>'http://www.baidu.com',
                        'picUrl'=>'https://ss0.bdstatic.com/5aV1bjqh_Q23odCf/static/superman/img/logo/bd_logo1_31bdc765.png'
                    ),
                );
    			$Index = D('Index');
                $Index->responseSubscribe($postObj,$arr);
    		}
    	}
        if (strtolower($postObj->MsgType) == 'text' && 
            trim($postObj->Content) == 'tuwen') {
            $arr = array(
                array(  
                    'title'=>'inspire',
                    'description'=>"inspire is very good",
                    'url'=>'http://www.baidu.com',
                    'picUrl'=>'https://ss0.bdstatic.com/5aV1bjqh_Q23odCf/static/superman/img/logo/bd_logo1_31bdc765.png'
                ),
                array(
                    'title'=>'inspire2',
                    'description'=>"inspire is very good2",
                    'url'=>'http://www.baidu.com',
                    'picUrl'=>'https://ss0.bdstatic.com/5aV1bjqh_Q23odCf/static/superman/img/logo/bd_logo1_31bdc765.png'
                ),
                array(
                     'title'=>'inspire2',
                    'description'=>"inspire is very good2",
                    'url'=>'http://www.baidu.com',
                    'picUrl'=>'https://ss0.bdstatic.com/5aV1bjqh_Q23odCf/static/superman/img/logo/bd_logo1_31bdc765.png'
                ),
            );
            $Index = D('Index');
            $Index->responseNews($postObj,$arr);
        } else {
            if (strtolower($postObj->MsgType) == 'text') {
                $ch = curl_init();
                $url = 'http://apis.baidu.com/apistore/weatherservice/cityname?cityname='.urlencode($postObj->Content).'';
                $header = array(
                    'apikey: a79124c4594c2e5a0799a39ea8f64c87',
                );
                curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch , CURLOPT_URL , $url);
                $res = curl_exec($ch);
                $arr = json_decode($res,true);
                $content = $arr['retData']['city']."\n".$arr['retData']['date']."\n".$arr['retData']['weather']."\n".$arr['retData']['temp'];

                $Index = D('Index');
                $Index->responseText($postObj,$content);
            }
        }
    }

    function http_curl() {
        $ch = curl_init();
        $url = 'http://www.baidu.com';
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        var_dump($output);
    }

    function getWxAccessToken() {
        $appid = 'wxd7ee9f7370773a48';
        $appsecret = '0f0036787e1621003354e79b81cb2f2e';
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret."";
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
}