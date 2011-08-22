<?php
include('headers.php');

function checkRequest(){
	if(empty($_GET['key'])){
		return 5;
	}else{
		$key = htmlspecialchars($_GET['key']);
	}
	if(empty($_GET['url'])){
		return 6;
	}else{
		$url = htmlspecialchars(urldecode($_GET['url']));
	}
	if(empty($_GET['client'])){
		$client = '';
	}else{
		$client = htmlspecialchars($_GET['client']);
	}
	if(empty($_GET['shortid'])){
		$shortid = '';
	}else{
		$shortid = htmlspecialchars($_GET['shortid']);
	}
	return array($key,$url,$client,$shortid);
}

$checkResult = checkRequest();

if(is_array($checkResult)){
	$libdb = new libDb();
	$result = $libdb->addUrl($checkResult[0],$checkResult[1],$checkResult[2],$checkResult[3]);
	
	if(is_numeric($result) && $result!=-1){
		if(array_key_exists($result,$locale['action'])){
			echo $locale['action'][$result];
		}else{
			if(!isset($locale['default'])){
				echo 'Unknown error';
			}else{
				echo $locale['default'];
			}
		}
	}
}else{
	if(array_key_exists($checkResult,$locale['action'])){
			echo $locale['action'][$checkResult];
	}else{
		if(!isset($locale['default'])){
			echo 'Unknown error';
		}else{
			echo $locale['default'];
		}
	}
}

?>