<?php
namespace app\admin\model;

use app\admin\model\BaseModel as Base;

class CityModel extends Base
{
	protected $table = 'test_city';

	/**
	 * @api       获取城市用户 
	 * @auther    李凯伦  
	 * @datetime  20180603 01:21
	 * @param     <参数类型>  <参数描述>   
	 * @return    <返回类型>
	 */
    public function users($pageNum=0, $field='*', $pageSize=20)
    {
    	return $this->hasMany('UserModel', 'city_id', 'id')
    	            ->field($field)
    	            ->limit($pageNum, $pageSize)
    	            ->select();
    }

    /**
     * @api       获取该城市用户的博客 
     * @auther    李凯伦  
     * @datetime  20180603 01:21
     * @param     <参数类型>  <参数描述>   
     * @return    <返回类型>
     */
    public function cityBlogs($pageNum=0, $field='*', $pageSize=20)
    {
    	return $this->hasManyThrough('BlogModel', 'UserModel', 'city_id', 'user_id', 'id')
    	            ->field($field)
    	            ->limit($pageNum, $pageSize)
    	            ->select();
    } 

}