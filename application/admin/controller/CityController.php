<?php
namespace app\admin\controller;

use app\admin\controller\BaseController as Base;
use app\admin\model\CityModel as City;

class CityController extends Base 
{
	public function _initialize()
	{

	}

	/**
	 * @api       获取该城市的用户 
	 * @auther    李凯伦  
	 * @datetime  20180603 01:21
	 * @param     <参数类型>  <参数描述>   
	 * @return    <返回类型>
	 */
	public function getCityUsers(City $city)
	{
		$theCity = City::get(64);
		return $theCity->users(0, 'id user_id, username');
	}

	/**
	 * @api       获取该城市博客 
	 * @auther    李凯伦  
	 * @datetime  20180603 01:21
	 * @param     <参数类型>  <参数描述>   
	 * @return    <返回类型>
	 */
	public function getCityBlogs()
	{
		$theCity = City::get(213);
		return $theCity->city_blogs;
	}

}