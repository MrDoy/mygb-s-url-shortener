<?php
include('../headers.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type='text/javascript' src='https://www.google.com/jsapi'></script>
<title>Admin</title>
</head>

<body>
<?php

if(empty($_GET['page']) || $_GET['page']=='index'){
	include('main.php');
}

?>

</body>
</html>
