<?php

require_once "./app/Conversion.php";
require_once "./app/Page.php";

class PageConversion extends Page
{
	// --------- //
	// Propriété //
	// --------- //

	private Conversion $conversion;

	// ----------- //
	// Constructor //
	// ----------- //

	public function __construct()
	{
		parent::__construct("conversion", "Mikonvertika");
		$this->conversion = new Conversion();
	}

	// ------- //
	// Méthode // -> API Publique
	// ------- //

	public function currenciesList(): array
	{
		return $this->conversion->getCurrencies();
	}

	public function lastConversion(): array|null
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
		if (!isset($_SESSION["currency_result"])) {
			return null;
		}
		return end($_SESSION["currency_result"]);
	}
}
