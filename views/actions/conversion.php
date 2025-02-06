<?php

require_once "./app/ActionConversion.php";
require_once "./app/Conversion.php";

$action = new ActionConversion(
	conversion: new Conversion,
	amount: (float) $_POST["amount"],
	currency_from: $_POST["currency_from"],
	currency_to: $_POST["currency_to"],
);

// On vérifie que cette action n'est accessible que s'il y a un utilisateur en
// session.
$action->requiredAuth();

// Validation des données du formulaire
//
// S'il y a des erreurs, elles seront envoyées dans la page
// "views/conversion.php" inclus plus bas.
$errors = $action->validate();

if ($errors === false) {
	// Sauvegarde les données dans la base de données et
	// dans la session, parce qu'à la base, c'est ça le but.
	$action->save();
}

require "./views/conversion.php";
