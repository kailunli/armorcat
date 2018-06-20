<?php
namespace app\admin\controller;

use app\admin\controller\BaseController as Base;
use think\Request;

class Test extends Base 
{
	public function _initialize()
	{

	}

	/**
	 * @api       tp请求信息
	 * @auther    李凯伦  
	 * @datetime  20180603 01:21
	 * @param     <参数类型>  <参数描述>   
	 * @return    <返回类型>
	 */
	public function myRequest(Request $request)
	{
		echo "<pre>";
		/*
		//var_dump($request);
		var_dump($request->param());
		var_dump($request->get());
		var_dump($request->post());
		var_dump($request->route());
		var_dump($request->dispatch());

		echo "<br />---------------------<br />";

		var_dump($request->domain());
		var_dump($request->baseFile());
		var_dump($request->url());
		var_dump($request->baseUrl());
		var_dump($request->root());
		var_dump($request->root(true));
		var_dump($request->pathinfo());
		var_dump($request->path());

		echo "<br />---------------------<br />";

		var_dump($request->module());
		var_dump($request->controller());
		var_dump($request->action());

		echo "<br />---------------------<br />";

		echo '请求方法：' . $request->method() . '<br/>';
		echo '资源类型：' . $request->type() . '<br/>';
		echo '访问ip地址：' . $request->ip() . '<br/>';
		echo '是否AJax请求：' . var_export($request->isAjax(), true) . '<br/>';
		echo '请求参数：';
		dump($request->param());
		echo '请求参数：仅包含name';
		dump($request->only(['name']));
		echo '请求参数：排除name';
		dump($request->except(['name']));

		echo "<br />---------------------<br />";
		*/

		// 是否为 GET 请求
		if (Request::instance()->isGet()) echo "当前为 GET 请求";
		// 是否为 POST 请求
		if (Request::instance()->isPost()) echo "当前为 POST 请求";
		// 是否为 PUT 请求
		if (Request::instance()->isPut()) echo "当前为 PUT 请求";
		// 是否为 DELETE 请求
		if (Request::instance()->isDelete()) echo "当前为 DELETE 请求";
		// 是否为 Ajax 请求
		if (Request::instance()->isAjax()) echo "当前为 Ajax 请求";
		// 是否为 Pjax 请求
		if (Request::instance()->isPjax()) echo "当前为 Pjax 请求";
		// 是否为手机访问
		if (Request::instance()->isMobile()) echo "当前为手机访问";
		// 是否为 HEAD 请求
		if (Request::instance()->isHead()) echo "当前为 HEAD 请求";
		// 是否为 Patch 请求
		if (Request::instance()->isPatch()) echo "当前为 PATCH 请求";
		// 是否为 OPTIONS 请求
		if (Request::instance()->isOptions()) echo "当前为 OPTIONS 请求";
		// 是否为 cli
		if (Request::instance()->isCli()) echo "当前为 cli";
		// 是否为 cgi
		if (Request::instance()->isCgi()) echo "当前为 cgi";
	}


	public function server()
	{
		echo "<pre>";
		var_dump($_SERVER);
	}

}