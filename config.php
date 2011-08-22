<?php
$config = array(array());

$config['db']['host'] = 'localhost';
$config['db']['user'] = 'johndoe';
$config['db']['pass'] = '';
$config['db']['base'] = 'johndoe';

$config['shortid']['pattern'] = '#^[A-Za-z0-9\-_]{2,8}$#';

$config['locale']['default'] = 'en-en';

$config['params']['proxy'] = true; // If 0 : redirection method will be with mod_rewrite, if 1 it will be with mod_proxy
$config['params']['length']= 5;
$config['params']['multipleaddings']= false; // Allow multiple addings let the user add one token for multiples domains (it implies you have one unique shortened url for all the domains).
$config['params']['infochar'] = '+';
$config['params']['protocol']= 'http';
$config['params']['path']= '/home/louis/www/';
$config['params']['mode'] = 0; // 0 public, 1 private (must be authenticated)
$config['params']['createaccount'] = true; // Allow users to create accounts
$config['params']['allowstats'] = true; // Allow users to access stats (with cookies or auth)
//$config['params']['allowgetpost'] = true; // Allow to shorten urls with GET parameters without auth (Tweetdeck for instance).
//set_include_path($config['params']['path']);
$config['params']['hideip'] = false;
$config['params']['allowcustomshortid'] = true;
$config['params']['defaultservice'] = 1;
$config['params']['defaultcustomid'] = 1;
$config['params']['allowautowebkey'] = true;
?>