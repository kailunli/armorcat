<?php
namespace app\wechat\controller;

use app\wechat\controller\BaseController as Base;

class WechatPublic extends Base
{		
	public function __construct()
	{
		parent::__construct();
	}
	// 入口
	public function index()
	{	
		if( null !== $this->echostr ) {
			// 第一次验证,有GET参数echostr
			if (parent::checkSignature()) {
				return $this->echostr;
			}
		} else {
			parent::setXmlObject(); 
			// 后续走业务流程
			$this->responseMsg();
		}
	}	
	
	// 处理入口
	public function responseMsg()
	{
		$wechatXml = $this->wechatXml;       
		$msgType   = strtolower( $wechatXml->MsgType );
		
		if ( $msgType == 'event' ) { // 处理事件
			$this->handleEvent();               
		} else if ( $msgType == 'text' ) {  // 处理文本消息
			$this->handleText();
		} else if ( $msgType == 'image' ) { // 处理图片消息
			$this->handleImg();
		} else if ( $msgType == 'voice' ) { // 处理语音消息
			$this->handleVoice();
		} else if ( $msgType == 'video' ) { // 处理视频消息
			$this->handleVideo();
		} else if ( $msgType == 'shortvideo' ) { // 处理短视频消息
			$this->handleShortVideo();
		} else if ( $msgType == 'location' ) {   // 处理地理位置消息
			$this->handleLocation();
		}		
	}
	
	/****************以下是处理事件的代码实现******************/
	// 事件
	private function handleEvent()
	{	
		$wechatXml = $this->wechatXml;
		$event     = strtolower($wechatXml->Event);
		switch ($event) {
			case 'subscribe': // 关注
				$this->eventSubscribe();
			break;
			case 'unsubscribe': // 取消关注
				$this->eventUnsubscribe();
			break;
			case 'scan': // 扫描带参数二维码事件:用户已关注时的事件推送
				$this->eventScan();
			break;
			case 'click': // 自定义菜单事件:点击菜单拉取消息时的事件推送
				$this->eventClick();
			break;
			case 'view': // 自定义菜单事件:点击菜单跳转链接时的事件推送
				$this->eventView();
			break;
		}
	}
	
	/****************以下是关注与取消关注的业务逻辑******************/
	// 关注
	private function eventSubscribe()
	{
		$wechatXml = $this->wechatXml;
		echo $this->generateText($wechatXml->FromUserName, $wechatXml->ToUserName, '欢迎关注“邀月同宿”公众号');
	}
	
	// 取消关注
	private function eventUnsubscribe()
	{
		
	}
	
	// 扫描带参数二维码事件:用户已关注时的事件推送
	private function eventScan()
	{
		
	}
	
	// 自定义菜单事件:点击菜单拉取消息时的事件推送
	private function eventClick()
	{
		
	}
	
	// 自定义菜单事件:点击菜单跳转链接时的事件推送
	private function eventView()
	{
		
	}
	
	/****************以下是处理消息的业务逻辑******************/
	/** 处理文本消息
	 <xml>
		<ToUserName>< ![CDATA[toUser] ]>
		</ToUserName>
		<FromUserName>< ![CDATA[fromUser] ]>
		</FromUserName>
		<CreateTime>1348831860</CreateTime>
		<MsgType>< ![CDATA[text] ]>
		</MsgType>
		<Content>< ![CDATA[this is a test] ]>
		</Content>
		<MsgId>1234567890123456</MsgId>
	</xml>
	 */
	private function handleText()
	{
		$wechatXml = $this->wechatXml;
		$content   = $wechatXml->Content;
	
		switch ( trim($content) ) {
			case '熊猫':
				$response = '<a href="http://www.armorcat.com/">www.armorcat.com</a>';
				break;
			default:
				$response = 'What you want?';
				break;
		}
		echo $this->generateText($wechatXml->FromUserName, $wechatXml->ToUserName, $response);		
	}
	
