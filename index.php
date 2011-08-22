<?php
include('headers.php');

if(!isset($_SESSION['publicid'])){
	$_SESSION['publicid']=0;
}

if(!isset($_SESSION['logued'])){
	$_SESSION['logued']=0;
}
//$_SESSION['logued']=0;
//echo $_SESSION['logued'].' - '.$_SESSION['publicid'];
?>
<!DOCTYPE html>
<html>
 <head>
   <title>My URL Shortener</title>
   <link type="text/css" rel="stylesheet" href="style.css"/>
 </head>
 <body>
 	<div id="containerforfooter">
    <header>
   		<nav>
        	<ul>
            	<li><a href="index.html"><?php echo $locale['index'][0];?></a></li>
                <li><a href="infos.html"><?php echo $locale['index'][1];?></a></li>
                <li><a href="tokens.html"><?php echo $locale['index'][2];?></a></li>
                <?php
				if($_SESSION['logued']==0){
				?>
                <li><a href="auth.html"><?php echo $locale['index'][3];?></a></li>
                <?php
				}else{
				?>
                <li><a href="disconnect.html"><?php echo $locale['index'][6];?></a></li>
                <?php
				}
                ?>
                <li><a href="about.html"><?php echo $locale['index'][4];?></a></li>
            </ul>
        </nav>
	</header>
    <section id="bodysec">
<?php

if(empty($_GET['page']) || $_GET['page']=='index'){
	include('main.php');
}elseif($_GET['page']=='plus'){
	include('plus.php');
}elseif($_GET['page']=='infos'){
	include('infos.php');
}elseif($_GET['page']=='sendnojs'){
	echo '<div id="page">';
	include('infos.php');
	echo '</div>';
}elseif($_GET['page']=='auth'){
	echo '<div id="page">';
	include('auth.php');
	echo '</div>';
}elseif($_GET['page']=='about'){
	echo '<div id="page">';
	include('template/about.php');
	echo '</div>';
}elseif($_GET['page']=='disconnect'){
	$_SESSION['logued']=0;
	echo '<div id="page">';
	include('auth.php');
	echo '</div>';
}

?>
    </section>
    </div>
    <footer>
        <section>
            <p>Urlshortener Script by <a href="http://www.mygb.eu">mygb</a> | <img src="html5logo.svg" alt="HTML5 Compliant" width="48px"/></p>
        </section>
	</footer>
 </body>
</html>
