<?php include 'Ellement/Header.php'; ?>

<!DOCTYPE html>

<?php require 'Ellement/function.php';
connect_from_session('alphonse');
$errors = Article_sand('photo_article');
?>


<html>

<head>
	<meta charset="utf-8" />
	<link rel="stylesheet" href="style.css">
	<title>Mode83 Teletravail Admin</title>
	<link rel="stylesheet" href="Créer_article.css">

</head>

<body>

	<div class="container-fluid">
		<div class="row justify-content-between">
			<div class="col-lg-12" style="padding:0;">
				<div class="barre_titre justify-content-between">

					<h4>Créer un nouvel article </h4>
					<h4 style="border-radius: 10% 0 0 10%;"> <?php if (isset($_SESSION['auth'])) {echo $_SESSION['auth']->login;} else {echo 'aucun utilisateur connecté';} ?> </h4>

				</div>
			</div>
		</div>


	</div>
	<div class="alerte_message">
		<?php if (!empty($errors)) : ?>
			<div class="alert alert-danger">
				<p>Oups ! Le formulaire comporte des erreurs : </p>
				<ul>
					<?php foreach ($errors as $error) : ?>
						<li><?= $error; ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php elseif (empty($errors) and $_POST) : ?>
			<div class="alert alert-success">
				<p>Felicitation votre post a bien été créé ! </p>
			</div>
		<?php endif; ?>
	</div>

	<div class="zone_entre">
		<h3>Rédiger un article</h3>
		<form style="" action="" method="POST" enctype="multipart/form-data">

			<div class="form-row align-items-center head_form" style="">
				<!-- LE TITRE  -->
				<input name="titre_article" class="titre_article" type="text" placeholder="       Titre de l'article "></input>

				<!-- C'EST LE SWITCH -->

				<!-- BOUTON IMAGE  -->
				<button style="font-weight:bold" type="button" class="bouton1" data-toggle="modal" data-target="#openfile">Ajouter une image</button>
				<div class="modal fade" id="openfile" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="openfile">Ajouter une photo !</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class="form-group standardzonev">
									<label for="photo_article">Associer une photo à l'article</label>
									<input type="file" name="photo_article" id="avatar" />
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Retour</button>
								<button type="button" data-dismiss="modal" class="btn btn-primary">Valider</button>
							</div>
						</div>
					</div>
				</div>
			</div>

			<textarea placeholder="         Corps de l'article" style="border: solid black 1px ;width:100%;" name="corp_article" id="" cols="30" rows="10"></textarea>
			<!-- LIGNE DU BAS  -->
			<div class="footer_form form-row justify-content-between">

				<div title="Publier cet article ?" class="switch1 d-flex flex-row justify-content-center align-items-center">
					<label class="switch">
						<input id="publie" name="publie" type="checkbox">
						<span class="slider round"></span>
					</label>
					<p style="margin-left:10px;font-size:1.2em;font-style:italic;text-shadow:0px 0px 1px black,2px 0px 2px white;"> Publié</p>
				</div>
				<div>
					<label style="font-size:1.2em;font-style:italic;text-shadow:0px 0px 1px black,2px 0px 2px white;" for="categorie">Catégorie : </label>
					<select name="categorie" id="categorie">

						<option>Outils</option>
						<option>Santé</option>
						<option>General</option>
					</select>
				</div>
				<button class="bouton" style="font-weight:bold" type="submit">Valider</button>
			</div>
		</form>
	</div>

	</div>
	<a href="acceuil_publique.php">Voir les articles</a>
	<script src="bootstrap/js/jquery-3.4.1.min.js"></script>
	<script src="bootstrap/js/bootstrap.bundle.js"></script>
</body>



</html>