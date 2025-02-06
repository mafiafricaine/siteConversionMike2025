<?php
require_once "./app/PageSignup.php";

$page = new PageSignUp;
$page->anonymousAuth();
?>
<?php include "./views/layouts/header.php"; ?>

<link rel="stylesheet" href="assets/css/signin.css">

<div class="auth-login align-t:center">
	<h1>Registration</h1>

	<p>Please enter your info to sign up to Mikonvertika</p>

	<ul>
		<li class="btn">
			<a href="#">
				<?php include "./assets/svg/google.svg" ?>
			</a>
		</li>

		<li class="btn">
			<a href="#">
				<?php include "./assets/svg/facebook.svg" ?>
			</a>
		</li>

		<li class="btn">
			<a href="#">
				<?php include "./assets/svg/apple.svg" ?>
			</a>
		</li>
	</ul>

	<hr>

	<form action="?action=register" method="post">
		<div class="form-group align-t:left">
			<label for="username">Username</label>
			<input id="username" type="text" name="username" placeholder="JohnDoe">
			<?= isset($errors) ? error($errors, "username") : null ?>
		</div>

		<div class="form-group align-t:left">
			<label for="email">Email Address</label>
			<input id="email" type="email" name="email" placeholder="john.doe@example.org">
			<?= isset($errors) ? error($errors, "email") : null ?>
		</div>

		<div class="form-group align-t:left">
			<label for="password">Password</label>
			<input id="password" type="password" name="password" placeholder="Secret555">
			<?= isset($errors) ? error($errors, "password") : null ?>
		</div>

		<div class="form-group align-t:left">
			<label for="password_confirm">Password Confirmation</label>
			<input id="password_confirm" type="password" name="password_confirm" placeholder="Secret555">
			<?= isset($errors) ? error($errors, "password_confirm") : null ?>
		</div>

		<button type="submit" class="btn">
			<span>Register</span>
		</button>
	</form>

	<p>
		You have already an account ?
		<a href="?page=signin">Sign in</a>
	</p>
</div>

<?php include "./views/layouts/footer.php"; ?>
