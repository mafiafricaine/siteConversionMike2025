<?php

session_start();

// Détruit toutes les sessions si ?destroy est dans l'URL.
if (isset($_GET["destroy"])) {
	session_destroy();

	// Redirige vers la page de connexion.
	header("Location: ?page=signin");
	exit;
} else {
	var_dump($_SESSION);
}