	/** 处理图片消息
	<xml>
		<ToUserName>< ![CDATA[toUser] ]>
		</ToUserName>
		<FromUserName>< ![CDATA[fromUser] ]>
		</FromUserName>
		<CreateTime>1348831860</CreateTime>
		<MsgType>< ![CDATA[image] ]>
		</MsgType>
		<PicUrl>< ![CDATA[this is a url] ]>
		</PicUrl>
		<MediaId>< ![CDATA[media_id] ]>
		</MediaId>
		<MsgId>1234567890123456</MsgId>
	</xml>
	 */
	 private function handlePic()
	 {
		 
	 }
	 
	 /** 处理语音消息
	<xml>
		<ToUserName>< ![CDATA[toUser] ]>
		</ToUserName>
		<FromUserName>< ![CDATA[fromUser] ]>
		</FromUserName>
		<CreateTime>1357290913</CreateTime>
		<MsgType>< ![CDATA[voice] ]>
		</MsgType>
		<MediaId>< ![CDATA[media_id] ]>
		</MediaId>
		<Format>< ![CDATA[Format] ]>
		</Format>
		<MsgId>1234567890123456</MsgId>
	</xml>
	 */
	 private function handleVoice()
	 {
		
	 }
	 
	 /** 处理视频消息
	<xml>
		<ToUserName>< ![CDATA[toUser] ]>
		</ToUserName>
		<FromUserName>< ![CDATA[fromUser] ]>
		</FromUserName>
		<CreateTime>1357290913</CreateTime>
		<MsgType>< ![CDATA[video] ]>
		</MsgType>
		<MediaId>< ![CDATA[media_id] ]>
		</MediaId>
		<ThumbMediaId>< ![CDATA[thumb_media_id] ]>
		</ThumbMediaId>
		<MsgId>1234567890123456</MsgId>
	</xml>
	 */
	 private function handleVideo()
	 {
		 
	 }
	 
	 /** 处理小视频消息
	 <xml>
		<ToUserName>< ![CDATA[toUser] ]>
		</ToUserName>
		<FromUserName>< ![CDATA[fromUser] ]>
		</FromUserName>
		<CreateTime>1357290913</CreateTime>
		<MsgType>< ![CDATA[shortvideo] ]>
		</MsgType>
		<MediaId>< ![CDATA[media_id] ]>
		</MediaId>
		<ThumbMediaId>< ![CDATA[thumb_media_id] ]>
		</ThumbMediaId>
		<MsgId>1234567890123456</MsgId>
	</xml>
	*/
	private function handleShortVideo()
	{
		
	}
	
	/** 处理地理位置消息
	<xml>
		<ToUserName>< ![CDATA[toUser] ]>
		</ToUserName>
		<FromUserName>< ![CDATA[fromUser] ]>
		</FromUserName>
		<CreateTime>1351776360</CreateTime>
		<MsgType>< ![CDATA[location] ]>
		</MsgType>
		<Location_X>23.134521</Location_X>
		<Location_Y>113.358803</Location_Y>
		<Scale>20</Scale>
		<Label>< ![CDATA[位置信息] ]>
		</Label>
		<MsgId>1234567890123456</MsgId>
	</xml>
	*/
	private function handleLocation()
	{
		
	}
	
	/** 处理链接消息
	<xml>
		<ToUserName>< ![CDATA[toUser] ]>
		</ToUserName>
		<FromUserName>< ![CDATA[fromUser] ]>
		</FromUserName>
		<CreateTime>1351776360</CreateTime>
		<MsgType>< ![CDATA[link] ]>
		</MsgType>
		<Title>< ![CDATA[公众平台官网链接] ]>
		</Title>
		<Description>< ![CDATA[公众平台官网链接] ]>
		</Description>
		<Url>< ![CDATA[url] ]>
		</Url>
		<MsgId>1234567890123456</MsgId>
	</xml>
	*/
	private function handleLink()
	{
		
	}	
}