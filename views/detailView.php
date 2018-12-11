<?php

include('includes/header.php');

?>

<div class="container">

	<header class="flex">
		<p class="margin-right">Bienvenue sur l'application Comptes Bancaires <?= $user->getName() ?></p>
	</header>

	<h1>Mon application bancaire</h1>
	<?php if ($message) { ?>
		<p class="text-center bg-danger text-white"><?= $message ?></p>
	<?php } ?>

	<form class="newAccount" action="detail.php" method="post">
		<label>Sélectionner un type de compte</label>
		<select class="" name="name" required>
			<option value="" disabled>Choisissez le type de compte à ouvrir</option>
			<option value="Compte courant">Compte courant</option>
			<option value="PEL">PEL</option>
			<option value="Livret A">Livret A</option>
			<option value="Compte joint">Compte joint</option>
			
		</select>
		<input type="submit" name="new" value="Ouvrir un nouveau compte">
	</form>

	<hr>

	<div class="main-content flex">

	<!-- For each account in our database we display different informations about it -->

	<?php foreach ($accounts as $account) {
		?>

		<div class="card-container">
			
			<!-- we use a particular className for the card to show to the user if his balance is negative or not -->
			<div class="card <?php 
			if ($account->getBalance() >= 0) { ?>
				card-border
			<?php }
			else{ ?>
				card-border-danger
			<?php } ?>
			r">
				<h3><strong><?php echo $account->getName(); ?></strong></h3>
				<div class="card-content">
					
					
					<p>Somme disponible : <?php echo $account->getBalance(); ?> €</p>
					<?php if ($account->getBalance() < 0) { ?>
						<small>Attention vous êtes à découvert, vous devriez arrêter de retirer de l'argent de ce compte</small>
					<?php } ?>
					
					<!-- add and remove balance form -->
					<h4>Dépot / Retrait</h4>
					<form action="detail.php" method="post">
						<input type="hidden" name="id" value=" <?php echo $account->getId(); ?>"  required>
						<label>Entrer une somme à débiter/créditer</label>
						<input type="number" name="balance" placeholder="Ex: 250" required>
						<input type="submit" name="payment" value="Créditer">
						<input type="submit" name="debit" value="Débiter">
					</form>
					
					
					<!-- transfer form -->
					<form action="detail.php" method="post">
						
						<h4>Transfert</h4>
						<label>Entrer une somme à transférer</label>
						<input type="number" name="balance" placeholder="Ex: 300"  required>
						<input type="hidden" name="idDebit" value="<?php echo $account->getId();?>" required>
						<label for="">Sélectionner un compte pour le virement</label>
						<select name="idPayment" required>
							<option value="" disabled>Choisir un compte</option>

							<!-- this foreach permit us to display all accounts name except the one in which we are -->
							<?php 
							foreach ($accounts as $accountTransfer) {
								if ($accountTransfer->getName() != $account->getName()) {
									?>
									<option value="<?php echo $accountTransfer->getId(); ?>"><?= $accountTransfer->getName(); ?></option>
									<?php
								}
							}
							?>
						</select>
						<input type="submit" name="transfer" value="Transférer l'argent">
					</form>
					
					<!-- Delete form -->
					<form class="delete" action="detail.php" method="post">
						<input type="hidden" name="id" value="<?php echo $account->getId(); ?>"  required>
						<input type="submit" name="delete" value="Supprimer le compte">
					</form>
					
				</div>
			</div>
		</div>
		
	<?php } ?>
		
	</div>

	<hr>

	<form class="disconnection m-4" action="detail.php" method="post">
		<input type="submit" class="p-2" name="disconnection" value="Deconnexion">
	</form>
</div>

<?php

include('includes/footer.php');

?>
