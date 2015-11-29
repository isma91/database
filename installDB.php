<?php
$filename = basename(__FILE__);
if (count($argv) === 1) {
    echo "Aide pour utiliser ce script PHP : \n";
    echo "php " . $filename . " [host] [username] [password] [database]\n";
    echo "[host]     => le serveur (localhost si vous êtes en dev)\n";
    echo "[username] => le nom d'utilisateur pour ce connecter à votre mysql\n";
    echo "[password] => le mot de passe pour ce connecter à votre mysql\n";
    echo "[database] => le nom de la base de donnée que vous voulez crée\n";
} elseif ($argv[1] === "help") {
    echo "Aide pour utiliser ce script PHP : \n";
    echo "php " . $filename . " [host] [username] [password] [database]\n";
    echo "[host]     => le serveur (localhost si vous êtes en dev)\n";
    echo "[username] => le nom d'utilisateur pour ce connecter à votre mysql\n";
    echo "[password] => le mot de passe pour ce connecter à votre mysql\n";
    echo "[database] => le nom de la base de donnée que vous voulez crée\n";
} elseif (count($argv) !== 5 && $argv[1] !== "help") {
    echo "Erreur !! Tapez 'php " . $filename . " help' pour avoir de l'aide !!\n";
} elseif (count($argv) === 5) {
    $host     = $argv[1];
    $username = $argv[2];
    $password = $argv[3];
    $database = $argv[4];
    try {
        $bdd = new PDO("mysql:host=" . $host . ";", $username, $password);
        $bdd->exec("CREATE DATABASE IF NOT EXISTS `$database`;");
        echo "Base de donnée '$database' crée avec succès !!\n";
    } catch (PDOException $e) {
        echo "Erreur dans la bdd : " . $e->getMessage();
    }
}
?>