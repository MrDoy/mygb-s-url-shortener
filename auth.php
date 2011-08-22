<?php
if(!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['email']) && $config['params']['createaccount'] == true){
	$libdb = new libDb();
	$return = $libdb->addUser($_POST['username'],$_POST['password'],$_POST['email']);
}elseif(!empty($_POST['username']) && !empty($_POST['password'])){
	$libdb = new libDb();
	$return = $libdb->verifyLogin($_POST['username'],$_POST['password']);
}

if(isset($return) && is_numeric($return) && $return !=-1){
	echo '<p>';
	if(array_key_exists($return,$locale['auth'])){
		echo $locale['auth'][$return];
	}else{
		if(!isset($locale['default'])){
			echo 'Unknown error';
		}else{
			echo $locale['default'];
		}
	}
	echo '</p>';
}

if($_SESSION['logued']==0){
?>

<h2><?php echo $locale['auth'][0] ?></h2>
<form method="post" action="auth.html">
	<input type="text" name="username" value="<?php echo $locale['auth'][2]?>"/>
    <input type="password" name="password" value="password"/>
    <button type="submit">Submit</button>
</form>
<?php 
if($config['params']['createaccount'] == true){
	echo '<h2>'.$locale['auth'][1].'</h2>';
?>
<form method="post" action="auth.html">
	<input type="text" name="username" value="<?php echo $locale['auth'][2]?>"/>
    <input type="text" name="email" value="email@example.com"/>
    <input type="password" name="password" value="password"/>
    <button type="submit">Submit</button>
</form>
<?php
}

}else{
	echo '<p><a href="index.html">'.$locale['auth'][5].'</a></p>';
}

?>