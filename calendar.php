<?php

	// 1. 当前的月份是多少天。
	
	$days = date('t');
	
	$arr_date = range(1, $days);

	$minus = 31 - $days;
	// 将数组元素总数补齐为 35
	while($minus--) {
		$arr_date[] = "";
	}
	
	while(count($arr_date) < 35) {
		array_unshift($arr_date, "");
	}
	
	/*
	for($i = 1; $i <= $days; $i++) {
		
		$arr_date[] = 
	}
	*/
	// 2. 日期与星期几一一对应。

	// 3. 页面
?>

<meta charset="utf-8" />
<style type="text/css">
	table{text-align: center;}
	td {padding: 10px 10px;}
</style>
<table border="1px" cellspacing="0" cellpadding="0"> 
	<tr style="font-weight: bolder;">
		<td>Mon</td>
		<td>Tue</td>
		<td>Wed</td>
		<td>Thur</td>
		<td>Frid</td>
		<td>Sat</td>
		<td>Sunday</td>
	</tr>
	<tr>
	<?php 
		$current_date = date('j');
		foreach($arr_date as $key => $date) {
		
			$format_date = date('Y-m') .'-' . $date;
			$week = date('N', strtotime($format_date));
			
			if ($week) {
				
				if ($week == 7) {
					if ($date == $current_date) {
						echo "<td style='color: red; font-weight: bolder;text-decoration: underline;'>{$date}</td></tr>";
					} else {
						echo "<td>{$date}</td></tr>";
					}
				} else {
					if ($date == $current_date) {
						echo "<td style='color: red; font-weight: bolder;text-decoration: underline;'>{$date}</td>";
					} else {
						echo "<td>{$date}</td>";
					}
				}
			} else {
				echo "<td>&nbsp;</td>";
			}
		}
	?>
</table>
