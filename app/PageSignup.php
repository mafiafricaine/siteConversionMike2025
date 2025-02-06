<?php

require_once "./app/Page.php";

class PageSignUp extends Page
{
	public function __construct()
	{
		parent::__construct("signup", "Registration");
	}
}
