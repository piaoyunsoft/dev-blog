<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $nonce = $_GET['nonce'];
        $token = $_GET['weixin'];
        $timestamp = $_GET['timestamp'];
        $signature = $_GET['signature'];
        $echostr = $_GET['echostr'];
        $array = array();
        $array = array($nonce,$timestamp,$token);
        sort($array);
        $str = sha1(implode($array));
        if ($str == $signature) {
        	echo $echostr;
        	exit;
        }
    }

    public function show() {
    	echo 'inspire';
    }
}