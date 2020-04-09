<?php
session_start();
include "function.php";
?>

<?php
// session_start();
// testSession()
?>

<?php
require("connect_pdo.php");
$bdd = new PDO('mysql:host=modencvefoad.mysql.db;dbname=modencvefoad' , 'modencvefoad' , 'Formation83');
if(isset($_POST['submit']))
{   
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $pseudo = htmlspecialchars($_POST['pseudo']);
    $email1 = htmlspecialchars($_POST['email1']);
    $email2 = htmlspecialchars($_POST['email2']);
    $mdp1   = sha1($_POST['mdp1']);
    $mdp2   = sha1($_POST['mdp2']);
    
    $formation = $_POST['formation'];
    $ref = htmlspecialchars($_POST['ref']);


    if(!empty($_POST['nom']) AND !empty($_POST['prenom']) AND !empty($_POST['pseudo']) AND !empty($_POST['email1']) AND !empty($_POST['email2']) AND !empty($_POST['mdp1']) AND !empty($_POST['mdp2']) AND !empty($_POST['formation']) AND !empty($_POST['ref']))
    {
        $pseudolenght = strlen($pseudo);
        if($pseudolenght <= 255)
        {
            if($email1 == $email2)
            {
                if(filter_var($email1, FILTER_VALIDATE_EMAIL))
                {
                    $reqemail = $bdd->prepare("SELECT * FROM DWTL_connexion WHERE email =?");
                    $reqemail->execute(array($email1));
                    $emailexist = $reqemail->rowCount();
                    if($emailexist == 0)
                    {
                        $reqpseudo = $bdd->prepare("SELECT * FROM DWTL_connexion WHERE pseudo =?");
                        $reqpseudo->execute(array($pseudo));
                        $pseudoexist = $reqpseudo->rowCount();
                        if($pseudoexist == 0)
                        {
                            if($mdp1 == $mdp2)
                             {      
                                $key = str_random(60);
                                $insertmbr = $bdd->prepare("INSERT INTO DWTL_connexion SET prenom = ?, nom =?, pseudo = ?, email = ?, mdp = ?, confirmkey = ?, formation = ?, ref = ? ");
                                $insertmbr->execute(array($nom, $prenom, $pseudo, $email1, $mdp1, $key, $formation, $ref));
                                $user_id = $bdd->lastinsertid();
                                mail($_POST['email1'], 'Confirmation de votre compte', "Afin de valider votre compte merci de cliquer sur ce lien\n\nhttps://formations.mode83.net/DWAM/dwtl_blog/php/confirmation.php?id=$user_id&confirmkey=$key");

                                $erreur = "Votre compte a bien été créé ! ";
                                header("loaction: php/connexion.php");
                            }
                             else
                             {
                                 $erreur = "Vos mots de passes ne correspondent pas !";
                             }
                        }
                        else
                        {
                            $erreur = "Ce pseudo existe déjà !";
                        }
                    }
                    else
                    {
                        $erreur = "Adresse Email déjà utilisé !";
                    }
                }
                else
                {
                    $erreur = "Votre adresse mail n'est pas valide !";
                }
            }
            else
            {
                $erreur = "Vos adresses Email ne correspondent pas";
            }
        }
        else
        {
            $erreur = "Votre pseudo ne doit pas dépasser les 255 caractères !";
        }
    }
    else
    {
        $erreur = "Tous les champs doivent être complétés !";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
   <link rel="stylesheet" href="https://formations.mode83.net/DWAM/dwtl_blog/css/connexion2.css">
</head>
	
	


<header>
    <?php
    include "header.php";
    ?>
</header>

<body>

	<section class="container">
		
		<div class="content">
    <?php
        if(isset($_POST['submit']))
        {
            $email = htmlspecialchars($_POST['email']);
            $mdp = sha1($_POST['mdp']);
            if(!empty($email) AND !empty($mdp))
            {
                $requser = $bdd->prepare("SELECT * FROM DWTL_connexion WHERE email = ? AND mdp = ?");
                $requser->execute(array($email, $mdp));
                $userexist = $requser->rowCount();
                if($userexist == 1)
                {
                    $userinfo = $requser->fetch();
                    $_SESSION['id'] = $userinfo['id'];
                    $_SESSION['pseudo'] = $userinfo['pseudo'];
                    $_SESSION['email'] = $userinfo['email'];
                    header("location: loremipsum.php=".$_SESSION['id']);
                }
                else
                {
                    $erreur = "Identifiants Incorrects !";
                }
            }
            else
            {
                $erreur = "Tous les champs doivent être complétés !";
            }
        }

        ?>

			<p class="se_connecter">Se connecter</p>

			<div class="formulaire_connexion">
				<!-- PSEUDO -->
				<form action="">
				<label for="Email"></label>
                <input type="email" id="pseudo" placeholder="email" required="required" name="email">
                
  				<!-- MOT DE PASSE -->
  				
                <label for="mdp"></label>
                <input type="password" id="mdp" placeholder="Mot de passe" required="required" name="mdp">
                
                <input name= "submit2" class="btn btn-primary" type="submit"  value="Se connecter !" />
                <!-- BOUTON -->
                <a href="#"><img src="img/cadenas.png" class="cadenas"></a>
            </form>
			</div>

			<div class="formulaire_inscription">
				<!-- SEPARATEUR -->
				<hr class="hr">
				<!-- INSCRIPTION -->
				<p>Je m'inscris</p>
				<!-- MENU DEROULANT -->
				<a href="#afficher" title="inscription"><img src="https://formations.mode83.net/DWAM/dwtl_blog/img/fleche_bleue.png" class="fleche_bleue"></a>
				
				
					<form id="afficher" method="POST">
					<!-- NOM -->			
					<label for="nom"></label>
		            <input type="text" id="nom" placeholder="Nom" required="required" name="nom">
					<!-- PRENOM -->					
					<label for="Prenom"></label>
		            <input type="text" id="Prenom" placeholder="Prénom" required="required" name="prenom">
					<!-- PSEUDO -->				
					<label for="pseudo"></label>
		            <input type="text" id="pseudo" placeholder="Pseudo" required="required" name="pseudo">
					<!-- MOT DE PASSE -->					
					<label for="mdp"></label>
		            <input type="password" id="mdp" placeholder="Mot de Passe" required="required" name="mdp1">
					<!-- CONFIRMER MOT DE PASSE -->					
					<label for="confirmation_mdp"></label>
					<input type="password" id="confirmation_mdp" placeholder="Confirmer mot de passe" required="required" name="mdp2">
					<!-- Email -->					
					<label for="Email"></label>
					<input type="email" id="confirmation_mdp" placeholder="Email" required="required" name="email1">
					<!-- CONFIRMER Email -->					
					<label for="confirmation_Email"></label>
		            <input type="email" id="confirmation_mdp" placeholder="Confirmer Email" required="required"name="email2">
					<!-- FORMATION -->					
					<!-- <label for="formation"></label>
                    <input type="text" id="formation" placeholder="Formation" required="required" name="formation"> -->
                        <?php
                        //require 'function.php';
                        require("connect_pdo.php");

                        $sql="SELECT * FROM DWTL_formation";
                        $exe=query($sql);
                        ?>
                        <select name="formation">
                        <?php
                        while($formation=fetch_object($exe))
                        {
                            ?>
                            <option> <?php echo $formation->nom_formation;?></option>
                            <?php
                        }
                        ?>

                        </select>
					<!-- REFERENCE -->					
					<label for="reference"></label>
		            <input type="text" id="reference" placeholder="Référence" required="required" name="ref">
										
					<!-- BOUTON -->
					<!-- <a href="#"><img src="img/fleche.png" class="fleche"></a> -->
					<input type="submit" name="submit" id="input" value="S'inscrire !">
					</form>
		
			</div>
		</div>
	</section>

<footer>

<?php
include "footer.php";
?>

</footer>

</body>
</html>