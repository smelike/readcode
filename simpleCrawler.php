<?php

	// 数据采集
	
	/*
		https://www.hinabian.com/theme/detail/7187963787461753308.html
		https://www.hinabian.com/theme/detail/7161618734657271587.html
		// 7,161,618,734,657,271,587
	*/
	header("Content-type: text/html; charset=utf-8");
	$ch = curl_init("http://www.2424324252525325example.com/?from=baidupz");
	
	$fp = fopen("hinabian.txt", "w");
	curl_setopt($ch, CURLOPT_FILE, $fp);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	//curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
	//curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
	
	$return = curl_exec($ch);
	curl_close($ch);
	fclose($fp);

  // 过滤采集数据，抓取图片到本地
  $source = fopen('hinabian.txt', 'r');
	
	$pattern = "/http:\/\/\S+(.png|jpg|jpeg|gif)/";
	
	$img = [];
	
	while ($row = fread($source, 1024)) {
		preg_match_all($pattern, $row, $match);
		$img[] = array_filter($match[0]);
	}
	
	$new_img = [];
	foreach($img as $item) {
		
		if ($item) {
			$new_img[] = $item;
		}
	}
	
	// 将多为数组转换为一维数组
	$new = [];
	function merge(array $parameter, &$new) {
		
		foreach($parameter as $item) {
			if (is_array($item) && $item) {
				merge($item, $new);
			}
			if (!is_array($item)) $new[] = $item;
		}
	}
	merge($new_img, $new);
	
	// 将图片文件下载到本地
	//header("content-type:application/x-msdownload");
	foreach($new as $item) {
		
		$fileName = explode('/', $item);
		$fileName = array_pop($fileName);
		$path = "download/$fileName";
		//header("content-disposition:attachement;filename={$fileName}");
		//readfile($path, false, fopen($item));//下载 
		// 读取远程连接文件，并保存到指定路径。
		$source = file_get_contents($item);
		file_put_contents($path, $source);
	}

	
	
