<?php
//ustawia odpowiednią strefę czasową dla całego skryptu
date_default_timezone_set('Europe/Warsaw');

$cache_dir = "../../../_cache";
//maksymalne wymiary obrazka
$max_width = 800;
$max_height = 800;

//pobiera wysokość i szerokość miniaturki
$thumb_x = $_GET['width'];
$thumb_y = $_GET['height'];

//pobiera i określa nazwę i ścieżkę do obrazka
$image_path = $_GET['src'];
$path = explode('/', $_GET['src']);
$image_name = $path[sizeof($path) - 1];

//ustawia ścieżkę do pliku w cache
$cache_path = $cache_dir . '/';
for ($i = 0; $i < sizeof($path) - 1; $i++)
{
	$cache_path .= $path[$i] . '/';
}
if ($thumb_x != 0 && $thumb_y != 0)
	$cache_path .= $thumb_x . 'x' . $thumb_y . '/';

$cache_image = $cache_path . $image_name;
$image_path = '../../../_userfiles/' . $image_path;
//sprawdza czy obrazek istnieje
if (file_exists($image_path))
{
	//sprawdza czy obrazek jest w cache
	if (!file_exists($cache_image))
	{
		//dołączenie klasy upload i utworzenie nowego obiektu 
		require_once('../../../library/Upload/class.upload.php');
		$upload = new upload($image_path);
		
		//konfiguracja miniaturki
		if($thumb_x != 0 && $thumb_y  != 0)
		{
			$upload->image_x = $thumb_x;
			$upload->image_y = $thumb_y;
			$upload->image_resize = true;
			$upload->image_ratio_crop = true;
		} else {
			list($image_width, $image_height) = getimagesize($image_path);
			if ($image_width > $max_width && $image_width >= $image_height)
			{
				$upload->image_x = $max_width;
				$upload->image_ratio_y = true;
				$upload->image_resize = true;
			} 
			else if ($image_width > $max_width && $image_width > $image_height)
			{
				$upload->image_y = $max_height;
				$upload->image_ratio_x = true;
				$upload->image_resize = true;
			}
		}
		$upload->image_convert = 'jpg';
		$upload->jpeg_quality = 100;
		
		//konfiguracja plików i folderów
        $upload->file_overwrite = true;
    	$upload->file_auto_rename = false;
    	$upload->auto_create_dir = true;
    	$upload->dir_auto_chmod = 0777;
		
		//dodaje znak wodny, jeżeli został podany
		if (isset($_GET['watermark'])) 
		{
            $upload->image_watermark = $_GET['watermark'] . '.png';
        }
		
		//generuje miniaturke
		$upload->process($cache_path);
		
		//wyświetla logi
		//exit($upload->log);
	}

	$last_modified = filemtime($cache_image);
	$etag = md5_file($cache_image);

	//ustawia odpowiednie nagłówki
	header('Content-Type: image/jpeg');
	header('Content-Length: ' . filesize($cache_image));
	header("Cache-Control: private");
	header('Pragma: ');
	header('Expires: ');
	header('Last-Modified: ' .gmdate("D, d M Y H:i:s", $last_modified).' GMT');
	header('ETag: ' . $etag); 

	//sprawdza za przeglądarke, czy plik w pamięci podręcznej ulegnął zmianie
	if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $last_modified || trim(isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH']) == $etag) 
	{
		header("HTTP/1.1 304 Not Modified");
		exit;
	}

	//wczytuje plik
	readfile($cache_image);

}
?>