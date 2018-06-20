<?php
namespace app\admin\controller;

use app\admin\controller\BaseController as Base;
use app\admin\model\UserModel as User;

class UserController extends Base 
{
	public function _initialize()
	{
		
	}

	/**
	 * @api       用户注册 
	 * @auther    李凯伦  
	 * @datetime  20180603 01:21
	 * @param     <参数类型>  <参数描述>   
	 * @return    <返回类型>
	 */
	public function doRegister(User $user) 
	{
		$username = 'llkl';
		$sex      = 1;
		$age      = 26;

		/* 关于软删除
		// 默认情况下查询的数据不包含软删除数据，如果需要包含软删除的数据, 可以使用下面的方式查询 
		$usersInfo = User::withTrashed()->find();

		// 如果你的查询条件比较复杂，尤其是某些特殊情况下使用OR查询条件会把软删除数据也查询出来，可以使用闭包查询的方式解决，如下：
		User::where(function($query) {
			$query->where('id', '>', 10)
		    	->whereOr('name', 'like', 'think');
		})->select();


		*/
		// 查询用户名是否已存在
		if (!$user->getUserByUsername($username)) {
			// 用户名不存在，可注册 
			$registerRes = $user->createUser($username, $sex, $age);
			if ($registerRes) {
				// 注册成功
				echo $user->id; // userId
			} else {
				// 注册失败
				echo '请重试！';
			}
		} else {
			// 用户名已存在，不可注册
			echo '用户名已存在！';
		}
	}

	/**
	 * @api       根据用户名称获取用户 
	 * @auther    李凯伦  
	 * @datetime  20180603 01:21
	 * @param     <参数类型>  <参数描述>   
	 * @return    <返回类型>
	 */
	public function getUserByUsername(User $user)
	{
		// $userInfo = $user->getUserByUsername('llkl');
		// $userInfo = User::where(['username'=>'llkl'])->select();
		$userInfo = User::get(['username'=>'llkl']);
		if ($userInfo) {
			// $userInfo = $userInfo->toArray(); // 转换为数组
			return $userInfo;
		} else {
			return '用户不存在';
		}
	}

    /**
     * @api       更改用户状态 
     * @auther    李凯伦  
     * @datetime  20180603 01:21
     * @param     <参数类型>  <参数描述>   
     * @return    <返回类型>
     */
    public function changeUserStatus(User $user)
    {
    	$changeRes = $user->editUser(1, ['status'=>0]);

    	var_dump($changeRes);
    }

    /**
     * @api       获取可用状态用户 
     * @auther    李凯伦  
     * @datetime  20180603 01:21
     * @param     <参数类型>  <参数描述>   
     * @return    <返回类型>
     */
    public function getEnableUsers() 
    {
    	$pageNum  = 0;
    	$pageSize = 10;
    	// 静态查询
    	$users = User::where('status', 1)->limit($pageNum, $pageSize)->select();

    	/*
    	// 闭包查询
    	$where = ['status'=>1];
    	$users = User::all(function($query)use($where ,$pageNum, $pageSize){
    		$query->where($where)->limit($pageNum,$pageSize);
    	});
    	*/

    	return json($users);
    }

    /**
     * @api       获取用户Blogs
     * @auther    李凯伦  
     * @datetime  20180603 01:21
     * @param     <参数类型>  <参数描述>   
     * @return    <返回类型>
     */
    public function getUserBlogs()
    {
    	$userId   = request()->param('user_id');
    	$pageNum  = request()->param('p', 0);
    	$pageSize = request()->param('size', 20);

    	$theUser = User::get($userId);
    	return $theUser->blogs()->limit($pageNum, $pageSize)->select();
    }
}