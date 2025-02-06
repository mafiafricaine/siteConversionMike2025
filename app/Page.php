<?php

require_once "./app/Auth.php";

class Page
{
	// --------- //
	// Propriété //
	// --------- //

	/**
	 * Classe Auth
	 */
	private Auth $auth;

	/**
	 * Identifiant de page
	 */
	private string $page;

	/**
	 * Titre de la page
	 */
	private string $title;

	// ----------- //
	// Constructor //
	// ----------- //

	public function __construct(
		string $page,
		string $title,
	) {
		$this->auth  = new Auth();
		$this->page  = $page;
		$this->title = $title;
	}

	// --------------- //
	// Getter | Setter //
	// --------------- //

	public function getPage(): string
	{
		return $this->page;
	}

	public function setPage(string $page): void
	{
		$this->page = $page;
	}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function setTitle(string $title): void
	{
		$this->title = $title;
	}

	public function getUserId(): int|null
	{
		return $this->auth->getUserSession() !== null
			? $this->auth->getUserSession()->getId()
			: null;
	}

	public function getUserName(): string|null
	{
		return $this->auth->getUserSession() !== null
			? $this->auth->getUserSession()->getUsername()
			: null;
	}

	public function getUserEmail(): string|null
	{
		return $this->auth->getUserSession() !== null
			? $this->auth->getUserSession()->getEmail()
			: null;
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
