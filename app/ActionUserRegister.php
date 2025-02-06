
<?php

require_once "./app/Action.php";

class ActionUserRegister extends Action
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
		private string $email,
		private string $password,
		private string $password_confirm,
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

		if (trim($this->email) === "") {
			$errors["email"] = "The email address MUST NOT be empty";
		} else if (strpos($this->email, '@') == false) {
			$errors["email"] = "The email address MUST CONTAIN the @ character";
		}

		if (strlen($this->password) === 0 || strlen($this->password_confirm) === 0) {
			$errors["password"] = "The password cannot be empty";
			$errors["password_confirm"] = "The password cannot be empty";
		} else if ($this->password !== $this->password_confirm) {
			$errors["password"] = "Both passwords MUST BE the same.";
			$errors["password_confirm"] = "Both passwords MUST BE the same.";
		}

		if (count($errors) > 0) {
			return $errors;
		}

		return false;
	}

	public function save(): array|bool
	{
		if ($this->auth->insert(
			$this->username,
			$this->email,
			password_hash($this->password, PASSWORD_DEFAULT)
		)) {
			$this->auth->redirectSignin();
			return true;
		} else {
			$errors["global"] =
				"An error occurred while registering, " .
				"you cannot register with these credentials, " .
				"they have been banned from our services.";
			return $errors;
		}
	}
}
