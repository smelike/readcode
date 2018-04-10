<?php

	/*
	
		fetch pdf from dodi
		
		URL 规则：
			
			http://kczx.dodi.cn/PHP/DCWE13.0/
			
			http://kczx.dodi.cn/PHP/DCWE13.0/2_2.pdf
			
			
			第二阶段、第三阶段、第四阶段，皆为 22 天.
			
			PDF 命名规则，有两种。一种使用下划线(_)，另一种是用横杠(-)
			
			
			实现思路：
			
				1、实现文件命名的规则
				
				2、调用 file_get_contents() 打开链接；
				
				3、判断是否存在文件；
				
				4、有，则实现将保存文件到本地；
				
	*/
	
	$baseUrl = 'http://kczx.dodi.cn/PHP/DCWE13.0/';
	
	$pdfList = [];
	
	//for ($i = 2; $i < 4; $i++) {
	//}
	$phases = array(3, 4);
	$days = range(1, 22);
	
	foreach($phases as $val) {
		foreach($days as $item) {
		
		// 如何拼接得更完美呢？理性与感性的冲突就开始现身了。
		
			$pdfList[$val][] = $val . '-' . $item . '.pdf';
		}
	}
	
	//var_dump($pdfList);
	foreach($pdfList as $list) {
		
		foreach($list as $item) {
			
			$source = file_get_contents($baseUrl . $item);
			$path = "pdf/$item";
			
			file_put_contents($path, $source);
		}
	}
	
	/*
	header('Content-Type: application/pdf');
	header('Content-Disposition: attachment;filename="01simple.pdf"');
	header('Cache-Control: max-age=0');
	*/
	
	
	
