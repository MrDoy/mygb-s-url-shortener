<?php
$locale['default'] = 'Unknown error';

$locale['redirect'][0] = 'This id doesn\'t exists';
$locale['redirect'][1] = 'Error with database connection';
$locale['redirect'][2] = 'Please give a valid id';
$locale['redirect'][3] = 'Something went wrong passing server variables';
$locale['redirect'][4] = 'Headers already sent';

$locale['action'][0] = 'API key is not valid';
$locale['action'][1] = 'URL is not valid';
$locale['action'][2] = 'Client ID is not valid';
$locale['action'][3] = 'Shortid not valid';
$locale['action'][4] = 'You are not allowed to shorten URls. Please login.';
$locale['action'][5] = 'No key';
$locale['action'][6] = 'No url';

$locale['list'][0] = 'You don\'t have the permission to access this content';
$locale['main'][0] = 'This is a private shortener, please login';

$locale['index'][0] = 'Home';
$locale['index'][1] = 'Shortened urls';
$locale['index'][2] = 'Tokens';
if($config['params']['createaccount'] == true){
	$locale['index'][3] = 'Login/Register';
}else{
	$locale['index'][3] = 'Login';
}
$locale['index'][4] = 'About';
$locale['index'][6] = 'Disconnect';

$locale['index'][5] = 'Custom short url';

$locale['auth'][0] = 'Login';
$locale['auth'][1] = 'Register';
$locale['auth'][2] = 'username';
$locale['auth'][3] = 'Wrong email';
$locale['auth'][4] = 'A user already exists with this email or username';
$locale['auth'][5] = 'Return to home page';
$locale['auth'][6] = 'Wrong login';
?>