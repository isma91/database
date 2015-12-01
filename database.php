#!/usr/bin/env php
<?php
/*pour avoir un xdebug en entier*/
/*ini_set('xdebug.var_display_max_depth', -1);
ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);*/
$GLOBALS["filename"] = basename(__FILE__);
function function_mysql ($host, $username, $password, $database) {
    if ($password === "[]") {
        $password = "";
    }
    try {
        $bdd = new PDO("mysql:host=" . $host . ";", $username, $password);
        $requete = $bdd->prepare("SELECT insert_priv FROM mysql.user WHERE user = '" . $username . "';");
        $requete->execute();
        $donnees = $requete->fetch();
        if (strtoupper($donnees["insert_priv"]) === "N") {
            echo "\033[1;33m\033[40mErreur !! \033[1;31m" . $username . "\033[1;33m n'a pas les droits pour crée une base de donnée !!\033[0m\n";
        } else {
            $bdd->exec("CREATE DATABASE IF NOT EXISTS `$database`;");
            echo "\033[40m\033[1;37mLa base de donnée \033[1;32m" . $database . " \033[1;37ma été crée avec succes pour l'utilisateur \033[1;32m" . $username . "\033[1;37m dans \033[1;32m" . $host ." !!\033[0m\n";
        }
    } catch (PDOException $exception) {
        if ($exception->getCode() === 2005) {
            echo "\033[1;33m\033[40mErreur !! Le serveur MySQL \033[1;31m" . $host . "\033[1;33m n'est pas reconnu !!\033[0m\n";
        } elseif ($exception->getCode() === 1045) {
            if ($password === "") {
                echo "\033[1;33m\033[40mErreur !! Le serveur MySQL a refusé l'acces à \033[1;31m" . $username . "\033[1;33m, vous avez peut-être oublier d'écrire le\033[1;31m mot de passe\033[1;33m ??\033[0m\n";
            } else {
                echo "\033[1;33m\033[40mErreur !! Le serveur MySQL a refusé l'acces à \033[1;31m" . $username . "\033[1;33m, vous avez peut-être mal écris le\033[1;31m mot de passe\033[1;33m ??\033[0m\n";
            }
        } else {    
            echo "\033[41m\033[1;37mErreur :\n";
            echo $exception->getMessage();
            echo "\nCode d'erreur : " . $exception->getCode();
            echo "\nLa fonction ayant généré l'erreur : " . $exception->getTrace()[1]['function'];
            echo "\nLa ligne d'erreur dans la fonction : " . $exception->getLine();
            echo "\nla ligne ou l'erreur s'est produit : " . $exception->getTrace()[1]['line'] . "\033[0m\n";
        }
    }
}
function function_debug_mysql ($host, $username, $password, $database) {
    if ($password === "[]") {
        $password = "";
    }
    try {
        $bdd = new PDO("mysql:host=" . $host . ";", $username, $password);
        $requete = $bdd->prepare("SELECT insert_priv FROM mysql.user WHERE user = '" . $username . "';");
        $requete->execute();
        $donnees = $requete->fetch();
        if (strtoupper($donnees["insert_priv"]) === "N") {
            echo "\033[1;33m\033[40mErreur !! \033[1;31m" . $username . "\033[1;33m n'a pas les droits pour crée une base de donnée !!\033[0m\n";
        } else {
            $bdd->exec("CREATE DATABASE IF NOT EXISTS `$database`;");
            echo "\033[40m\033[1;37mLa base de donnée \033[1;32m" . $database . " \033[1;37ma été crée avec succes pour l'utilisateur \033[1;32m" . $username . "\033[1;37m dans \033[1;32m" . $host ." !!\033[0m\n";
        }
    } catch (PDOException $exception) {
        echo "\033[41m\033[1;37mErreur :\n";
        echo $exception->getMessage();
        echo "\nCode d'erreur : " . $exception->getCode();
        echo "\nLa fonction ayant généré l'erreur : " . $exception->getTrace()[1]['function'];
        echo "\nLa ligne d'erreur dans la fonction : " . $exception->getLine();
        echo "\nla ligne ou l'erreur s'est produit : " . $exception->getTrace()[1]['line'] . "\033[0m\n";
    }
}
function not_argv_enough ($type) {
    echo "\033[1;33m\033[40mPas assez d'argument !!\033[0m\n";
    echo "\033[40m\033[1;37mtapez\033[1;31m php " . $GLOBALS["filename"] . " \033[1;32mhelp\033[40m\033[1;37m pour avoir de l'aide !!\033[0m\n";
    echo "\033[40m\033[1;37mtapez\033[1;31m php " . $GLOBALS["filename"] . " \033[1;32m" . $type . "\033[40m\033[1;37m pour avoir l'aide pour utiliser le script avec mysql\033[0m\n";
}
function too_much_argv_enough ($type) {
    echo "\033[1;33m\033[40mTrop d'argument !!\033[0m\n";
    echo "\033[40m\033[1;37mtapez\033[1;31m php " . $GLOBALS["filename"] . " \033[1;32mhelp\033[40m\033[1;37m pour avoir de l'aide !!\033[0m\n";
    echo "\033[40m\033[1;37mtapez\033[1;31m php " . $GLOBALS["filename"] . " \033[1;32m" . $type . "\033[40m\033[1;37m pour avoir l'aide pour utiliser le script avec mysql\033[0m\n";
}
if (count($argv) === 1) {
    echo "\033[1;33m\033[40mPas d'argument !!\033[0m\n";
    echo "\033[40m\033[1;37mtapez\033[1;31m php " . $GLOBALS["filename"] . " \033[1;32mhelp\033[40m\033[1;37m pour avoir de l'aide !!\033[0m\n";
} elseif ($argv[1] === "help") {
    echo "\033[1;37m\033[40mAide pour utiliser ce script PHP :\n";
    echo "\033[40m\033[1;37mtapez\033[1;31m php " . $GLOBALS["filename"] . " \033[1;32mliste\033[40m\033[1;37m pour avoir la liste des types de base de donnée compatibles !!\n";
    echo "\033[40m\n";
    echo "\033[40m\033[1;37mtapez\033[1;31m php " . $GLOBALS["filename"] . " \033[1;32m[type]\033[40m\033[1;37m pour avoir l'aide pour utiliser le script avec le type de base de donnée choisit !!\033[0m\n";
    echo "\033[40m\033[1;37mExemple :\n";
    echo "\033[1;31mphp " . $GLOBALS["filename"] . " \033[1;32mmysql\033[40m\033[1;37m pour avoir l'aide pour utiliser le script avec mysql\033[0m\n";
} elseif ($argv[1] === "liste") {
    echo "\033[1;37m\033[40mVoici la liste des types de base de donnée supporter :\n";
    echo "\033[1;32mmysql\033[0m\n";
} elseif (strtolower($argv[1]) === "mysql") {
    if (count($argv) === 2) {
        echo "\033[1;37m\033[40mAide pour utiliser le script avec mysql :\n";
        echo "\033[1;31mphp " . $GLOBALS["filename"] . " \033[1;32mmysql [host] [username] [password] [database]\n";
        echo "\033[1;31mtapez \033[1;32mdebug\033[1;31m avant \033[1;32mmysql \033[1;37m pour avoir les erreurs originelle\n";
        echo "\033[1;32m[type]\033[1;37m     => le type de base de donnée (ici mysql)\n";
        echo "\033[1;32m[host]\033[1;37m     => le serveur (localhost si vous êtes en dev)\n";
        echo "\033[1;32m[username]\033[1;37m => le nom d'utilisateur pour ce connecter à votre mysql\n";
        echo "\033[1;32m[password]\033[1;37m => le mot de passe pour ce connecter à votre mysql \033[1;31mecrire [] si vous avez un mot de passe vide !!\n";
        echo "\033[1;32m[database]\033[1;37m => le nom de la base de donnée que vous voulez crée\033[0m\n";
        echo "\033[40m\033[1;37mExemple :\n";
        echo "\033[1;31mphp " . $GLOBALS["filename"] . " \033[1;32mmysql localhost root pass bddtest\033[40m\033[1;37m\033[0m\n";
    } elseif (count($argv) < 6) {
        not_argv_enough("mysql");
    } elseif (count($argv) === 6) {
        $host     = $argv[2];
        $username = $argv[3];
        $password = $argv[4];
        $database = $argv[5];
        function_mysql($host, $username, $password, $database);
    } elseif (count($argv) > 6) {
        too_much_argv_enough("mysql");
    }
} elseif (strtolower($argv[1]) === "debug") {
    if (count($argv) === 2) {
    } elseif (count($argv) > 2 && strtolower($argv[2]) === "mysql") {
        if (count($argv) < 7) {
            not_argv_enough("mysql");
        } elseif (count($argv) === 7) {
            $host     = $argv[3];
            $username = $argv[4];
            $password = $argv[5];
            $database = $argv[6];
            function_debug_mysql($host, $username, $password, $database);
        } elseif (count($argv) > 7) {
            too_much_argv_enough("mysql");
        }
    }
} else {
    echo "\033[1;33m\033[40mErreur !! \033[40m\033[1;37mTapez\033[1;31m php " . $GLOBALS["filename"] . " help\033[40m\033[1;37m pour avoir de l'aide !!\033[0m\n";
}
?>