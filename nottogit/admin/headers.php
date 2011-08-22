<?php
include('config.php');
include('functions.php');
include('locale/locale_'.$config['locale']['default'].'.php');
include(getLocale());
include('lib_db.php');
?>