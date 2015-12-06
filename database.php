#!/usr/bin/env php
<?php
/*pour avoir un xdebug en entier*/
/*ini_set('xdebug.var_display_max_depth', -1);
ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);*/
$GLOBALS["filename"] = basename(__FILE__);
function compare_with_levenshtein ($array_argument_to_check, $array_to_compare) {
    $good_writted_arguments = array();
    $bad_writted_arguments = array();
    $distance = -1;
    foreach ($array_argument_to_check as $argument_to_check) {
        foreach ($array_to_compare as $argument_to_compare) {
            $levenshtein = levenshtein($argument_to_check, $argument_to_compare);
            if ($levenshtein === 0) {
                $closest = $argument_to_compare;
                $distance = 0;
                break;
            }
            if ($levenshtein <= $distance || $distance < 0) {
                $closest  = $argument_to_compare;
                $distance = $levenshtein;
            }
            if ($distance == 0) {
                array_push($good_writted_arguments, $closest);
                echo "Correspondance exacte trouvée : $closest\n";
            } else {
                array_push($bad_writted_arguments, $closest);
                echo "Vous voulez dire : $closest ?\n";
            }
        }
    }
}
$arguments = array("help", "mysql", "liste", "create:database", "create:table");
$array_argument = array();
if (count($argv) > 1) {
    for ($j=0; $j < count($argv); $j++) {
        if ($j >= 1) {
            array_push($array_argument, $argv[$j]);
        }
    }
}
compare_with_levenshtein($array_argument, $arguments);

