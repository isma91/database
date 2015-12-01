#!/usr/bin/env php
<?php
ini_set('xdebug.var_display_max_depth', -1);
ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);
$filename = basename(__FILE__);
function function_mysql ($host, $username, $password, $database) {
    if ($password === "[]") {
        $password = "";
    }
    try {
        $bdd = new PDO("mysql:host=" . $host . ";", $username, $password);
        $bdd->exec("CREATE DATABASE IF NOT EXISTS `$database`;");
        echo "\033[40m\033[1;37mLa base de donnée \033[1;32m" . $database . " \033[1;37m a été crée avec succes pour l'utilisateur \033[1;32m" . $username . "\033[1;37m dans \033[1;32m" . $host ." !!\033[0m\n";
    } catch (PDOException $exception) {
        if ($exception->getCode() === 2005) {
        }
        echo "le message d'erreur :\n";
        var_dump($exception->getMessage());
        echo "\nle code :\n";
        var_dump($exception->getCode());
        echo "\nla fonction :\n";
        var_dump($exception->getTrace()[1]['function']);
        echo "\nla ligne d'erreur dans la fonction : \n";
        var_dump($exception->getLine());
        echo "\nla ligne ou l'erreur s'est produit : \n";
        var_dump($exception->getTrace()[1]['line']);
    }
}
if (count($argv) === 1) {
    echo "\033[1;33m\033[40mPas d'argument !!\033[0m\n";
    echo "\033[40m\033[1;37mtapez\033[1;31m php " . $filename . " \033[1;32mhelp\033[40m\033[1;37m pour avoir de l'aide !!\033[0m\n";
} elseif ($argv[1] === "help") {
    echo "\033[1;37m\033[40mAide pour utiliser ce script PHP :\n";
    echo "\033[40m\033[1;37mtapez\033[1;31m php " . $filename . " \033[1;32mliste\033[40m\033[1;37m pour avoir la liste des types de base de donnée compatibles !!\n";
    echo "\033[40m\n";
    echo "\033[40m\033[1;37mtapez\033[1;31m php " . $filename . " \033[1;32m[type]\033[40m\033[1;37m pour avoir l'aide pour utiliser le script avec le type de base de donnée choisit !!\033[0m\n";
    echo "\033[40m\033[1;37mExemple :\n";
    echo "\033[1;31mphp " . $filename . " \033[1;32mmysql\033[40m\033[1;37m pour avoir l'aide pour utiliser le script avec mysql\033[0m\n";
} elseif ($argv[1] === "liste") {
    echo "\033[1;37m\033[40mVoici la liste des types de base de donnée supporter :\n";
    echo "\033[1;32mmysql\033[0m\n";
} elseif (strtolower($argv[1]) === "mysql") {
    if (count($argv) === 2) {
        echo "\033[1;37m\033[40mAide pour utiliser le script avec mysql :\n";
        echo "\033[1;31mphp " . $filename . " \033[1;32m[type] [host] [username] [password] [database]\n";
        echo "\033[1;32m[type]\033[1;37m     => le type de base de donnée (ici mysql)\n";
        echo "\033[1;32m[host]\033[1;37m     => le serveur (localhost si vous êtes en dev)\n";
        echo "\033[1;32m[username]\033[1;37m => le nom d'utilisateur pour ce connecter à votre mysql\n";
        echo "\033[1;32m[password]\033[1;37m => le mot de passe pour ce connecter à votre mysql \033[1;31mecrire [] si vous avez un mot de passe vide !!\n";
        echo "\033[1;32m[database]\033[1;37m => le nom de la base de donnée que vous voulez crée\033[0m\n";
        echo "\033[40m\033[1;37mExemple :\n";
        echo "\033[1;31mphp " . $filename . " \033[1;32mmysql localhost root pass bddtest\033[40m\033[1;37m\033[0m\n";
    } elseif (count($argv) < 6) {
        echo "\033[1;33m\033[40mPas assez d'argument !!\033[0m\n";
        echo "\033[40m\033[1;37mtapez\033[1;31m php " . $filename . " \033[1;32mhelp\033[40m\033[1;37m pour avoir de l'aide !!\033[0m\n";
        echo "\033[40m\033[1;37mtapez\033[1;31m php " . $filename . " \033[1;32mmysql\033[40m\033[1;37m pour avoir l'aide pour utiliser le script avec mysql\033[0m\n";
    }
    if (count($argv) === 6) {
        $host     = $argv[2];
        $username = $argv[3];
        $password = $argv[4];
        $database = $argv[5];
        function_mysql($host, $username, $password, $database);
    }
} else {
    echo "\033[1;33m\033[40mErreur !! \033[40m\033[1;37mTapez\033[1;31m php " . $filename . " help\033[40m\033[1;37m pour avoir de l'aide !!\033[0m\n";
}
?>