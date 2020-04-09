<meta charset="utf-8">
<?php
if(isset($_GET['id'], $_GET['confirmkey']) AND !empty($_GET['id']) AND !empty($_GET['confirmkey'] ))
{
    $bdd = new PDO('mysql:host=modencvefoad.mysql.db;dbname=modencvefoad' , 'modencvefoad' , 'Formation83');
    $id = intval ($_GET['id']);
    $key = intval($_GET['confirmkey']);
    $requser = $bdd->prepare("SELECT * FROM DWTL_connexion WHERE id = ? /*AND confirmkey = ?*/");
    $requser->execute(array($id));
    $userexist = $requser->rowCount();
    if($userexist == 1)
    {
        $user = $requser->fetch();
        if($user['confirme'] == 0)
        {
            $updateuser = $bdd->prepare("UPDATE DWTL_connexion SET confirme = 1 /*WHERE id = ? AND confirmkey = ?*/" );
            $updateuser->execute(array($id));
            echo "Votre compte a bien été confirmé !";
            header('location: connexion.php');
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