<?php
session_start();
include('config.php');
include('functions.php');
include('locale/locale_'.$config['locale']['default'].'.php');
include(getLocale('',$config['params']['path']));
include('lib_db.php');
?>