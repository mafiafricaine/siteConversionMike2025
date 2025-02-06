<?php

require_once "./app/ActionUserLogin.php";

$action = new ActionUserLogin(
	username: $_POST["username"],
	password: $_POST["password"],
);

// On vérifie que cette action n'est accessible que s'il n'y a pas d'utilisateur
// en session.
$action->anonymousAuth();

// Validation des données du formulaire
//
// S'il y a des erreurs, elles seront envoyées dans la page
// "views/signin.php" inclus plus bas.

$errors = $action->validate();

if ($errors === false) {
	// Tentative de connexion de l'utilisateur.
	$errors = $action->attempt();
}

include "./views/signin.php";
