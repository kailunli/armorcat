<?php 
namespace app\db;

use think\db\Query;

class ModelQuery extends Query
{
	public function top($num)
    {
    	return $this->limit($num)-select();
    }
}