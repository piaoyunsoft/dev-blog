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
        // 1.first subscribe reponse
    // 	if (strtolower($postObj->MsgType) == 'event') {
    // 		if (strtolower($postObj->Event) == 'subscribe') {
    // 			$toUser = $postObj->FromUserName;
    // 			$fromUser = $postObj->ToUserName;
    // 			$time = time();
    // 			$msgType = 'text';
    // 			$content = 'subscribe: '.$postObj->ToUserName.'<br>wechat user openid: '.$postObj->FromUserName.'<br>feedback msgType: '.$tmpStr;
    // 			$template = "<xml>
				//            <ToUserName><![CDATA[%s]]></ToUserName>
				//            <FromUserName><![CDATA[%s]]></FromUserName>
				//            <CreateTime>%s</CreateTime>
				//            <MsgType><![CDATA[%s]]></MsgType>
				//            <Content><![CDATA[%s]]></Content>
				//            </xml>";
				// $info = sprintf($template,$toUser,$fromUser,$time,$msgType,$content);
				// echo $info;
    // 		}
    // 	}

        // 2.text reponse
        // if (strtolower($postObj->MsgType) == 'text') {
        //     $toUser = $postObj->FromUserName;
        //     $fromUser = $postObj->ToUserName;
        //     $time = time();
        //     $msgType = 'text';
        //     switch (trim($postObj->Content)) {
        //         case 1:
        //             $content = 'input 1';  
        //             break;
        //         case 2:
        //             $content = 'input 2';
        //             break;
        //         case 3:
        //             $content = "<a href='http://www.baidu.com'>baidu</a> ";   
        //             break;
        //         default:
        //             $content = 'input other';    
        //     }
        //     $template = "<xml>
        //                <ToUserName><![CDATA[%s]]></ToUserName>
        //                <FromUserName><![CDATA[%s]]></FromUserName>
        //                <CreateTime>%s</CreateTime>
        //                <MsgType><![CDATA[%s]]></MsgType>
        //                <Content><![CDATA[%s]]></Content>
        //                </xml>";
        //     $info = sprintf($template,$toUser,$fromUser,$time,$msgType,$content);
        //     echo $info;
        // }

        // 3.pic + text
        if (strtolower($postObj->MsgType) == 'text') {
            if (trim($postObj->Content) == 'tuwen') {
                $toUser = $postObj->FromUserName;
                $fromUser = $postObj->ToUserName;
                $arr = array(
                    array(
                        'title'=>'inspire',
                        'description'=>"inspire is very good",
                        'url'=>'http://www.baidu.com',
                        'picUrl'=>'http://t-1.tuzhan.com/7bf5fd085229/c-2/l/2013/08/08/02/4fa5cd36e0184058b4372fd9b6cad45c.jpg'
                    ),
                    array(
                        'title'=>'inspire2',
                        'description'=>"inspire is very good2",
                        'url'=>'http://www.baidu.com',
                        'picUrl'=>'http://t-1.tuzhan.com/7bf5fd085229/c-2/l/2013/08/08/02/4fa5cd36e0184058b4372fd9b6cad45c.jpg'
                    ),
                    array(
                        'title'=>'inspire2',
                        'description'=>"inspire is very good2",
                        'url'=>'http://www.baidu.com',
                        'picUrl'=>'http://t-1.tuzhan.com/7bf5fd085229/c-2/l/2013/08/08/02/4fa5cd36e0184058b4372fd9b6cad45c.jpg'
                    ),
                ); 
                $template = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <ArticleCount>".count($arr)."</ArticleCount>
                            <Articles>";
                foreach ($arr as $key => $value) {
                    $template .= "<item>
                                <Title><![CDATA[".$value['title']."]]></Title> 
                                <Description><![CDATA[".$value['description']."]]></Description>
                                <PicUrl><![CDATA[".$value['picUrl']."]]></PicUrl>
                                <Url><![CDATA[".$value['url']."]]></Url>
                                </item>";
                }
                $template .= "</Articles>
                            </xml>";
                echo sprintf($template,$toUser,$fromUser,time(),'news');
            }
        }
    }
}