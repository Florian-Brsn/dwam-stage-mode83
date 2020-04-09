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
    <link rel="stylesheet" href="Visualiser_article.css">

</head>

<body>

    <div class="container-fluid" style="margin-top:2vh;">
        <div class="row justify-content-between">
            <h3>-------LIRE MON ARTICLE</h3>
            <a href="acceuil_publique.php">visualiser les articles</a>
        </div>
    </div>

    <div class="container-fluid" style="margin-top:5vh;">
        <div class="row">
            <div class="col-lg-10">
                <?php init_display_post(); ?>
            </div>
            <div class="col-lg-2" style="">
                <div class="preview_legend">
                    D'autres articles
                </div>
                <?php init_display_preview(); ?>

            </div>

        </div>
    </div>

    <a class="lien_pageAcceuil" href="acceuil_publique.php"></a>

</body>

</html>