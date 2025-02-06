<?php

require_once "./app/Action.php";

class ActionUserLogin extends Action
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
		private string $username,
		private string $password,
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

		if (trim($this->username) === "") {
			$errors["username"] = "Username MUST NOT be empty";
		}

		if (strlen($this->password) === 0) {
			$errors["password"] = "The password cannot be empty";
			$errors["password_confirm"] = "The password cannot be empty";
		}

		if (count($errors) > 0) {
			return $errors;
		}

		return false;
	}

	public function attempt()
	{
		$user = $this->auth->attempt($this->username, $this->password);

		if ($user) {
			return true;
		}

		$errors["global"] = "Unable to log in with these credentials.";

		return $errors;
	}
}
