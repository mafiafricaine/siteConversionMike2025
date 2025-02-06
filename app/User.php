<?php

class User
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
		private int $id,
	) {}

	// --------------- //
	// Getter | Setter //
	// --------------- //

	public function getUsername(): string
	{
		return $this->username;
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function getPassword(): string
	{
		return $this->password;
	}

	public function getId(): int
	{
		return $this->id;
	}
}