function mysql_create_table ($host, $username, $password, $database, $table, $number) {
    if ($password === "[]") {
        $password = "";
    }
    try {
        echo "\033[1;37m\033[40mVerification des droits...\033[0m\n";
        $bdd = new PDO("mysql:host=" . $host . ";dbname=" . $database, $username, $password);
        $requete_privilege = $bdd->prepare("SELECT insert_priv FROM mysql.user WHERE user = '" . $username . "';");
        $requete_privilege->execute();
        $donnees_privilege = $requete_privilege->fetch();
        if (strtoupper($donnees_privilege["insert_priv"]) === "N") {
            echo "\033[1;33m\033[40mErreur !! \033[1;31m" . $username . "\033[1;33m n'a pas les droits pour crée un tableau !!\033[0m\n";
        } else {
            echo "\033[40m\033[1;32mBase de donnée trouvée !!\033[0m\n";
            if (is_numeric($number)) {
                $number = floor($number);
                $number = (int)$number;
                if ($number < 1) {
                    echo "\033[1;33m\033[40mErreur !!\033[1;31m [number]\033[1;33m doit être un nombre supérieur ou égal à 1 !!\033[0m\n";
                } else {
                    $create_table = "CREATE TABLE IF NOT EXISTS `$table`(";
                    for ($i=0; $i < $number; $i = $i + 1) {
                        echo "\033[1;37m\033[40mTapez le nom de la colonne : \033[0m";
                        $answer_create_table_colonne = fopen("php://stdin", "r");
                        $reponse_create_table_colonne = fgets($answer_create_table_colonne);
                        $reponse_create_table_colonne = trim($reponse_create_table_colonne);
                        $create_table = $create_table . " " . $reponse_create_table_colonne;
                        while (empty($reponse_create_table_colonne)) {
                            echo "\033[1;33m\033[40mErreur !!\033[1;33m une colonne ne peut pas avoir un nom vide !!\033[0m\n";
                            echo "\033[1;37m\033[40mTapez le nom de la colonne : \033[0m";
                            $answer_create_table_colonne = fopen("php://stdin", "r");
                            $reponse_create_table_colonne = fgets($answer_create_table_colonne);
                            $reponse_create_table_colonne = trim($reponse_create_table_colonne);
                            $create_table = $create_table . " " . $reponse_create_table_colonne;
                        }
                        echo "\033[1;37m\033[40mTapez le type de donnée que va avoir \033[1;32m" . $reponse_create_table_colonne . "\033[1;37m (ex: INT, VARCHAR(255), TEXT, DATE etc...) \033[0m";
                        $answer_create_table_type_donnée = fopen("php://stdin", "r");
                        $reponse_create_table_type_donnée = fgets($answer_create_table_type_donnée);
                        $reponse_create_table_type_donnée = trim($reponse_create_table_type_donnée);
                        $reponse_create_table_type_donnée = strtoupper($reponse_create_table_type_donnée);
                        $create_table = $create_table . " " . $reponse_create_table_type_donnée;
                        while (empty($reponse_create_table_type_donnée)) {
                            echo "\033[1;33m\033[40mErreur !!\033[1;33m une colonne ne peut pas avoir un type de donnée vide !!\033[0m\n";
                            echo "\033[1;37m\033[40mTapez le type de donnée que va avoir \033[1;32m" . $reponse_create_table_colonne . "\033[1;37m (ex: INT, VARCHAR(255), TEXT, DATE etc...) \033[0m";
                            $answer_create_table_type_donnée = fopen("php://stdin", "r");
                            $reponse_create_table_type_donnée = fgets($answer_create_table_type_donnée);
                            $reponse_create_table_type_donnée = trim($reponse_create_table_type_donnée);
                            $create_table = $create_table . " " . $reponse_create_table_type_donnée;
                        }
                        echo "\033[1;37m\033[40mTapez les options que va avoir \033[1;32m" . $reponse_create_table_colonne . "\033[1;37m (ex: NOT NULL, PRIMARY KEY, DEFAULT etc...)\033[1;31mMettez rien si vous ne voulez pas d'option pour \033[1;32m" . $reponse_create_table_colonne . "\033[1;37m \033[0m";
                        $answer_create_table_option = fopen("php://stdin", "r");
                        $reponse_create_table_option = fgets($answer_create_table_option);
                        $reponse_create_table_option = trim($reponse_create_table_option);
                        if ($number > 1 && $i !== ($number - 1)) {
                            $create_table = $create_table . " " . $reponse_create_table_option . ", ";
                        } else {
                            $create_table = $create_table . " " . $reponse_create_table_option;
                        }
                    }
                    $create_table = $create_table . " )";
                    echo "\033[1;37m\033[40mVoici la requete SQL final qui va crée votre tableau :\033[0m\n";
                    echo "\033[1;31m\033[40m" . $create_table . "\033[0m\n";
                    echo "\033[1;37m\033[40mVoulez-vous valider ? (Y/N) \033[0m\n";
                    $answer_create_table = fopen("php://stdin", "r");
                    $reponse_create_table = fgets($answer_create_table);
                    $reponse_create_table = trim($reponse_create_table);
                    $reponse_create_table = mb_strtoupper($reponse_create_table);
                    while ($reponse_create_table !== 'Y' && $reponse_create_table !== 'N') {
                        echo "\033[1;33m\033[40mVous n'avez pas ecrit Y ou N ...\033[0m\n";
                        echo "\033[1;37m\033[40mVoici la requete SQL final qui va crée votre tableau :\033[0m\n";
                        echo "\033[1;31m\033[40m" . $create_table . "\033[0m\n";
                        echo "\033[1;37m\033[40mVoulez-vous valider ? (Y/N) \033[0m\n";
                        $answer_create_table = fopen("php://stdin", "r");
                        $reponse_create_table = fgets($answer_create_table);
                        $reponse_create_table = trim($reponse_create_table);
                        $reponse_create_table = mb_strtoupper($reponse_create_table);
                    }
                    if ($reponse_create_table === "Y") {
                        echo "\033[40m\033[1;37mCréation du tableau \033[1;31m" . $table . "\033[1;37m dans la base de donnée \033[1;31m" . $database . "\033[1;37m ...\033[0m\n";
                        $bdd->exec($create_table);
                        echo "\033[40m\033[1;32mLe tableau \033[1;31m" . $table . "\033[1;32m a été crée dans la base de donnée \033[1;31m" . $database . "\033[1;32m !!\033[0m\n";
                    } elseif ($reponse_create_table === "N") {
                        echo "\033[40m\033[1;32mAu revoir !!\033[0m\n";
                    }
                }
            } else {
                echo "\033[1;33m\033[40mErreur !!\033[1;31m [number]\033[1;33m doit être un nombre !!\033[0m\n";
            }
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
        } elseif ($exception->getCode() === 1049) {
            echo "\033[1;33m\033[40mErreur !! La base de donnée \033[1;31m" . $database . "\033[1;33m n'existe pas dans le serveur \033[1;31m" . $host . "\033[1;33m !!\033[0m\n";
            echo "\033[1;37m\033[40mVoulez-vous crée la base de donnée \033[1;31m" . $database . "\033[1;37m dans le serveur \033[1;31m" . $host . "\033[1;37m ? (Y/N)\033[0m ";
            $answer_create_database = fopen("php://stdin", "r");
            $reponse_create_database = fgets($answer_create_database);
            $reponse_create_database = trim($reponse_create_database);
            $reponse_create_database = mb_strtoupper($reponse_create_database);
            while ($reponse_create_database !== 'Y' && $reponse_create_database !== 'N') {
                echo "\033[1;33m\033[40mVous n'avez pas ecrit Y ou N ...\n";
                echo "\033[1;37m\033[40mVoulez-vous crée la base de donnée \033[1;31m" . $database . "\033[1;37m dans le serveur \033[1;31m" . $host . "\033[1;37m ? (Y/N)\033[0m ";
                $answer_create_database = fopen("php://stdin", "r");
                $reponse_create_database = fgets($answer_create_database);
                $reponse_create_database = trim($reponse_create_database);
                $reponse_create_database = mb_strtoupper($reponse_create_database);
            }
            if ($reponse_create_database === "Y") {
                echo "\033[40m\033[1;32mCréation de la base de donnée !!\033[0m\n";
                mysql_create_database($host, $username, $password, $database);
                mysql_create_table($host, $username, $password, $database, $table, $number);
            } elseif ($reponse_create_database === "N") {
                echo "\033[40m\033[1;32mAu revoir !!\033[0m\n";
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
function mysql_create_database ($host, $username, $password, $database) {
    if ($password === "[]") {
        $password = "";
    }
    try {
        $bdd = new PDO("mysql:host=" . $host . ";", $username, $password);
        echo "\033[1;37m\033[40mVerification des droits...\033[0m\n";
        $requete_privilege = $bdd->prepare("SELECT insert_priv FROM mysql.user WHERE user = '" . $username . "';");
        $requete_privilege->execute();
        $donnees_privilege = $requete_privilege->fetch();
        if (strtoupper($donnees_privilege["insert_priv"]) === "N") {
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
function not_argv_enough ($type, $option = null) {
    if ($option === null) {
        echo "\033[1;33m\033[40mPas assez d'argument !!\033[0m\n";
        echo "\033[40m\033[1;37mtapez\033[1;31m ./" . $GLOBALS["filename"] . " \033[1;32mhelp\033[40m\033[1;37m pour avoir de l'aide !!\033[0m\n";
        echo "\033[40m\033[1;37mtapez\033[1;31m ./" . $GLOBALS["filename"] . " \033[1;32m" . $type . "\033[40m\033[1;37m pour avoir l'aide pour utiliser le script avec \033[1;32m" . $type . "\033[0m\n";
    } else {
        echo "\033[1;33m\033[40mPas assez d'argument !!\033[0m\n";
        echo "\033[40m\033[1;37mtapez\033[1;31m ./" . $GLOBALS["filename"] . " \033[1;32m" . $type . " " . $option . "\033[40m\033[1;37m pour avoir l'aide pour utiliser le script avec \033[1;32m" . $type . "\033[1;37m et utiliser l'option \033[1;32m" . $option . "\033[0m\n";
    }
}
function too_much_argv_enough ($type, $option = null) {
    if ($option === null) {
        echo "\033[1;33m\033[40mTrop d'argument !!\033[0m\n";
        echo "\033[40m\033[1;37mtapez\033[1;31m ./" . $GLOBALS["filename"] . " \033[1;32mhelp\033[40m\033[1;37m pour avoir de l'aide !!\033[0m\n";
        echo "\033[40m\033[1;37mtapez\033[1;31m ./" . $GLOBALS["filename"] . " \033[1;32m" . $type . "\033[40m\033[1;37m pour avoir l'aide pour utiliser le script avec \033[1;32m" . $type . "\033[0m\n";
    } else {
        echo "\033[1;33m\033[40mPas assez d'argument !!\033[0m\n";
        echo "\033[40m\033[1;37mtapez\033[1;31m ./" . $GLOBALS["filename"] . " \033[1;32m" . $type . " " . $option . "\033[40m\033[1;37m pour avoir l'aide pour utiliser le script avec \033[1;32m" . $type . "\033[1;37m et utiliser l'option \033[1;32m" . $option . "\033[0m\n";
    }
}
if (count($argv) === 1) {
    echo "\033[1;33m\033[40mPas d'argument !!\033[0m\n";
    echo "\033[40m\033[1;37mtapez\033[1;31m ./" . $GLOBALS["filename"] . " \033[1;32mhelp\033[40m\033[1;37m pour avoir de l'aide !!\033[0m\n";
} elseif ($argv[1] === "help") {
    echo "\033[1;37m\033[40mAide pour utiliser ce script PHP :\n";
    echo "\033[40m\033[1;37mtapez\033[1;31m ./" . $GLOBALS["filename"] . " \033[1;32mliste\033[40m\033[1;37m pour avoir la liste des types de base de donnée compatibles !!\n";
    echo "\033[40m\n";
    echo "\033[40m\033[1;37mtapez\033[1;31m ./" . $GLOBALS["filename"] . " \033[1;32m[type]\033[40m\033[1;37m pour avoir l'aide pour utiliser le script avec le type de base de donnée choisit !!\033[0m\n";
} elseif ($argv[1] === "liste") {
    echo "\033[1;37m\033[40mVoici la liste des types de base de donnée supporter :\n";
    echo "\033[1;32mmysql\033[0m\n";
} elseif (strtolower($argv[1]) === "mysql") {
    if (count($argv) === 2) {
        echo "\033[1;37m\033[40mAide pour utiliser le script avec mysql :\n";
        echo "\033[1;31m./" . $GLOBALS["filename"] . " \033[1;32mmysql [option] [argument]\n";
        echo "\033[1;37mTapez \033[1;31m./" . $GLOBALS["filename"] . " \033[1;32mmysql [option]\033[1;37m pour avoir l'aide de cet option\n";
        echo "\033[40m\033[1;37mOption :\n";
        echo "\033[1;32mcreate:database\033[1;37m => crée une base de donnée\n";
        echo "\033[1;32mcreate:table\033[1;37m    => crée une table dans une base de donnée\033[0m\n";
    } elseif ($argv[2] === "create:database") {
        if (count($argv) === 3) {
            echo "\033[1;37m\033[40mAide pour crée une base de donnée avec mysql :\n";
            echo "\033[1;31m./" . $GLOBALS["filename"] . " \033[1;32mmysql create:database [host] [username] [password] [database]\n";
            echo "\033[1;32m[host]\033[1;37m     => le serveur\n";
            echo "\033[1;32m[username]\033[1;37m => le nom d'utilisateur pour ce connecter à votre mysql\n";
            echo "\033[1;32m[password]\033[1;37m => le mot de passe pour ce connecter à votre mysql \033[1;31mecrire [] si vous avez un mot de passe vide !!\n";
            echo "\033[1;32m[database]\033[1;37m => le nom de la base de donnée que vous voulez crée\033[0m\n";
        } elseif (count($argv) < 7) {
            not_argv_enough("mysql", "create:database");
        } elseif (count($argv) === 7) {
            mysql_create_database($argv[3], $argv[4], $argv[5], $argv[6]);
        } elseif (count($argv) > 7) {
            too_much_argv_enough("mysql", "create:database");
        }
    } elseif ($argv[2] === "create:table") {
        if (count($argv) === 3) {
            echo "\033[1;37m\033[40mAide pour crée un tableau dans une base de donnée avec mysql :\n";
            echo "\033[1;31m./" . $GLOBALS["filename"] . " \033[1;32mmysql create:tableau [host] [username] [password] [database] [table] [number]\n";
            echo "\033[1;32m[host]\033[1;37m     => le serveur\n";
            echo "\033[1;32m[username]\033[1;37m => le nom d'utilisateur pour ce connecter à votre mysql\n";
            echo "\033[1;32m[password]\033[1;37m => le mot de passe pour ce connecter à votre mysql \033[1;31mecrire [] si vous avez un mot de passe vide !!\n";
            echo "\033[1;32m[database]\033[1;37m => le nom de la base de donnée ou vous voulez ajouter le tableau\n";
            echo "\033[1;32m[table]\033[1;37m    => le nom du tableau que vous voulez crée\n";
            echo "\033[1;32m[number]\033[1;37m    => le nombre de colonne à créer\033[0m\n";
        } elseif (count($argv) < 9) {
            not_argv_enough("mysql", "create:table");
        } elseif (count($argv) === 9) {
            mysql_create_table($argv[3], $argv[4], $argv[5], $argv[6], $argv[7], $argv[8]);
        } elseif (count($argv > 9)) {
            too_much_argv_enough("mysql", "create:table");
        }
    }
}else {
    echo "\033[1;33m\033[40mErreur !! \033[40m\033[1;37mTapez\033[1;31m ./" . $GLOBALS["filename"] . " help\033[40m\033[1;37m pour avoir de l'aide !!\033[0m\n";
}
?>