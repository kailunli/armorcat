<?php
namespace app\wechat\controller;

use think\Controller;
/**
简单解释下开发者ID和服务器配置各参数的作用：
AppID是应用ID，也就是微信开发者编号的意思，在微信中主要用于创建微信菜单等。
AppSecret是应用密匙，与AppID是相配合的，意思可理解为这是私密的应用编号。
URL服务器地址是微信服务器像开发者服务器推送消息和事件的地址，可理解为你家的住址。这里没什么特别要求，无论你使用什么语言开发，只要能通过HTTP服务的80端口返回符合微信要求的XML信息即可。
Token令牌可理解为用来验证安全接头暗号，让微信服务器知道对方就是我要找的人。
EncodingAESKey可理解为暗语加密交流，以免你与用户之间的交互信息被第三方获取到后泄露敏感数据。

处理流程：
微信服务器就相当于一个转发服务器，终端（手机、Pad等）发起请求至微信服务器，然后微信服务器将请求转发给自定义服务（也就是开发者服务器，url对应的服务器）。
服务处理完毕，然后回发给微信服务器，微信服务器再将具体响应回复到终端。
通信协议为：HTTP
数据格式为：XML
*/

class BaseController extends Controller
{
	const APP_ID = 'wx20618b3ce8f39b2d';
	// const APP_ID = 'wxe18415d1d332ae00'; // 测试
	const APP_SECRET = 'b167f41c47162885d3219d2dcf544dc1';
	// const APP_SECRET = '0eb43a3fbdf26f4c9f9e4efac0587cbc'; // 测试
	const TOKEN = '9b005a2ebad10a2ba26ddb91b0c738a6';
	// const TOKEN = '9b005a2ebad10a2ba26ddb91b0c738a6'; // 测试
	const ENCODING_AES_KEY = 'm5PcJl58cQx7Rzp8euZ57nNpRu4bRoDiSb1yHiDJnBc';
	
	// 是否是安全模式
	const SAFE_MODEL = true; // 兼容模式下可设置；安全模式下必须为true;明文模式下必须为false
	
	// 处理微信HTTP_RAW_POST_DATA后的xml对象
	protected $wechatXml;
	// 最终返回给微信服务器的
	protected $response;
	
	// 微信服务器传送的GET参数
	protected $signature;
	protected $timestamp;
	protected $nonce;
	protected $echostr = null; // 第一次验证,有GET参数echostr
	protected $msgSignature = null; // 安全模式下，微信服务器推送给第三方的用于消息解密的签名
	
	public function __construct() 
	{
		$this->signature = $_GET["signature"];
		$this->timestamp = $_GET["timestamp"];
		$this->nonce     = $_GET["nonce"];
		$this->echostr   = isset($_GET['echostr']) ? $_GET['echostr'] : null; // 第一次验证,有GET参数echostr
		$this->msgSignature = isset($_GET['msg_signature']) ? $_GET['msg_signature'] : null; // 微信服务器推送给第三方的用于消息解密的签名
	}
	
	// 验证TOKEN
	protected function checkSignature()
	{
		$tmpArr = array(self::TOKEN, $this->timestamp, $this->nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $this->signature === $tmpStr ){
			return true;
		}else{
			return false;
		}
	}
	
	// 将微信数据解析成xml对象
	protected function setXmlObject() 
	{
		$wechatPostData   = intval(phpversion()) < 7 ? $GLOBALS['HTTP_RAW_POST_DATA'] : file_get_contents("php://input");	
	
		if (self::SAFE_MODEL == true) {
			$wechatXml = simplexml_load_string($wechatPostData);
			$wechatPostData = $this->decrypt($wechatXml->Encrypt);
		} 
		
		$this->wechatXml = simplexml_load_string($wechatPostData);
	}
	
	// 安全模式下解密微信服务器推送的消息
	protected function decrypt($encrypt)
	{
		try {
			$format = "<xml><ToUserName><![CDATA[toUser]]></ToUserName><Encrypt><![CDATA[%s]]></Encrypt></xml>";
			$from_xml = sprintf($format, $encrypt);
			
			$pc = new \wx_public_crypt\WXBizMsgCrypt(self::TOKEN, self::ENCODING_AES_KEY, self::APP_ID);  
			$errCode = $pc->decryptMsg($this->msgSignature, $this->timestamp, $this->nonce, $from_xml, $decrypt); 
			
			if ($errCode == 0) {
				return $decrypt;
			} else {
				print($errCode . "\n");
			}
		} catch (\Exception $e) {
			print ($e->getMessage());
		}
	}
	
