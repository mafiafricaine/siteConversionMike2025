<?php

require_once "./app/Auth.php";

// On vérifie que cette action n'est accessible que s'il y a un utilisateur
// en session.

$auth = new Auth;

if (!$auth->isConnected()) {
	// Dans le cas contraire, on redirige vers la page de connexion.
	$auth->redirectSignin();
}

// Déconnecte l'utilisateur.
$auth->logout();
