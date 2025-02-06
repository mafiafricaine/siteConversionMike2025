<?php

require_once "./app/Conversion.php";
require_once "./app/Page.php";

class PageProfile extends Page
{
	private Conversion $conversion;

	public function __construct()
	{
		parent::__construct("profile", "My Profile");
		$this->conversion = new Conversion;
	}

	public function conversionsList(): array
	{
		$userId = $this->getUserId();

		if (!$userId) {
			return [];
		}

		return $this->conversion->all($userId);
	}
}
