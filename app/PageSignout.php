<?php

require_once "./app/Page.php";

class PageSignOut extends Page
{
	public function __construct()
	{
		parent::__construct("signout", "Log Out");
	}
}
