<p>
<?php
if(empty($_GET['page']) || $_GET['page']==1){
?>
<h1>Installation</h1>
This is the install script for <a href="http://www.mygb.eu">mygb</a>'s url shortener.<br/>
Please follow carefully the instructions.<br/>
During the next minutes, the configuration file will be created. You'll still be able to edit parameters in config.php.<br/><br/>

<a href="install.php?page=2">Next step</a>
<?php
}else{
	echo '<h1>Installation - step '.htmlentities($_GET['page']).'</h1>';
	if($_GET['page']==2){
		
		echo '<form action="install.php?page=3" method="post">';
?>
<label>Databse host : <input type="text" name="db_host"/></label><br/>
<label>Databse user : <input type="text" name="db_user"/></label><br/>
<label>Databse pass : <input type="text" name="db_pass"/></label><br/>
<label>Databse base : <input type="text" name="db_base"/></label><br/><br/>

<label>Shorting domain : <input type="text" name="domain" value="http://sh.rt"/></label><br/><br/>

Redirect method (mod_proxy or mod_rewrite)
<label><input type="radio" name="redirect_method" value="false" checked="checked"/>Mod rewrite</label>
<label><input type="radio" name="redirect_method" value="true"/>Mod proxy</label><br/><br/>

<label><input type="checkbox" name="private"/>Private service (user must be authenticated to shorten urls)</label><br/>
<label><input type="checkbox" name="createaccount"/>Let user create their accounts</label><br/>
<label><input type="checkbox" name="allowstats"/>Allow stats </label><br/>
<label><input type="checkbox" name="hideip" checked="checked"/>Hide IP (very recommended if public) </label><br/>
<label><input type="checkbox" name="allowcustomshortid"/>Allow custom short id</label><br/>
<label><input type="checkbox" name="allowautowebkey" checked="checked"/>Allow web key (users can use main web page to shorten url</label><br/><br/>

<input type="submit"/>

</form>
<?php
	//echo '<a href="install.php?page='.($_GET['page']+1).'">Next step</a>';
	}elseif($_GET['page']==3){
		?>
        Creating config file...
        <?php
		
		$configfile = fopen('config.php','w');
		fwrite($configfile,'<?php
$config[\'shortid\'][\'pattern\'] = \'#^[A-Za-z0-9\-_]{2,8}$#\';
$config[\'params\'][\'length\']= 5;
$config[\'params\'][\'infochar\'] = \'+\';
$config[\'params\'][\'multipleaddings\']= false;
$config[\'locale\'][\'default\'] = \'en-en\';
$config[\'params\'][\'protocol\']= \'http\';
$config[\'params\'][\'defaultservice\'] = 1;
$config[\'params\'][\'defaultcustomid\'] = 1;
$config[\'params\'][\'path\'] = \''.realpath('.').'/\';
');

		foreach($_POST as $key=>$value){
			if(preg_match('#^db_#',$key)){
				$db_values = explode('_',$key);
				fwrite($configfile,'$config[\'db\'][\''.$db_values[1].'\']=\''.$value.'\';
');
			}elseif($key=='redirect_method'){
				fwrite($configfile,'$config[\'params\'][\'proxy\']='.$value.';
');
			}
		}
		
		if(isset($_POST['private'])){
				fwrite($configfile,'$config[\'params\'][\'mode\']=1;
');
		}else{
				fwrite($configfile,'$config[\'params\'][\'mode\']=0;
');
		}
		if(isset($_POST['createaccount'])){
				fwrite($configfile,'$config[\'params\'][\'createaccount\']=true;
');
		}else{
				fwrite($configfile,'$config[\'params\'][\'createaccount\']=false;
');
		}
		if(isset($_POST['allowstats'])){
				fwrite($configfile,'$config[\'params\'][\'allowstats\']=true;
');
		}else{
				fwrite($configfile,'$config[\'params\'][\'allowstats\']=false;
');
		}
		if(isset($_POST['hideip'])){
				fwrite($configfile,'$config[\'params\'][\'hideip\']=true;
');
		}else{
				fwrite($configfile,'$config[\'params\'][\'hideip\']=false;
');
		}
		if(isset($_POST['allowcustomshortid'])){
				fwrite($configfile,'$config[\'params\'][\'allowcustomshortid\']=true;
');
		}else{
				fwrite($configfile,'$config[\'params\'][\'allowcustomshortid\']=false;
');
		}
		if(isset($_POST['allowautowebkey'])){
				fwrite($configfile,'$config[\'params\'][\'allowautowebkey\']=true;
');
		}else{
				fwrite($configfile,'$config[\'params\'][\'allowautowebkey\']=false;
');
		}
		fwrite($configfile,'?>');
		fclose($configfile);
		$htaccessfile = fopen('.htaccess','w');
		fwrite($htaccessfile,'
RewriteEngine On

RewriteRule ^([A-Za-z0-9]+)\.html$ index.php?page=$1

RewriteCond %{HTTP_HOST} ^'.$_POST['domain'].'$
RewriteRule ^([A-Za-z0-9_\-]{2,8})$ '.$_POST['domain'].'/redirect.php?id=$1
RewriteRule ^([A-Za-z0-9_\-]{2,8})\+$ index.php?page=infos&id=$1
RewriteRule ^([A-Za-z0-9_\-]{2,8})\+\+$ index.php?page=infos&id=$1&format=raw');

		if($_POST['redirect_method']==true){
			fwrite($htaccessfile,' [P]
');
		}
		fwrite($htaccessfile,'

RewriteRule (^[A-Za-z0-9\-_]+\.(png|gif|svg|jpg|ico)$) template/images/$1
RewriteRule (^[A-Za-z0-9\-_]+\.css$) template/styles/$1
');
		fclose($htaccessfile);
		
		mysql_connect($_POST['db_host'],$_POST['db_user'],$_POST['db_pass']);
		mysql_select_db($_POST['db_base']);
		
		if(isset($_POST['allowcustomshortid'])){
			$customshortid=1;
		}else{
			$customshortid=0;
		}
		
		mysql_query(
'CREATE TABLE IF NOT EXISTS `idtourl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyid` int(11) NOT NULL,
  `shortid` text NOT NULL,
  `url` text NOT NULL,
  `client` text NOT NULL,
  `ip` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `state` int(11) NOT NULL,
  `publicowner` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=629 ;

CREATE TABLE IF NOT EXISTS `keyapi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner` int(11) NOT NULL,
  `service` int(11) NOT NULL,
  `keytext` text NOT NULL,
  `restriction` text NOT NULL,
  `allowcustomid` tinyint(1) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `state` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

CREATE TABLE IF NOT EXISTS `owner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sessionid` text NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `email` text NOT NULL,
  `loginip` text NOT NULL,
  `registerip` text NOT NULL,
  `lastlogin` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

CREATE TABLE IF NOT EXISTS `service` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `host` text NOT NULL,
  `length` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

INSERT INTO `service` (`id`, `host`, `length`) VALUES
(1, \''.preg_replace('#https?:\/\/#','',$_POST['domain']).'\', 5);
INSERT INTO `owner` (`id`, `username`, `password`, `email`, `loginip`, `registerip`, `lastlogin`) VALUES
(1, \'test\', \'55c3b5386c486feb662a0785f340938f518d547f\', \'email@example.com\', "'.$_SERVER['REMOTE_ADDR'].'", "'.$_SERVER['REMOTE_ADDR'].'", NOW());
INSERT INTO `keyapi` (`id`, `owner`, `service`, `keytext`, `allowcustomid`, `date`, `state`) VALUES
(1, 1, 1, \"myfirstkey\", '.$customshortid.', NOW(), 0);


');


	}
}

?>
</p>