<?php
$libdb = new libDb();

if(empty($_GET['id'])){
	$shortlist = $libdb->listUrl();
}else{
	$id = mysql_escape_string($_GET['id']);
	$shortlist = $libdb->listStats($id,$_SERVER['HTTP_HOST']);
	
	echo '<h1>'.$config['params']['protocol'].'://'.$shortlist[0]['host'].'/'.$shortlist[0]['shortid'].'</h1>';
}

if(is_numeric($shortlist)){
	if(array_key_exists($shortlist,$locale['list'])){
		echo $locale['list'][$shortlist];
	}else{
		if(!isset($locale['default'])){
			echo 'Unknown error';
		}else{
			echo $locale['default'];
		}
	}
}

include('template/list.php');
		

