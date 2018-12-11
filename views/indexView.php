<?php

include('includes/header.php');

?>

<div class="container">

	<header class="flex">
		<p class="margin-right">Bienvenue sur l'application Comptes Bancaires</p>
	</header>

	<div class="container">
		<?php if ($message) { ?>
			<p class="text-center"><?= $message ?></p>
		<?php } ?>
		<div class="row">
			<div class="col-md-6 mx-auto indexform borderFormRight pr-5">

			<h3 class="mt-5">Inscription</h3>

			<!-- inscriptionForm -->

				<form method="post" action="index.php" class="my-5 connexion">
				<div class="form-group">
					<label for="exampleInputName">Prénom</label>
					<input type="text" class="form-control" id="exampleInputName" aria-describedby="nameHelp" placeholder="Entrez votre prénom" name="name" autofocus required>
				</div>
				<div class="form-group">
					<label for="exampleInputPassword1">Mot de passe</label>
					<input type="password" class="form-control" id="exampleInputPassword1" placeholder="Mot de passe" name="password" required>
				</div>
				<div class="form-group">
					<label for="exampleInputPassword2">Mot de passe (vérification)</label>
					<input type="password" class="form-control" id="exampleInputPassword2" placeholder="Entrez à nouveau votre mot de passe" name="passwordbis" required>
				</div>
				<div class="form-group">
					<label for="exampleInputEmail1">Email</label>
					<input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Entrez votre email" name="email" required>
					<small id="emailHelp" class="form-text text-muted">Nous ne partagerons jamais votre email avec qui que ce soit</small>
				</div>
				<input type="submit" name="addUser" value="Inscription">
				</form>

			</div>
			<div class="col-md-6 mx-auto indexform d-flex flex-column borderFormLeft pl-5">

			<h3 class="mt-5">Connexion</h3>

		<!-- connectionForm -->
		
			<form method="post" action="index.php" class="mt-5 connexion">
				<div class="form-group">
					<label for="exampleInputEmail1">Email</label>
					<input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Entrez votre email" name="email" required>
				</div>
				<div class="form-group">
					<label for="exampleInputPassword1">Mot de passe</label>
					<input type="password" class="form-control" id="exampleInputPassword1" placeholder="Mot de passe" name="password" required>
				</div>
				<input type="submit" name="connectUser" value="Connexion">
			</form>

			</div>
		</div>
	</div>
</div>

<?php

include('includes/footer.php');

?>