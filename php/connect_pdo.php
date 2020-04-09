<?php
$server = "modencvefoad.mysql.db"; // localisation du serveur MSSQL
$name = "modencvefoad"; // Login de l'utilisateur
$password = "Formation83"; // Password de l'utilisateur
$base = "modencvefoad"; // Nom de la Base de donn�es

$dsn = 'mysql:dbname='.$base.';host='.$server;

$options = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
);

try {
    $GLOBALS['conn'] = new PDO($dsn, $name, $password, $options);
} catch (PDOException $e) {
    echo 'Connexion impossible au serveur : ' . $e->getMessage();
}



?>
