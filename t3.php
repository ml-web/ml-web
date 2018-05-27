<?
//считываем json в массив $obj
$decode = file_get_contents('chart2.json');
$obj = json_decode($decode);
//echo '<pre>'; print_r ($obj); echo '</pre>';
?>
<?
//разбиваем массив $obj на множество массивов типа $array(N)
foreach ($obj as $array) {
    foreach ($array as $j => $val) {
    ${"array{$j}"}[] = $val;
  }
}
//echo '<pre>'; print_r($array2); echo '</pre>';
?>
<?
// заменяем начальные значения во всех полученных массивов по условию задачи 
//(если в начале массива элементы больше 3 раз равны 100 заменяем на null )
for($i = 0; $i < count($obj); $i++) {	
	for ($j = 0; $j < count($obj[0]); $j++) {
		if ((${"array{$j}"}[0] == 100) && (${"array{$j}"}[1] == 100) && (${"array{$j}"}[2] == 100) && (${"array{$j}"}[3] == 100)) {
			if ($i==0) { 
				$obj_1[$i][$j] =  null;
				} 
				elseif ((${"array{$j}"}[$i-1] == 100)&&(${"array{$j}"}[$i] == 100)) {
				$obj_1[$i][$j] = null;
				} else {
					$obj_1[$i][$j] = ${"array{$j}"}[$i];
					};
		} else {
			$obj_1[$i][$j] = ${"array{$j}"}[$i];
			};
	}; 
};
//echo '<pre>'; print_r($obj_1); echo '</pre>';
?>
<? 
// преобразуем массив в JSON и запишем в файл chart_result.json
 file_put_contents('chart_result.json', json_encode($obj_1));
//  echo json_encode($obj_1);
?>