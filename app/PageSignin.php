<?php

require_once "./app/Page.php";

class PageSignIn extends Page
{
	public function __construct()
	{
		parent::__construct("signin", "Log In");
	}
}
