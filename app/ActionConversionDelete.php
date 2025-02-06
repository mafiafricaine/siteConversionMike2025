<?php

require_once "./app/Action.php";
require_once "./app/Conversion.php";

class ActionConversionDelete extends Action
{
	// ----------- //
	// Constructor //
	// ----------- //

	/*
		La syntaxe suivante dans les paramètres d'un constructeur :

			public function __construct(private type $property)
			{
			}

		est une alternative à faire ceci :

			private type $property;
			public function __construct(type $property)
			{
				$this->property = $property;
			}
	*/
	public function __construct(
		private Conversion $conversion,
		private int $conversionId,
	)
	{
		parent::__construct();
	}

	// ------- //
	// Méthode // -> API Publique
	// ------- //

	public function delete(): void
	{
		$this->conversion->deleteFromDatabase(
			$this->auth->getUserSession()->getId(),
			$this->conversionId
		);
		$this->auth->redirectProfile();
	}
}
