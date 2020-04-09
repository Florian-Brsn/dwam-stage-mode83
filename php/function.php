<?php

function debug($variable)
{

    echo '<pre>' . print_r($variable, true) . '</pre>';
}

function str_random($length)
{
    $alphabet = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";
    return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length);
}

function logged_only()
{

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['auth'])) {
        $_SESSION['flash']['danger'] = "Vous n'avez pas le droit d'accéder  cette page ";
        header('Location: Login.php');
        exit();
    }
}


function recconect_from_cookie()
{

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_COOKIE['remember']) && !isset($_SESSION['auth'])) {
        require_once 'C:/wamp64/www/html/Autresite/Ellement/db.php';
        $remember_token = $_COOKIE['remember'];
        $parts = explode('==', $remember_token);
        $user_id = $parts[0];
        if ($pdo) {
            $req = $pdo->prepare('SELECT * FROM utilisateur WHERE id = ?');
            $req->execute([$user_id]);
            $user = $req->fetch();
        } else {
            print("pdo n'éxiste pas frunnnnd");
        }
        if ($user) {
            $expected = $user_id . '==' . $user->remember_token . sha1($user_id . 'ratonlaveurs');
            if ($expected == $remember_token) {
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['auth'] = $user;
                setcookie('remember', $remember_token, time() + 60 * 60 * 24 * 7);
            }
        }
    } else {
        setcookie('remember', null, -1);
    }
}
/*
function showflash(){
    <?php if (isset($_SESSION['flash'])) : ?>
                    <?php foreach ($_SESSION['flash'] as $type => $message) : ?>
                        <div class="alert alert-<?= $type; ?>">
                            <?= $message; ?>
                        </div>
                    <?php endforeach; ?>
                    <?php unset($_SESSION['flash']); ?>
                <?php endif; ?>
}

*/

function Article_sand($id_photo)
{
    global $errors;

    if (!empty($_POST)) {
        require 'db.php';
        // || !preg_match('/^[a-z0-9-Z-é]+$/' , $_POST['titre_article'])
        if (empty($_POST['titre_article'])) {

            $errors['titre'] = "Veuillez rentrer un titre";
        } else {
            $req = $pdo->prepare('SELECT id FROM articles WHERE titre = ?');
            $req->execute([$_POST['titre_article']]);
            $article = $req->fetch();

            if ($article) {
                $errors['titre'] = "Un article ayant un même titre existe déjà";
            }
        }


        if (empty($_POST['corp_article'])) {

            $errors['corps'] = "Nous n'avons pas pu trouver de texte dans le corps de l'article";
        }

        if (empty($errors)) {

            $req = $pdo->prepare('INSERT INTO articles SET titre = ? , contenu = ?, categories = ?,membre_posteur = ?');
            $req->execute([$_POST['titre_article'], $_POST['corp_article'], $_POST['categorie'], $_SESSION['auth']->id]);
            $req = $pdo->prepare('SELECT * FROM articles WHERE titre = ?');
            $req->execute([$_POST['titre_article']]);

            $article = $req->fetch();
            $_SESSION['current_article'] = $article;


            if (isset($_POST['publie'])) {
                $req = $pdo->prepare('UPDATE articles SET publique = ? WHERE id = ?');
                $req->execute(['oui', $article->id]);
            } else {
                $req = $pdo->prepare('UPDATE articles SET publique = ? WHERE id = ?');
                $req->execute(['non', $article->id]);
            }
        }


        if (isset($_FILES[$id_photo]) and !empty($_FILES[$id_photo]['name'])) {

            $tailleMax = 2097152;
            $extensionValides = array('jpg', 'jpeg', 'gif', 'png');
            if ($_FILES[$id_photo]['size'] <= $tailleMax) {
                $extensionUpload = strtolower(substr(strrchr($_FILES[$id_photo]['name'], '.'), 1));
                if (in_array($extensionUpload, $extensionValides)) {
                    $path = "Ressource_image/photo_article/" . $_SESSION['current_article']->id . "." . $extensionUpload;
                    $resultat = move_uploaded_file($_FILES[$id_photo]['tmp_name'], $path);
                    if ($resultat) {
                        require_once 'Ellement/db.php';

                        $req = $pdo->prepare('UPDATE articles SET image = :image WHERE id = :id');
                        $req->execute(array(
                            'image' => $_SESSION['current_article']->id . "." . $extensionUpload,
                            'id' => $_SESSION['current_article']->id
                        ));
                    }
                }
            }
        }

        return $errors;
    }
}

function connect_from_session($pseudo)
{
    if (!isset($_SESSION['auth'])) {
        require_once 'db.php';
        $req = $pdo->prepare('SELECT * FROM utilisateur WHERE login = ?');
        $req->execute([$pseudo]);
        $user = $req->fetch();
        if ($user) {
            $_SESSION['auth'] = $user;
        } else {
            echo "aucun utilisateur n'ayant ce pseudo n'éxiste dans la base de donné";
        }
    }
}
function show_errors()
{
}

//tester si session ouverte

function testSession(){
    if(!isset($_SESSION['utilisateur_id']))
    {
        header('Location: connexion.php');
        exit;
    }
}


//fonction input liste déroulante 


function query($sql, $vars = NULL, $debug = false) {
		
	if ($debug==true){
		$sql_debug = $sql;
		if (count($vars)>0){
			foreach($vars as $key => $value){
				// echo $key." : ".$value."<br/>";
				$sql_debug = str_replace($key, "'".str_replace("'", "''", $value)."'", $sql_debug);
			}
		}
		echo "<pre>";print_r($sql_debug);
	}	
	$res = $GLOBALS['conn']->prepare($sql);	
	if ($res === FALSE) {
		return FALSE;
	}

	$result = $res->execute($vars);
	if ($result !== FALSE) {
		return $res;
	}
	
	
	// Erreur
	$error = $res->errorInfo();
	$file = __FILE__;
	$line = __LINE__;
	if ($file || $line){
		//log_sql_error($sql,$error[2]);
		Kill($sql.$error[2], $file, $line);
	}
	
	return(false);
}



function Kill($msg = "", $file = null, $line = null)
{
	if ($msg)
	{	if ($file)
		{	$msg .= " dans {$file}";
			if ($line)
				$msg .= ", ligne {$line}";
		}
		$msg .= " :\n";
	}
	elseif ($file)
	{	$msg = $file;
		if ($line)
			$msg .= ", ligne {$line}";
		$msg .= " :\n";
	}
	die("<font color='red'>".$msg."</font>");
}


function fetch_object($req) {
	return $req->fetchObject();
}