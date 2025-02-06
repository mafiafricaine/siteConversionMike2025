<?php

class Conversion
{
	// --------- //
	// Propriété //
	// --------- //

	/**
	 * Accès à PDO, à la base de données.
	 */
	private PDO $database;

	/**
	 * Taux de conversions
	 *
	 * Le format des données de la liste est :
	 *
	 * 		"DEVISE" => taux en entier
	 */
	private array $rates = [
		/*
		'AUD' => 1.6629,
		'BGN' => 1.9558,
		'BRL' => 6.0138,
		'CAD' => 1.4894,
		'CHF' => 0.9396,
		'CNY' => 7.4943,
		'CZK' => 25.172,
		'DKK' => 7.461,
		'EUR' => 1,
		'GBP' => 0.83188,
		'HKD' => 8.0489,
		'HUF' => 407.15,
		'IDR' => 16865,
		'ILS' => 3.6961,
		'INR' => 90.01,
		'ISK' => 146.8,
		'JPY' => 160.52,
		'KRW' => 1504.21,
		'MXN' => 21.132,
		'MYR' => 4.5929,
		'NOK' => 11.721,
		'NZD' => 1.8418,
		'PHP' => 60.222,
		'PLN' => 4.2193,
		'RON' => 4.9769,
		'SEK' => 11.418,
		'SGD' => 1.4015,
		'THB' => 34.937,
		'TRY' => 37.155,
		'USD' => 1.0335,
		'ZAR' => 19.4072,
		*/
	];

	// ----------- //
	// Constructor //
	// ----------- //

	public function __construct()
	{
		$this->database = new PDO('mysql:dbname=tp_currency_converter;host=localhost', "root", "");
		$this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->database->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
	}

	// --------------- //
	// Getter | Setter //
	// --------------- //

	/**
	 * Récupère le taux des devises via une API.
	 */
	public function getRates(): array
	{
		$this->rates = $this->fromAPI("rates", "/v1/latest");
		$this->rates["EUR"] = 1.0;
		return $this->rates;
	}

	/**
	 * Toutes les devices sous forme de liste
	 * Exemple: ["EUR", "USD", "AUD", ...]
	 */
	public function getCurrencies(): array
	{
		return array_keys($this->getRates());
	}

	// ------- //
	// Méthode // -> API Publique
	// ------- //

	/**
	 * Récupère toutes les conversions dans la base de donnée en fonction de
	 * l'ID utilisateur.
	 */
	public function all(int $user_id): array
	{
		$data = [];

		try {
			$req = $this->database->prepare(
				"
				SELECT
					id,
					amount,
					currency_from AS 'from',
					currency_to   AS 'to'
				FROM conversions
				WHERE user_id = :user_id
				"
			);

			$req->execute(["user_id" => $user_id]);


			foreach ($req->fetchAll(PDO::FETCH_ASSOC) as $item) {
				// Les résultats ne sont pas sauvegardés en base de données.
				$item["result"] = $this->convert(
					$item["amount"],
					$item["from"],
					$item["to"],
					save: false,
				);

				$data[] = $item;
			}
		} catch (PDOException $error) { }

		return $data;
	}

	/**
	 * Converti un montant d'une devise à une autre.
	 */
	public function convert(
		float $amount,
		string $from,
		string $to,
		bool $save = true,
	): array
	{
		$converted1 = $this->fromAPI(
			$amount . "_" . $from . "_" . $to,
			"/v1/latest?amount=$amount&base=$from&symbols=$to"
		)[$to];

		$converted2 = $this->fromAPI(
			$amount . "_" . $to . "_" . $from,
			"/v1/latest?amount=$amount&base=$to&symbols=$from"
		)[$from];

		if ($save) {
			$this->saveToSession(
				$amount,
				$from,
				$to,
				[$converted1, $converted2],
			);

			$this->saveToDatabase(
				$amount,
				$from,
				$to,
				[$converted1, $converted2],
			);
		}

		return [$converted1, $converted2];
	}

	/**
	 * Supprime une conversion en base de données en fonction de son ID.
	 */
	public function deleteFromDatabase(int $user_id, int $conversion_id): bool
	{
		try {
			$req = $this->database->prepare("
				DELETE FROM conversions
				WHERE id      = :conversion_id
				  AND user_id = :user_id
			");

			return $req->execute([
				"conversion_id" => $conversion_id,
				"user_id"       => $user_id,
			]);
		} catch (PDOException $error) {
			return false;
		}
	}

	// ------- //
	// Méthode // -> Privée
	// ------- //

	/**
	 * Ajoute une conversion en base de données
	 */
	private function saveToDatabase(
		float $amount,
		string $from,
		string $to,
		array $result
	): bool
	{
		try {
			$req = $this->database->prepare("
				INSERT INTO conversions (
					amount,
					currency_from,
					currency_to,
					user_id
				) VALUES (
					:amount,
					:currency_from,
					:currency_to,
					:user_id
				)
			");

			return $req->execute([
				"amount" 		=> $amount,
				"currency_from" => $from,
				"currency_to" 	=> $to,
				"user_id" 		=> $_SESSION["user"]->getId(),
			]);
		} catch (PDOException $error) {
			return false;
		}
	}

	/**
	 * Sauvegarde une conversion en session.
	 */
	private function saveToSession(
		float $amount,
		string $from,
		string $to,
		array $result
	): void
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}

		$_SESSION["currency_result"][] = [
			"amount" => $amount,
			"from" 	 => $from,
			"to"     => $to,
			"result" => $result,
		];
	}

	private function fromAPI(string $name, string $path): mixed
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}

		if (isset($_SESSION[$name])) {
			return $_SESSION[$name];
		}

		$url = "https://api.frankfurter.dev" . $path;
		// NOTE: n'est pas une bonne méthode de récupération des données
		// 		 j'ai choisi cette option par facilité.
		$result = file_get_contents($url);

		$_SESSION[$name] = json_decode($result, true)["rates"];

		return $_SESSION[$name];
	}
}
