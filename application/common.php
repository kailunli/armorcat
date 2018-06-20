<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
if (!function_exists('curl')) {
	function curl($url, $data=[]) {
		$ch = curl_init();  
		curl_setopt($ch, CURLOPT_URL, $url);  
		curl_setopt($ch, CURLOPT_HEADER, false);  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果把这行注释掉的话，就会直接输出  
		if ($data && is_array($data)) {
			curl_setopt($ch, CURLOPT_POST, 1);  
		    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		}
		$result=curl_exec($ch);  
		curl_close($ch);  
		
		return $result;
	}
}

if (!function_exists('curl_get')) {
	function curl_get($url) {
		$ch = curl_init();  
		curl_setopt($ch, CURLOPT_URL, $url);  
		curl_setopt($ch, CURLOPT_HEADER, false);  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果把这行注释掉的话，就会直接输出  
		$result=curl_exec($ch);  
		curl_close($ch);  
		
		return $result;
	}
}

if (!function_exists('curl_post')) {
	function curl_post($url, array $data, $headers=[]) {  
		$ch = curl_init();  
		if ($headers) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}
		curl_setopt($ch, CURLOPT_URL, $url);  
		curl_setopt($ch, CURLOPT_HEADER, false);  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果把这行注释掉的话，就会直接输出  
		curl_setopt($ch, CURLOPT_POST, 1);  
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		$result=curl_exec($ch);  
		curl_close($ch);  
		return $result;
	}
}