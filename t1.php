<?require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");?>
<!-- 
======================================================
1 шаг. 
поиск на примере модуля iblock
получаем список файлов из таблицы `b_file` 
которые находятся в полях FILE_NAME и ORIGINAL_NAME при условии `MODULE_ID`=>'iblock' 
и собираем их в массив $dir_db 
также можно добавить поиск по fileman и другим модулям (или по всем или сделать выбор из модулей) 
======================================================
-->
<?
$dir_db = array();

global $DB;
$results = $DB->Query("SELECT `SUBDIR`, `FILE_NAME`, `ORIGINAL_NAME` FROM `b_file` WHERE `MODULE_ID` LIKE 'iblock'");
while($row = $results->Fetch()){
   // echo $row['SUBDIR'].'/'.$row['FILE_NAME'].'</br>'; 
   // echo $row['SUBDIR'].'/'.$row['ORIGINAL_NAME'].'</br>'; 
   $FILE_NAME = 'upload/'.$row['SUBDIR'].'/'.$row['FILE_NAME'];
   array_push($dir_db, $FILE_NAME);
   $ORIGINAL_NAME = 'upload/'.$row['SUBDIR'].'/'.$row['ORIGINAL_NAME'];
   array_push($dir_db, $ORIGINAL_NAME);
   
};
// в масиве $dir_db собраны имена файлов картинок из таблицы `b_file`

	//echo '<pre>'; print_r($dir_db); echo '</pre>';
?>
<!-- 
==================================================
2 шаг.
получаем список файлов из директории upload/iblock 
и собираем их в массив $dir_iblock. 
Можно добавить другие папки для поиска файлов или 
сделать решение с привязкой названия директории в 
зависимости от выбранного модуля при поиске в базе
==================================================
-->
<?
$dir_iblock = array();

$dir  = 'upload/iblock';
//пропускаем точки
$skip = array('.', '..');
//сканируем директорию для получения вложенных директорий
$dir1 = scandir($dir);
foreach($dir1 as $dir2) {
    if(!in_array($dir2, $skip)) {
		$dir2 = $dir.'/'.$dir2;
		//сканируем директорию для получения файлов
		$dir3 = scandir($dir2);
			foreach($dir3 as $files) {
				if(!in_array($files, $skip))	{
			//	echo $dir2.'/'.$files.'<br />';
			$files_iblock = $dir2.'/'.$files;
			array_push($dir_iblock, $files_iblock);
				};
			};
			
	};
};

// в масиве $dir_iblock собраны имена файлов картинок из директории `upload/iblock`

	//echo '<pre>'; print_r($dir_iblock); echo '</pre>';
?>
<!-- 
=====================================================
3 шаг.
вычитам массив $dir_db из $dir_iblock и 
рекурсивно удаляем файлы в директории /upload/iblock
которых нет в таблице 'b_file' 
=====================================================
-->

массив $result со списком файлов из директории <br>
/upload/iblock которых нет в таблице 'b_file' базы и удаляем их: <br>
<?
$result = array_diff($dir_iblock, $dir_db);
echo '<pre>'; print_r($result); echo '</pre><br>';

//выводим список файлов картинок из массива $result которые нужно удалить и удаляем их

			foreach($result as $files_del) {
			$files_del = $_SERVER['DOCUMENT_ROOT'].'/'.$files_del;
			unlink ($files_del);
			// echo $files_del.'<br>';
			};
?>
<!-- 
==================================================================
4 шаг.
рекурсивно ищем и удаляем пустые папки в директории /upload/iblock
Также можно пройтись по другим папкам в зависимости от поставленной задачи
================================================================== 
-->
<?
$path = $_SERVER['DOCUMENT_ROOT'].'/upload/iblock';
 
$idir = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $path, FilesystemIterator::SKIP_DOTS ), RecursiveIteratorIterator::CHILD_FIRST );
 
foreach( $idir as $v ){
    if( $v->isDir() and $v->isWritable() ){
        $f = glob( $idir->key() . '/*.*' );
        if( empty( $f ) ){
            rmdir( $idir->key() );
            echo 'remove directory ' . $idir->key() . '<br>' . "\n";
        }
    }
}
?>