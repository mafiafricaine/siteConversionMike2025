<?php

require_once "./app/ActionConversionDelete.php";
require_once "./app/Conversion.php";

$action = new ActionConversionDelete(
	conversion: new Conversion,
	conversionId: (int) $_POST["conversion_id"],
);

// On vérifie que cette action n'est accessible que s'il y a un utilisateur en
// session.
$action->requiredAuth();

// Supprime la conversion dans la base de données.
$action->delete();
