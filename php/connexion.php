<?php
session_start();
$bdd = new PDO('mysql:host=127.0.0.1;dbname=blog', 'admin', 'admin');

if(isset($_POST['submit']))
{
    $email = htmlspecialchars($_POST['email']);
    $mdp = /*sha1*/($_POST['mdp']);
    if(!empty($email) AND !empty($mdp))
    {
        $requser = $bdd->prepare("SELECT * FROM connexion WHERE email =? AND mdp =?");
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
