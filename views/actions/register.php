<?php

require_once "./app/ActionUserRegister.php";

$action = new ActionUserRegister(
	username: $_POST["username"],
	email: $_POST["email"],
	password: $_POST["password"],
	password_confirm: $_POST["password_confirm"],
);

// On vérifie que cette action n'est accessible que s'il n'y a pas d'utilisateur
// en session.
$action->anonymousAuth();

// Validation des données du formulaire
//
// S'il y a des erreurs, elles seront envoyées dans la page
// "views/signup.php" inclus plus bas, grâce à la variable $errors.

$errors = $action->validate();

if ($errors === false) {
	// Sauvegarde l'utilisateur dans la base de données.
	//
	// S'il y a des erreurs, elles seront envoyées dans la page
	// "views/signup.php" inclus plus bas, grâce à la variable $errors.
	$errors = $action->save();
}

include "./views/signup.php";
