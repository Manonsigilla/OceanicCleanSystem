<?php
    // Manipulation de base de données avec PDO

    // Connexion à la base de données

    try {
        $db = new PDO(
            'mysql:host=localhost;dbname=oceaniccleansystem;charset=utf8',
            'admin', // nom d'utilisateur
            'AdminOceanic1234', // password
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION // Active la gestion des erreurs
            ]
        );
    } catch (Exception $e) {
        echo "Connexion refusée à la base de données";
        // écriture de la vraie erreur dans un fichier log
        // echo "Error: ".$e->getMessage();
        exit();
    }
?>