<?php

require_once "./app/User.php";

class Auth
{
	// --------- //
	// Propriété //
	// --------- //

	/**
	 * Accès à PDO, à la base de données.
	 */
	private PDO $database;

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

	public function getUserSession(): User|null
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
		return isset($_SESSION["user"]) ? $_SESSION["user"] : null;
	}

	private function setUserSession(User $user): void
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
		$_SESSION["user"] = $user;
	}

	private function unsetUserSession(): void
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
		unset($_SESSION["currency_result"]);
		unset($_SESSION["user"]);
	}

	// ------- //
	// Méthode // -> API Publique
	// ------- //

	/**
	 * Tentative de connexion
	 */
	public function attempt(string $username, string $password): User|null
	{
		$user = $this->findByUsername($username);

		// Utilisateur non trouvé
		if (!$user) {
			return null;
		}

		// Utilisateur trouvé
		// Les mots de passes ne correspondent pas
		if (!password_verify($password, $user->getPassword())) {
			return null;
		}

		// Tout est ok.

		$this->setUserSession($user);

		return $user;
	}

	/**
	 * Cherche un utilisateur dans la base de données en fonction d'un pseudo.
	 */
	public function findByUsername(string $username): User|null
	{
		try {
			$req = $this->database->prepare("SELECT * FROM users WHERE username = :username LIMIT 1", []);
			$req->execute(["username" => $username]);
			$data = $req->fetch();
			if (!$data) {
				return null;
			}
			$user = new User($data->username, $data->email, $data->password, $data->id);
			return $user;
		} catch (PDOException $e) {
			return null;
		}
	}

	public function insert(
		string $username,
		string $email,
		string $password,
	): bool
	{
		try {
			$req = $this->database->prepare("
				INSERT INTO users  (  username, email, password )
							VALUES ( :username,:email,:password )
			");

			return $req->execute([
				"username" => $username,
				"email"    => $email,
				"password" => $password,
			]);
		} catch (PDOException $e) {
			return false;
		}
	}

	public function isConnected(): bool
	{
		return $this->getUserSession() !== null && !empty($this->getUserSession()->getId());
	}

	public function logout(): void
	{
		$this->unsetUserSession();
		$this->redirectSignin();
	}

	public function redirectProfile(): void
	{
		header("Location: ?page=profile");
		exit;
	}

	public function redirectSignin(): void
	{
		header("Location: ?page=signin");
		exit;
	}
}
