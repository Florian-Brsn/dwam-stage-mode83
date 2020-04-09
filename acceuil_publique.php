<?php include 'Ellement/Header.php'; ?>

<!DOCTYPE html>

<?php require 'Ellement/function.php';
//Conexion manuel d'un utilisateur la fonction prend en paramètre le pseudo de l'utilisateur présent dans la base de donné
connect_from_session('alphonse');

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="style.css">
    <title>Teletravail acceuil</title>
    <link rel="stylesheet" href="acceuil_publique.css">

</head>

<body>

    <div class="container-fluid" style="margin-top:2vh;">
        <div class="row justify-content-between">
            <h1>-------Le teletravail aujourd'hui </h1>
            <a href="Créer_article.php">Ecrire un article</a>
        </div>
    </div>

    <div class="container-fluid" style="margin-top:5vh;">
        <div class="row" >


            <!-- AFFICHAGE DES POST  -->
            <?php init_show_post() ?>


        </div>
    </div>

</body>

</html>