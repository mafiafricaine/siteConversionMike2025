<?php
require_once "./app/PageProfile.php";

$page = new PageProfile;
$page->requiredAuth();
?>
<?php include "./views/layouts/header.php"; ?>

<link rel="stylesheet" href="assets/css/profile.css">

<div class="page-profile">
	<h1>Profile</h1>

	<section>
		<h2>History of your currency conversion</h2>

		<div class="currency-conversion-history">
			<table>
				<thead>
					<tr>
						<th>Amount</th>
						<th>Currency from</th>
						<th>Currency to</th>
						<th>Result</th>
						<th>Actions</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($page->conversionsList() as $conversion): ?>
						<tr>
							<td><?= $conversion["amount"]; ?></td>
							<td><?= $conversion["from"]; ?></td>
							<td><?= $conversion["to"]; ?></td>
							<td><?= $conversion["result"][0]; ?></td>

							<td>
								<form action="?action=conversion_delete" method="POST">
									<input type="hidden" name="conversion_id" value="<?= $conversion["id"]; ?>">
									<button type="submit">Delete</button>
								</form>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</section>

</div>

<?php include "./views/layouts/footer.php"; ?>
