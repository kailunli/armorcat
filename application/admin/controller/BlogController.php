<?php
namespace app\admin\controller;

use app\admin\controller\BaseController as Base;
use app\admin\model\BlogModel as Blog;

class BlogController extends Base
{
	public function _initialize()
	{

	}

	/**
	 * @api       获取某博客内容 
	 * @auther    李凯伦  
	 * @datetime  20180603 01:21
	 * @param     <参数类型>  <参数描述>   
	 * @return    <返回类型>
	 */
	public function getBlogContent()
	{
		$theBolg = Blog::get(1);
		return $theBolg->content;
	}

}