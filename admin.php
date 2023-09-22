<!-- Vous devez également créer une seconde page, qui permettra de se connecter en tant 
qu’administrateur afin d’avoir la liste des formulaires renseignés par les clients. -->


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Admin</title>
</head>
<body>
    <?php
        // page accessible uniquement par l'utilisateur admin (voir index.php)
        session_start();
        if ($pseudo == 'admin'){
            $resultat['level'] = 1;
        }
        if ($resultat['level'] != 1) {
            header('Location: index.php');
        }
        // connexion à la base de données
        include_once 'connect.php';

        // récupération des données du formulaire de la page index.php en créant une variable mysql

        $mysql = "SELECT * FROM reservations";
        $resultat = $db->query($mysql);

        // on récupère les données de la base de données et on les affiche dans le tableau déjà créé en html
        if ($resultat->rowCount() == 0) {
            echo "<p>Aucun résultat</p>";
        } else {
            echo "<table>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>id</th>";
            echo "<th>nom</th>";
            echo "<th>prénom</th>";
            echo "<th>adresse</th>";
            echo "<th>email</th>";
            echo "<th>téléphone</th>";
            echo "<th>zone</th>";
            echo "<th>quantité</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            while ($ligne = $resultat->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . $ligne['id_reservation'] . "</td>";
                echo "<td>" . $ligne['name_reservation'] . "</td>";
                echo "<td>" . $ligne['firstname_reservation'] . "</td>";
                echo "<td>" . $ligne['adress_reservation'] . "</td>";
                echo "<td>" . $ligne['mail_reservation'] . "</td>";
                echo "<td>" . $ligne['phone_reservation'] . "</td>";
                echo "<td>" . $ligne['zone_reservation'] . "</td>";
                echo "<td>" . $ligne['quantite_reservation'] . "</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        }

        // $password = "fghfgh";
        // $hash = password_hash($password, PASSWORD_DEFAULT);
        // // affichage du mot de passe hashé
        // echo $hash;
        // Quand l'utilisateur se déconnecte on le renvoie sur la page index.php et on détruit la session
        if (isset($_POST['deconnexion'])) {
            session_destroy();
            header('Location: index.php');
        }
    ?>
    <!-- // si l'utilisateur se déconnecte on le renvoie sur la page index.php -->
    <form action="index.php" method="post">
    <input type="submit" value="Se déconnecter">
    </form>
</body>
</html>