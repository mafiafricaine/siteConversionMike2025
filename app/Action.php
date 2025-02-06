<?php

require_once "./app/Auth.php";

class Action
{
	// --------- //
	// Propriété //
	// --------- //

	protected Auth $auth;

	// ----------- //
	// Constructor //
	// ----------- //

	public function __construct()
	{
		$this->auth = new Auth;
	}

	// ------- //
	// Méthode // -> API Publique
	// ------- //

	/**
	 * L'accès à la page est anonyme, ça veut dire
	 * qu'il n'y a pas besoin de se connecter.
	 */
	public function anonymousAuth(): void
	{
		if ($this->auth->isConnected()) {
			$this->auth->redirectProfile();
		}
	}

	/**
	 * L'accès à la page est requise.
	 */
	public function requiredAuth(): void
	{
		if (!$this->auth->isConnected()) {
			$this->auth->redirectSignin();
		}
	}
}
