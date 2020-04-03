<meta charset="utf-8">
<?php
if(isset($_GET['pseudo'], $_GET['key']) AND !empty($_GET['pseudo']) AND !empty($_GET['key'] ))
{
    $bdd = new PDO('mysql:host=127.0.0.1;dbname=blog', 'admin', 'admin');
    $pseudo = htmlspecialchars ($_GET['pseudo']);
    $key = intval($_GET['key']);
    $requser = $bdd->prepare("SELECT * FROM connexion WHERE pseudo = ? AND confirmkey = ?");
    $requser->execute(array($pseudo));
    $userexist = $requser->rowCount();
    if($userexist == 1)
    {
        $user = $requser->fetch();
        if($user['confirme'] == 0)
        {
            $updateuser = $bdd->prepare("UPDATE connexion SET confime = 1 WHERE pseudo = ? AND confirmkey = ?" );
            $updateuser->execute(array($pseudo, $key));
            echo "Votre compte a bien été confirmé !";
        }
        else
        {
            echo "Votre compte a déjà été confirmé !";
        }
    }
    else
    {
        echo "L'utilisateur n'existe pas !";
    }
}
?>