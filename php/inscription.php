<?php
$bdd = new PDO('mysql:host=127.0.0.1;dbname=blog' , 'admin' , 'admin');

if(isset($_POST['submit']))
{
    $pseudo = htmlspecialchars($_POST['pseudo']);
    $email1 = htmlspecialchars($_POST['email1']);
    $email2 = htmlspecialchars($_POST['email2']);
    $mdp1 = sha1($_POST['mdp1']);
    $mdp2 = sha1($_POST['mdp2']);

    if(!empty($_POST['pseudo']) AND !empty($_POST['email1']) AND !empty($_POST['email2']) AND !empty($_POST['mdp1']) AND !empty($_POST['mdp2']))
    {
        $pseudolenght = strlen($pseudo);
        if($pseudolenght <= 255)
        {
            if($email1 == $email2)
            {
                if(filter_var($email1, FILTER_VALIDATE_EMAIL))
                {
                    $reqemail = $bdd->prepare("SELECT * FROM connexion WHERE email =?");
                    $reqemail->execute(array($email1));
                    $emailexist = $reqemail->rowCount();
                    if($emailexist == 0)
                    {
                        $reqpseudo = $bdd->prepare("SELECT * FROM connexion WHERE pseudo =?");
                        $reqpseudo->execute(array($pseudo));
                        $pseudoexist = $reqpseudo->rowCount();
                        if($pseudoexist == 0)
                        {
                            if($mdp1 == $mdp2)
                             {
                                $insertmbr = $bdd->prepare("INSERT INTO connexion(pseudo, email, mdp) VALUES(? ,?, ?)");
                                $insertmbr->execute(array($pseudo, $email1, $mdp1));
                                $_SESSION['comptcree'] = "Votre compte a bien été crée !";
                                header("location: connexion.php");
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

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body>
<!--Titre et header-->
<header class="d-block text-center mt-5 text-danger">
<div>
<h1>
<a href="inscription.php">Page d'inscription</a>
</h1>
</div>
</header>

<!--Formulaire-->
<section class="bg-secondary px-5 mt-5 py-5 text-light">
    <div>
        <form method="POST">

            <div class="form-group">
            <label>pseudo</label>
            <input type="text" name="pseudo" placeholder="Veuillez choisir un Pseudo." class=" form-control" value="<?php if(isset($pseudo)) { echo $pseudo;} ?>" />
            </div>

            <div class="form-group">
            <label>Email</label>
            <input type="email" name="email1" placeholder="Veuillez entrer une adresse Email." class=" form-control" value="<?php if(isset($email1)) { echo $email1;} ?>" />
            </div>

            <div class="form-group">
            <label>Confirmation Email</label>
            <input type="email" name="email2" placeholder="Veuillez confirmer votre adresse Email." class=" form-control" value="<?php if(isset($email1)) { echo $email1;} ?>" />
            </div>

            <div class="form-group">
            <label>mot de passe</label>
            <input type="password" name="mdp1" placeholder="Veuillez choisir un mot de passe." class=" form-control" />
            </div>

            <div class="form-group">
            <label>Confirmation de mot de passe</label>
            <input type="password" name="mdp2" placeholder="Veuillez confirmer votre mot de passe." class=" form-control" />
            </div>

            <input  type="submit" name="submit" value="S'inscrire !" />

        </form>
    </div>
</section>
</body>
</html>



<?php

echo "<font color='red'>".$erreur."</font>";

?>