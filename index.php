<?php

// Say m'a inspiré, j'ai utilisé le même principe que lui pour les pages, qui
// est le suivant :
//
// J'inclus une page ".php" dynamiquement avec require, en fonction d'un
// paramètre d'URL et en fonction du verbe HTTP (GET ou POST).

switch ($_SERVER["REQUEST_METHOD"]):
	// 1. Lorsqu'on accède à une page, le verbe HTTP est GET par défaut.
	//
	// EXAMPLE: http://localhost/?page=nom-de-page
	//
	// 2. Lorsqu'un un formulaire est envoyé via la méthode GET:
	//
	// EXAMPLE: <form action="" method="GET">
	// 				<input type="hidden" name="page" value="nom-de-page">
	// 			</form>
	case "GET":
	{
		// Récupération du paramètre d'URL `?page=<nom-de-page>` où
		// <nom-de-page> DOIT être un nom de fichier existant dans le dossier
		// `views/`. Si ce fichier n'est pas existant, une page d'erreur est
		// retournée.
		//
		// Le paramètre d'URL PEUT NE PAS être existant dans l'URL, si c'est le
		// cas, je veux afficher une page par défaut, ici la page de connexion
		// "signin".

		$pageParam = isset($_GET["page"])
			? $_GET["page"]
			: "signin";

		if (file_exists("views/$pageParam.php")):
			include "views/$pageParam.php";
		else:
			include "views/errors/404_notfound.php";
		endif;
	} break;

	// 1. Lorsqu'un un formulaire est envoyé via la méthode POST:
	//
	// EXAMPLE: <form action="?action=nom-action" method="POST">
	case "POST":
	{
		// Récupération du paramètre d'URL `?action=<nom-action>` où
		// <nom-action> DOIT être un nom de fichier existant dans le dossier
		// `views/actions/`. Si ce fichier n'est pas existant, une page d'erreur
		// est retournée.
		//
		// Le paramètre d'URL PEUT NE PAS être existant dans l'URL, si c'est le
		// cas, je veux afficher une page par défaut, ici la page d'erreur.

		$actionParam = isset($_GET["action"]) ? $_GET["action"] : null;

		if ($actionParam && file_exists("views/actions/$actionParam.php")):
			include "views/actions/$actionParam.php";
		else:
			include "views/errors/404_notfound.php";
		endif;
	} break;
endswitch;
