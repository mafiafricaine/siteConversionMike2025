<?php
require_once "./app/PageSignout.php";

$page = new PageSignOut;
$page->requiredAuth();
?>
<?php include "./views/layouts/header.php"; ?>

<link rel="stylesheet" href="assets/css/signin.css">
<link rel="stylesheet" href="assets/css/signout.css">

<div class="auth-login align-t:center">

	<section>
		<p class="align-t:left">
			Hello <strong><?= $page->getUserName(); ?></strong>,
			you are about to log out of your account (<strong><?= $page->getUserEmail(); ?></strong>)
		</p>

		<form action="?action=logout" method="POST">
			<button type="submit" name="send" class="btn">
				<span>Logout now</span>
			</button>
		</form>
	</section>

</div>

<?php include "./views/layouts/footer.php"; ?>
