<?php

include 'function.php';

?>
<?php
$bdd = new PDO('mysql:host=modencvefoad.mysql.db;dbname=modencvefoad' , 'modencvefoad' , 'Formation83');

if(isset($_POST['submit']))
{
    $pseudo = htmlspecialchars($_POST['pseudo']);
    $email1 = htmlspecialchars($_POST['email1']);
    $email2 = htmlspecialchars($_POST['email2']);
    $mdp1   = sha1($_POST['mdp1']);
    $mdp2   = sha1($_POST['mdp2']);
    // $mdp1   = password_hash($_POST['mdp1'], PASSWORD_BCRYPT);
    // $mdp2   = password_hash($_POST['mdp2'], PASSWORD_BCRYPT);

    if(!empty($_POST['pseudo']) AND !empty($_POST['email1']) AND !empty($_POST['email2']) AND !empty($_POST['mdp1']) AND !empty($_POST['mdp2']))
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
                                $insertmbr = $bdd->prepare("INSERT INTO DWTL_connexion SET pseudo = ?, email = ?, mdp = ?, confirmkey = ? ");
                                $insertmbr->execute(array($pseudo, $email1, $mdp1, $key));
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

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="https://formations.mode83.net/DWAM/dwtl_blog/css/bootstrap.min.css">
</head>
<body>
<!--Titre et header-->
<header>
<!-- <div>
<h1>
<a href="inscription.php">Page d'inscription</a>
</h1>
</div> -->
    
        <?php 
        include "header.php";
        ?>
    
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
            <a href="connexion.php">Déjà inscrit.</a>

        </form>
    </div>
    <?php

    echo "<font color='red'>".$erreur."</font>";

    ?>
</section>


    <footer>
    <?php
    include 'footer.php';
    ?>
    </footer>

</body>
</html>



