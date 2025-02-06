<?php

function error(
	array|bool $errors,
	string $name,
): string|null
{
	if (is_array($errors) && isset($errors[$name])) {
		return "<span class='error-message'>" . $errors[$name] . "</span>";
	}
	return null;
}