	// 加密消息
	protected function encrypt($decrypt) 
	{
		try {
			$pc = new \wx_public_crypt\WXBizMsgCrypt(self::TOKEN, self::ENCODING_AES_KEY, self::APP_ID);  				
			$encrypt = '';
			$errCode = $pc->encryptMsg($decrypt, $this->timestamp, $this->nonce, $encrypt);
			if ($errCode == 0) {
				// 加密消息
				return $encrypt;
			} else {
				print($errCode . "\n");
			}
		} catch(\Exception $e){
			print ($e->getMessage());
		}
	}
	
	// 获取access_token
	protected function getAccessToken()
	{
		$uri = 'https://api.weixin.qq.com/cgi-bin/token';
		// ?grant_type=client_credential&appid=APPID&secret=APPSECRET
		$query_params = ['grant_type'=>'client_credential', 'appid'=>self::APP_ID, 'secret'=>self::APP_SECRET]; 
		$query_str    = http_build_query($query_params);
		
		$url = $uri . '?' . $query_str;		
		$accessToken = curl_get($url); 
		
		/*
		// 错误码
		$err = [
			'-1' =>	'系统繁忙，此时请开发者稍候再试',
			'0'  =>	'	请求成功',
			'40001' =>	'	AppSecret错误或者AppSecret不属于这个公众号，请开发者确认AppSecret的正确性',
			'40002' =>	'	请确保grant_type字段值为client_credential',
			'40164' =>	'	调用接口的IP地址不在白名单中，请在接口IP白名单中进行设置。（小程序及小游戏调用不要求IP地址在白名单内。）'
		];
		*/
		if ( !isset($err->errcode) ) {
			$accessToken = json_decode($accessToken, true);
			$accessToken['errcode'] = 0;
			$accessToken = json_encode($accessToken);
		}
		return $accessToken;
	}
	
	// 获取微信服务器IP地址
	protected function getWxServerIp($accessToken)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=' . $accessToken;
		$wxServerIp = curl_get($url);
		if ( !isset($err->errcode) ) {
			$wxServerIp = json_decode($wxServerIp, true);
			$wxServerIp['errcode'] = 0;
			$wxServerIp = json_encode($wxServerIp);
		}
		
		return $wxServerIp;
	}
	
	/****************以下是回复的各类型消息******************/
	private function handleLastMsg($msg)
	{
		try{
			if (self::SAFE_MODEL == true) {
				return $this->encrypt($msg);
			} else {
				return $msg;
			}
		}catch(\Exception $e){
			print $e->getMessage();
		}
	}
	// 1.回复文本消息
	protected function generateText($toUser, $fromUser, $content) 
	{
		// 回复文本消息模板
		$textTpl = "<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[%s]]></Content></xml>";
		$msg = sprintf($textTpl, $toUser, $fromUser, time(), $content);
		return $this->handleLastMsg($msg);
	}
	
	// 2.回复图片消息
	protected function generatePic($toUser, $fromUser, $mediaId)
	{
		// 回复图片消息模板
	    $picTpl = "<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[image]]></MsgType><Image><MediaId><![CDATA[%s]]></MediaId></Image></xml>";
		$msg = sprintf($picTpl, $toUser, $fromUser, time(), $mediaId);
		return $this->handleLastMsg($msg);
	}
	
	// 3.回复语音消息
	protected function generateVoice($toUser, $fromUser, $mediaId)
	{
		// 回复语音消息模板
		$voiceTpl = "<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[voice]]></MsgType><Voice><MediaId><![CDATA[%s]]></MediaId></Voice></xml>";
		$msg = sprintf($picTpl, $toUser, $fromUser, time(), $mediaId);
		return $this->handleLastMsg($msg);
	}
	
	// 4.回复视频消息
	protected function generateVideo($toUser, $fromUser, $mediaId, $title='', $desc='')
	{
		// 回复视频消息模板
		$voiceTpl = "<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[video]]></MsgType><Video><MediaId><![CDATA[%s]]></MediaId><Title><![CDATA[%s]]></Title><Description><![CDATA[%s]]></Description></Video></xml>";
		$msg = sprintf($picTpl, $toUser, $fromUser, time(), $mediaId, $title, $desc);
		return $this->handleLastMsg($msg);
	}
	
	// 5.回复音乐消息
	protected function generateMusic($toUser, $fromUser, $mediaId, $title='', $desc='', $musicUrl='', $hQMusicUrl='')
	{
		// 回复音乐消息模板
		$voiceTpl = "<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[music]]></MsgType><Music><Title><![CDATA[%s]]></Title><Description><![CDATA[%s]]></Description><MusicUrl><![CDATA[%s]]></MusicUrl><HQMusicUrl><![CDATA[%s]]></HQMusicUrl><ThumbMediaId><![CDATA[%s]]></ThumbMediaId></Music></xml>";
		$msg = sprintf($picTpl, $toUser, $fromUser, time(), $title, $desc, $musicUrl, $hQMusicUrl, $mediaId);
		return $this->handleLastMsg($msg);
	}
	
	// 6.回复图文消息
	protected function generateNews()
	{
		
	}
	
	
}