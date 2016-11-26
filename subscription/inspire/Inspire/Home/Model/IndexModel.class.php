<?php
namespace Home\Model;
use Think\Model;
class IndexModel {
	public function responseNews($postObj,$arr) {
		$toUser = $postObj->FromUserName;
        $fromUser = $postObj->ToUserName; 
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

	public function responseText($postObj,$content) {
		$toUser = $postObj->FromUserName;
        $fromUser = $postObj->ToUserName;
        $time = time();
        $msgType = 'text';
		$template = "<xml>
                   <ToUserName><![CDATA[%s]]></ToUserName>
                   <FromUserName><![CDATA[%s]]></FromUserName>
                   <CreateTime>%s</CreateTime>
                   <MsgType><![CDATA[%s]]></MsgType>
                   <Content><![CDATA[%s]]></Content>
                   </xml>";
        echo sprintf($template,$toUser,$fromUser,$time,$msgType,$content);
	}

	public function responseSubscribe($postObj,$arr) {
        $this->responseNews($postObj,$arr);
	}
}
?>