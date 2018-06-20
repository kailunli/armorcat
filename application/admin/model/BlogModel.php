<?php
namespace app\admin\model;

use app\admin\model\BaseModel as Base;
use traits\model\SoftDelete;

class BlogModel extends Base
{
	use SoftDelete;
	// 该模型对应的数据库表名称
	protected $table = 'test_blog'; // 优先级高于$name
	// protected $name = 'user'; // 自动加前缀

// 设置数据表只读字段，即该字段在写入以后就不允许被更改
    protected $readonly = ['id', 'user_id', 'create_time'];

    // 只允许更新的字段
    protected $filed = ['name', 'update_time', 'delete_time', 'cate_id'];

	// 设置单独的数据库配置
	// protected $connection = 'db_config'; // 在应用或者模块的配置文件中单独配置db_config参数

	// 设置模型的数据集返回类型
    protected $resultSetType = 'collection'; // 默认array
	// protected $resultSetType = 'array';

	// 数据表的所有时间字段统一使用autoWriteTimestamp属性规范时间类型（支持datetime、date、timestamp以及integer）
	protected $autoWriteTimestamp = 'integer';

    /**
    // 开启时间字段自动写入
    protected $autoWriteTimestamp = false; // 默认false
    // 定义时间字段名
    protected $createTime = 'create_time'; // 默认create_at, 新增数据的时候自动写入
    protected $updateTime = 'update_time'; // 默认update_at, 新增和更新的时候都会自动写入
    */

    // 设置软删除时间字段
    protected $deleteTime = 'delete_time'; // 默认delete_time

	/*****************************************/
    /**********  以下定义关联模型  ***********/
    /*****************************************/
	/**
        1.关联关系通常有一个参照模型，这个参照模型我们一般称为主模型（或者当前模型），关联关系对应的模型就是关联模型.
        2.关联关系是指定义在主模型中的关联，有些关联关系还会设计到一个中间表的概念，但中间表不一定需要存在具体的模型。
        3.主模型和关联模型之间通常是通过某个外键进行关联，而这个外键的命名系统会有一个约定规则，通常是主模型名称+_id，尽量遵循这个约定会给关联定义带来很大简化。
        
        hasOne('关联模型','外键','主键')
        hasMany('关联模型','外键','主键')
        hasManyThrough() // 远程一对多

        belongsTo('关联模型','外键','关联表主键')
        belongsToMany('关联模型','中间表','外键','关联键')

        morphMany() // 多态一对多
        morphTo()


        ==============
        用法：hasMany('关联模型','外键','主键');
        除了关联模型外，其它参数都是可选。

        关联模型（必须）：模型名或者模型类名
        外键：关联模型外键，默认的外键名规则是当前模型名+_id
        主键：当前模型主键，一般会自动获取也可以指定传入



        关联方法必须使用驼峰法命名；
        关联方法一般无需定义任何参数；
        关联调用的时候驼峰法和小写+下划线都支持；
        关联字段设计尽可能按照规范可以简化关联定义；
        关联方法定义可以添加额外查询条件；
	*/

    /**
     * @api       获取博客的内容 
     * @auther    李凯伦  
     * @datetime  20180603 01:21
     * @param     <参数类型>  <参数描述>   
     * @return    <返回类型>
     */
    public function content()
    {
        return $this->hasOne('BlogContentModel', 'blog_id', 'id');
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

    



}