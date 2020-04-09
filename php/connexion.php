<?php
session_start();
$bdd = new PDO('mysql:host=modencvefoad.mysql.db;dbname=modencvefoad' , 'modencvefoad' , 'Formation83');

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

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="https://formations.mode83.net/DWAM/dwtl_blog/css/bootstrap.min.css">
</head>
<body>

<!-- Titre -->
<header>
   <?php 
    include "header.php";
   ?>
</header>
    <!-- Formumlaire -->
<section class=" bg-secondary px-5 mt-5 py-3  text-light">
  <div>

    <form method="POST">
        <div class=" form-group mt-3" >
        <label>Email</label>
        <input type="email" name="email" placeholder="Entrez votre adresse email" class="form-control" />
      </div>

      <div class=" form-group">
        <label>Mot De Passe</label>
        <input type="password" name="mdp" placeholder="Entrez votre Mot De Passe." class="form-control" />
      </div>
    
      
      <input name= "submit" class="btn btn-primary" type="submit"  value="Se connecter !" />
      </form>  

      <button class="btn btn-danger"> <a href="/DWAM/dwtl_blog/php/inscription.php">Inscription</a></button>


      <?php
        if(isset($erreur))
        {
            echo "<font color='red'>".$erreur."</font>";
        }
         
      ?>

      
  </div>
</section>
        <footer>
        
          <?php include "footer.php"; ?>
        
        </footer>

</body>
</html>