<?php
$locale['default'] = 'Erreur inconnue';

$locale['redirect'][0] = 'Cet id n\'existe pas';
$locale['redirect'][1] = 'Erreur avec la connexion à la base de données';
$locale['redirect'][2] = 'Merci de fournir un id valide';
$locale['redirect'][3] = 'Quelque chose a mal tourné lors du transfère de variables';
$locale['redirect'][4] = 'En-têtes déjà envoyées';

$locale['action'][0] = 'La clé n\'est pas valide';
$locale['action'][1] = 'L\'url n\'est pas valide';
$locale['action'][2] = 'L\'id client n\'est pas valide';
$locale['action'][3] = 'Le shortid n\'est pas valide';
$locale['action'][4] = 'Veuillez vous loguer pour raccourcir des urls';
$locale['action'][5] = 'Aucune clé';
$locale['action'][6] = 'Aucune url';

$locale['list'][0] = 'Vous n\'avez pas la permission d\'accéder à ce contenu';
$locale['main'][0] = 'C\'est un racourcisseur d\'url privé, merci de vous loguer';

$locale['index'][0] = 'Accueil';
$locale['index'][1] = 'Urls raccourcies';
$locale['index'][2] = 'Clés';
if($config['params']['createaccount'] == true){
	$locale['index'][3] = 'Se loguer/S\'inscrire';
}else{
	$locale['index'][3] = 'Se loguer';
}
$locale['index'][4] = 'A propos';
$locale['index'][6] = 'Déconnexion';

$locale['index'][5] = 'Url raccourcie personnalisée';

$locale['auth'][0] = 'Loguer';
$locale['auth'][1] = 'Enregistrer';
$locale['auth'][2] = 'nom d\'utilisateur';
$locale['auth'][3] = 'Email invalide';
$locale['auth'][4] = 'Il existe déjà un utilisateur avec ce nom ou cet email';
$locale['auth'][5] = 'Retourner à la page principale';
$locale['auth'][6] = 'Mauvais login';
?>