<?php
//pobiera ścieżkę do pliku
$file_path = $_GET['src'];
$file_path = '../../../_userfiles/files/' . $file_path;

//sprawdza czy plik istnieje
if (file_exists($file_path))
{
	//ustawia nagłówki w celu wymuszenia pobierania
	header('Content-Length: ' . filesize($file_path));
	header('Content-Type: application/force-download');
	header('Content-Disposition: attachment; filename="' . basename($file_path) . '"; modification-date="' . date('r', $mtime) . '";');

	//wczytuje plik
	readfile($file_path);
}

?>