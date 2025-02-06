<?php

require_once "./app/Action.php";
require_once "./app/Conversion.php";

class ActionConversion extends Action
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

		private float $amount,
		private string $currency_from,
		private string $currency_to,
	)
	{
		parent::__construct();
	}

	// ------- //
	// Méthode // -> API Publique
	// ------- //

	public function validate(): array|bool
	{
		$errors = [];

		if ($this->amount <= 0.0) {
			$errors["amount"] = "The amount cannot be equal to 0";
		}

		$currencies = $this->conversion->getCurrencies();

		if (!in_array(strtoupper($this->currency_from), $currencies)) {
			$errors["currency_from"] =
				"The currency you have entered is invalid. " .
				"Valid currencies are : " . join(",", $currencies);
		}

		if (!in_array(strtoupper($this->currency_to), $currencies)) {
			$errors["currency_to"] =
				"The currency you have entered is invalid. " .
				"Valid currencies are : " . join(",", $currencies);
		}

		if (count($errors) > 0) {
			return $errors;
		}

		return false;
	}

	public function save(): void
	{
		$this->conversion->convert(
			$this->amount,
			strtoupper($this->currency_from),
			strtoupper($this->currency_to),
		);
	}
}
