<?php
if($config['params']['mode'] == 0 || ($config['params']['mode'] == 1 && $_SESSION['logued']!=0)){
	include('template/index.php');
}else{
	echo $locale['main'][0];
}
?>
