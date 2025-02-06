<?php

require_once "./app/PageConversion.php";

$page = new PageConversion;
$page->requiredAuth();

$lastConversion = $page->lastConversion();

include "./views/layouts/header.php";
?>

<link rel="stylesheet" href="assets/css/conversion.css">

<dialog id="currency-converter" open>
	<h1><?= $page->getTitle() ?></h1>

	<form action="?action=conversion" method="POST">
		<div class="form-group">
			<label for="js-amount">Enter amount</label>

			<input
				id="js-amount"
				name="amount"
				type="number"
				min="0"
				step="0.1"

				<?php if (isset($_POST["amount"])): ?>
					value="<?php echo (float) $_POST["amount"] ?>"
				<?php endif ?>
			>
			<?= isset($errors) ? error($errors, "amount") : null ?>
		</div>

		<div class="form-group-inline">
			<div class="form-group">
				<label for="js-curr-from">From</label>

				<select name="currency_from" id="js-curr-from">
					<?php foreach ($page->currenciesList() as $currency) : ?>
						<option
							value="<?php echo strtolower($currency) ?>"
							<?php
							if (
								isset($_POST["currency_from"])      &&
								strtoupper($_POST["currency_from"]) == strtoupper($currency)
							):
							?>
								selected="selected"
							<?php
							elseif (
								!isset($_POST["currency_from"]) &&
								"EUR" == strtoupper($currency)
							):
							?>
								selected="selected"
							<?php endif ?>
						>
							<?php echo $currency ?>
						</option>
					<?php endforeach ?>
				</select>
				<?= isset($errors) ? error($errors, "currency_from") : null ?>
			</div>

			<div>
				<?php include "./assets/svg/conversion.svg" ?>
			</div>

			<div class="form-group">
				<label for="js-curr-to">To</label>

				<select name="currency_to" id="js-curr-to">
					<?php foreach ($page->currenciesList() as $currency) : ?>
						<option
							value="<?php echo strtolower($currency) ?>"
							<?php
							if (
								isset($_POST["currency_to"])      &&
								strtoupper($_POST["currency_to"]) == strtoupper($currency)
							):
							?>
								selected="selected"
							<?php endif ?>
						>
							<?php echo $currency ?>
						</option>
					<?php endforeach ?>
				</select>

				<?= isset($errors) ? error($errors, "currency_to") : null ?>
			</div>
		</div>

		<button type="submit">Convert now</button>
	</form>

	<?php if ($lastConversion !== null): ?>
		<hr>

		<details open>
			<summary>Last conversion</summary>

			<ul>
				<li>
					<strong>
						<?= $lastConversion["amount"] ?> (<?= $lastConversion["from"] ?>)
					</strong>

					equals

					<strong>
						<?= $lastConversion["result"][0] ?> (<?= $lastConversion["to"] ?>)
					</strong>
				</li>

				<li>
					<strong>
						<?= $lastConversion["amount"] ?> (<?= $lastConversion["to"] ?>)
					</strong>

					equals

					<strong>
						<?= $lastConversion["result"][1] ?> (<?= $lastConversion["from"] ?>)
					</strong>
				</li>
			</ul>
		</details>
	<?php endif ?>

</dialog>

<?php include "./views/layouts/footer.php" ?>
