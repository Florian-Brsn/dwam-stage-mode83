<?php
$bdd = new PDO('mysql:host=modencvefoad.mysql.db;dbname=modencvefoad' , 'modencvefoad' , 'Formation83');

    // $pseudo = "lulu";
    // $email1 = "lulu@lulu.fr";
    // $email2 = "lulu@lulu.fr";
    // $mdp1 = "1234";
    // $mdp2 = "1234";

    if(isset($_POST['submit']))
 {
    $pseudo = htmlspecialchars($_POST['pseudo']);
    $email1 = htmlspecialchars($_POST['email1']);
    $email2 = htmlspecialchars($_POST['email2']);
    $mdp1 = sha1($_POST['mdp1']);
    $mdp2 = SHA1($_POST['mdp2']);

     if(!empty($_POST['pseudo']) AND !empty($_POST['email1']) AND !empty($_POST['email2']) AND !empty($_POST['mdp1']) AND !empty($_POST['mdp2']))
    {
        $pseudolenght = strlen($pseudo);
        if($pseudolenght <= 255)
        {
            if($email1 == $email2)
            {
                if(filter_var($email1, FILTER_VALIDATE_EMAIL))
                {
                  $reqmail = $bdd->prepare("SELECT * FROM DWTL_connexion WHERE email = ?");
                  $reqmail->execute(array($email1));
                  $mailexist = $reqmail->rowCount();
                  if($mailexist == 0)
                  {
                    $reqpseudo= $bdd->prepare("SELECT * FROM DWTL_connexion WHERE pseudo = ?");
						        $reqpseudo->execute(array($pseudo));
						        $pseudoexist = $reqpseudo->rowCount();
						        if($pseudoexist == 0)
						        {
                      if($mdp1 == $mdp2)
                      {
                          $insertmbr = $bdd->prepare("INSERT INTO DWTL_connexion(pseudo, email, mdp) VALUES(?, ?, ?) ");
                          $insertmbr->execute (array($pseudo, $email1, $mdp1));
                          $_SESSION['comptecree'] = "votre compte a bien été crée !";
                          
                      }
                      else
                      {
                          $erreur= " Vos mot de passe ne correspondent pas !";
                      }
                    }
                    else
                    {
                      $erreur = "Ce pseudo est déjà utilisé !";
                    }
                  } 
                  else
                  {
                    $erreur = "Adresse Email déjà utilisée !";
                  }
              }
               else
               {
                 $erreur = "Votres adresse Email n'est pas valide !";  
               }

            }
            else
            {
                $erreur= " Vos adresses Emails de correspondent pas !";
            }
        }
        else
        {
           $erreur = "votre pseudo ne doit pas dépasser 255 caractères";
        }
        

    }
     else
     {
         $erreur ="tous les champs doivent etre complétés";
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
    <header class=" d-block text-center mt-5 text-alerte">
    <div>
    <h1> <a href="inscription.php">Page d'inscription .</a> </h1>
    </div>
    </header>

    <!-- Formumlaire -->
<section class=" bg-secondary px-5 mt-5 py-3  text-light">
  <div>

    <form method="POST">
      <div class=" form-group">
        <label>Pseudo</label>
        <input type="text" name="pseudo" placeholder="Veuillez choisir un pseudo." class="form-control" value="<?php if(isset($pseudo)) { echo $pseudo;} ?>"/>
      </div>
    <!-- </form>

    <form> -->
      <div class=" form-group mt-3" >
        <label>Email</label>
        <input type="email" name="email1" placeholder="Entrez votre adresse email" class="form-control" value="<?php if(isset($email1)) { echo $email1;} ?>"/>
        <!-- <small id="emailHelp" class="form-text text-muted"  >Nous ne partagerons pas votre adresse email.</small> -->
      </div>
    <!-- </form>

    <form> -->
      <div class=" form-group">
        <label>Email de confimation</label>
        <input type="email" name="email2" placeholder="Entrez votre adresse email une nouvelle fois" class="form-control" value="<?php if(isset($email2)) { echo $email2;} ?>"/>
        <!-- <small id="emailHelp" class="form-text text-muted">Nous ne partagerons pas votre adresse email.</small> -->
      </div>
    <!-- </form>

    <form> -->
      <div class=" form-group">
        <label>mot de passe</label>
        <input type="password" name="mdp1" class=" form-control" placeholder="Veuillez choisir un mot de passe." />
      </div>
    <!-- </form>

    <form> -->
      <div class=" form-group">
        <label>confirmation du mot de passe</label>
        <input type="password" name="mdp2" class=" form-control" placeholder="Veuillez confirmer votre mot de passe." />
      </div>
    
    
        <input  type="submit" name="submit" value="S'inscrire !" />
      </form>  


      <?php
        if(isset($erreur))
        {
            echo "<font color='red'>".$erreur."</font>";
        }

       

      ?>

  </div>
</section>
</body>
</html>
