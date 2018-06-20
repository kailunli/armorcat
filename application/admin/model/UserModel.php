<?php
namespace app\admin\model;

use app\admin\model\BaseModel as Base;
use traits\model\SoftDelete;

class UserModel extends Base
{
	use SoftDelete;
	// 该模型对应的数据库表名称
	protected $table = 'test_user'; // 优先级高于$name
	// protected $name = 'user'; // 自动加前缀

	// 设置单独的数据库配置
	// protected $connection = 'db_config'; // 在应用或者模块的配置文件中单独配置db_config参数

	// 设置模型的数据集返回类型
    protected $resultSetType = 'collection'; // 默认array
	// protected $resultSetType = 'array';

    // 设置数据表只读字段，即该字段在写入以后就不允许被更改
	protected $readonly = ['username'];

	// 数据表的所有时间字段统一使用autoWriteTimestamp属性规范时间类型（支持datetime、date、timestamp以及integer）
	protected $autoWriteTimestamp = 'integer';

	// 设置软删除时间字段
	protected $deleteTime = 'delete_time'; // 默认delete_time

	/*****************************************/
    /**********  以下定义关联模型  ***********/
    /*****************************************/
	/**
	  1.关联关系通常有一个参照模型，这个参照模型我们一般称为主模型（或者当前模型），关联关系对应的模型就是关联模型.
	  2.关联关系是指定义在主模型中的关联，有些关联关系还会设计到一个中间表的概念，但中间表不一定需要存在具体的模型。
      3.主模型和关联模型之间通常是通过某个外键进行关联，而这个外键的命名系统会有一个约定规则，通常是主模型名称+_id，尽量遵循这个约定会给关联定义带来很大简化。
	*/

    /**
    * @api       获取用户的Blogs
    * @auther    李凯伦  
    * @datetime  20180603 01:21
    * @param     <参数类型>  <参数描述>   
    * @return    <返回类型>
    */
    public function blogs()
    {
        return $this->hasMany('BlogModel', 'user_id', 'id');
    }


    /******************************************/
    /**********  以下是查询条件预定义  ********/
    /******************************************/
    protected function scopeStatus($query, $status=0)
    {
    	$query->where('status', $status);
    }


    /***************************************/
    /**********  以下是业务逻辑  ***********/
    /***************************************/

    /**
     * @api       根据名称查询用户 
     * @auther    李凯伦  
     * @datetime  20180603 01:21
     * @param     <参数类型>  <参数描述>   
     * @return    <返回类型>
     */
    public function getUserByUsername($username)
    {
    	$user = $this->get(['username'=>$username]);
    	if (!$user) {
    		return false;
    	} 
    	return $user;
    }

    /**
     * @api       新增用户 
     * @auther    李凯伦  
     * @datetime  20180603 01:21
     * @param     <参数类型>  <参数描述>   
     * @return    <返回类型>
     */
    public function createUser($username, $sex=0, $age=0, $remark=null) 
    {
    	$createRes = $this->save([
    		'username' => $username,
    		'sex'      => $sex,
    		'age'      => $age,
    		'remark'   => $remark,
    		'create_time' => time()
    	]);
    	
    	if ($createRes) {
    		// 新增成功，返回新增的用户的ID
    		// return $this->getData('id');
    		return true;
    	} else {
    		// 新增失败
    		return false;
    	}
    }

    /**
     * @api       编辑用户信息
     * @auther    李凯伦  
     * @datetime  20180603 01:21
     * @param     <参数类型>  <参数描述>   
     * @return    <返回类型>
     */
    public function editUser($userId, array $userInfo) 
    {
    	return $this->get($userId)->save($userInfo);
    }



}