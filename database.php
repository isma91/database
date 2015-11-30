<?php
ini_set('xdebug.var_display_max_depth', -1);
ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);
$filename = basename(__FILE__);
if (count($argv) === 1) {
    echo "Aide pour utiliser ce script PHP : \n";
    echo "php " . $filename . " [type] [host] [username] [password] [database]\n";
    echo "[type]     => le type de base de donnée (généralement mysql)\n";
    echo "[host]     => le serveur (localhost si vous êtes en dev)\n";
    echo "[username] => le nom d'utilisateur pour ce connecter à votre mysql\n";
    echo "[password] => le mot de passe pour ce connecter à votre mysql\n";
    echo "[database] => le nom de la base de donnée que vous voulez crée\n";
} elseif ($argv[1] === "help") {
    echo "Aide pour utiliser ce script PHP : \n";
    echo "php " . $filename . " [type] [host] [username] [password] [database]\n";
    echo "[type]     => le type de base de donnée (généralement mysql)\n";
    echo "[host]     => le serveur (localhost si vous êtes en dev)\n";
    echo "[username] => le nom d'utilisateur pour ce connecter à votre mysql\n";
    echo "[password] => le mot de passe pour ce connecter à votre mysql\n";
    echo "[database] => le nom de la base de donnée que vous voulez crée\n";
} elseif (count($argv) !== 6 && $argv[1] !== "help") {
    echo "Erreur !! Tapez 'php " . $filename . " help' pour avoir de l'aide !!\n";
} elseif (count($argv) === 6) {
    $type     = $argv[1];
    $host     = $argv[2];
    $username = $argv[3];
    $password = $argv[4];
    $database = $argv[5];
    try {
        $bdd = new PDO($type . ":host=" . $host . ";", $username, $password);
        $bdd->exec("CREATE DATABASE IF NOT EXISTS `$database`;");
    } catch (PDOException $exception) {
        echo "le message d'erreur : \n";
        var_dump($exception->getMessage());
        echo "\nla ligne d'erreur : \n";
        var_dump($exception->getCode());
    }
}
?>