<?php
include('headers.php');

$libdb = new libDb();
$url = $libdb->getUrl($_GET['id'],$_SERVER);
if(is_numeric($url)){
	if(array_key_exists($url,$locale['redirect'])){
		echo $locale['redirect'][$url];
	}else{
		if(!isset($locale['default'])){
			echo 'Unknown error';
		}else{
			echo $locale['default'];
		}
	}
}else{
	if(!headers_sent()){
		header('Location: '.$url);
	}else{
		echo $locale['redirect'][4]. ' - '.$url;
	}
}
?>