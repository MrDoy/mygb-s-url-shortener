<?php
function getLocale($params='',$path=''){
	if(empty($params)){
		$params = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
	}
	$prefsandlanguages;
	$acceptlanguage = explode(',',$params);
	$i=0;
	$lastpref=-1;
	foreach($acceptlanguage as $languages){
		$language_pref = NULL;
		$language_pref = explode(';',$languages);
		if(preg_match('#^[a-z]{2}[\-a-zA-Z]{0,3}$#',$language_pref[0])){
			$prefsandlanguages[$i]['lang']=$language_pref[0];
			$prefsandlanguages[$i]['pref']=1;
			if(!empty($language_pref[1]) && is_numeric(substr($language_pref[1],2))){
				$prefsandlanguages[$i]['pref']=substr($language_pref[1],2);
			}
			
			if($lastpref==-1 || $prefsandlanguages[$lastpref]['pref']<$prefsandlanguages[$i]['pref']){
				$globreturn = glob($path.'locale/locale_'.strtolower($prefsandlanguages[$i]['lang']).'*.php');
				if(!empty($globreturn)){
					$lastpref = $i;
				}
				//echo 'locale_'.strtolower($prefsandlanguages[$i]['lang']).'*.php';
			}
			$i++;
		}
	}
	if($lastpref==-1){
		include('config.php');
		$locale = $config['locale']['default'];
	}else{
		$locale = strtolower($prefsandlanguages[$lastpref]['lang']);
	}
	$filetoinclude = glob($path.'locale/locale_'.$locale.'*.php');
	return $filetoinclude[0];
}

function createShortId($len=-1){
	
	if($len==-1){
		include('config.php');
		$len = $config['params']['length'];
	}
	
	$shortid = '';
	
	for($i=0;$i<$len;$i++){
		$char = chr(rand(43,122));
		if(preg_match('#[^/\.,:;<>@=`^\\\[\]\?\+]#isU',$char)){
			$shortid.=$char;
		}else{
			$i--;
		}
	}
	return $shortid;
}


?>