<!-- Vous devez également créer une seconde page, qui permettra de se connecter en tant 
qu’administrateur afin d’avoir la liste des formulaires renseignés par les clients. -->
<?php
            session_start();
            $errorMessage = "";
            $pseudo = isset($_POST["pseudo"]) ? $_POST["pseudo"] : "";
            $password = isset($_POST["password"]) ? $_POST["password"] : "";

            // on vérifie si les champs ne sont pas vides
            if(isset($_POST['submit'])) {
                // on vérifie que les champs ne sont pas vides
                if(!empty($_POST['pseudo']) && !empty($_POST['password'])) {
                    require_once('connect.php');
                    // On écrit la requête qui permet de récupérer les données de l'utilisateur
                    $requete = "SELECT password_user as password FROM users WHERE pseudo_user = :pseudo";
                    // On prépare la requête
                    $requete = $db->prepare($requete);
                    // On exécute la requête avec les bonnes données
                    $requete->execute(array(
                        "pseudo" => $pseudo
                    ));
                    // on récupère les données de la base de données
                    $resultat = $requete->fetch();
                    // si on obtient pas de résultat alors on affiche un message d'erreur
                    if(!$resultat) {
                        $errorMessage = "<p>Mauvais identifiant ou mot de passe !</p>";
                    }
                    // si c'est bon alors on peut vérifier le mot de passe
                    if($resultat){
                        if(!password_verify($password, $resultat['password'])){
                            $errorMessage = "<p>Mauvais identifiant ou mot de passe !</p>";
                        }
                        if(password_verify($password, $resultat['password'])){
                            // on crée une session pour l'utilisateur en tenant compte de son niveau d'utilisateur. Si l'utilisateur est un admin alors on le redirige vers la page admin.php et si l'utilisateur est un membre alors on le laisse sur la page index.php
                            $_SESSION['pseudo'] = $pseudo;
                            $_SESSION['password'] = $password;
                            // si c'est l'admin alors on lui attribue un niveau de connexion
                            $niveauRequete = "SELECT niveau_user as niveau FROM users WHERE pseudo_user = :pseudo";
                            $niveauRequete = $db->prepare($niveauRequete);
                            $niveauRequete->execute(array(
                                "pseudo" => $pseudo
                            ));
                            $resultatNiveau = $niveauRequete->fetch();
                            if($resultatNiveau && $resultatNiveau['niveau'] == 1){
                                $_SESSION['admin'] = $resultatNiveau['niveau'];
                                // page accessible uniquement par l'utilisateur admin (voir index.php)
                                if(isset($_SESSION['admin']) && $_SESSION['admin'] == 1){
                                    // connexion à la base de données
                                    include_once 'connect.php';

                                    // récupération des données du formulaire de la page index.php en créant une variable mysql

                                    $mysql = "SELECT * FROM reservations";
                                    $resultat = $db->query($mysql);

                                    // on récupère les données de la base de données et on les affiche dans le tableau déjà créé en html
                                    if ($resultat->rowCount() == 0) {
                                        echo "<p>Aucun résultat</p>";
                                    } else {
                                        // on affiche un titre
                                        echo "<h1>Liste des réservations</h1>";
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
                                        echo "<th>Modifier</th>";
                                        echo "<th>Supprimer</th>";
                                        echo "</tr>";
                                        echo "</thead>";
                                        echo "<tbody>";
                                        while ($ligne = $resultat->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<tr>";
                                            echo "<td>" . $ligne['id_reservation'] . "</td>";
                                            echo "<td>" . $ligne['firstname_reservation'] . "</td>";
                                            echo "<td>" . $ligne['name_reservation'] . "</td>";
                                            echo "<td>" . $ligne['mail_reservation'] . "</td>";
                                            echo "<td>" . $ligne['adress_reservation'] . "</td>";
                                            echo "<td>" . $ligne['zone_reservation'] . "</td>";
                                            echo "<td>" . $ligne['phone_reservation'] . "</td>";
                                            echo "<td>" . $ligne['quantite_reservation'] . "</td>";
                                            echo "<td><a href='edit.php?id=" . $ligne['id_reservation'] . "'>Modifier</a></td>";
                                            echo "<td><a href='delete.php?id=" . $ligne['id_reservation'] . "'>Supprimer</a></td>";
                                            echo "</tr>";
                                        }
                                            echo "</tbody>";
                                            echo "</table>";
                                    }
                                    echo "<a class='deconnect' href='?logout=true'>Se déconnecter</a>";
                                    // on détruit la session admin pour se déconnecter
                                    if(isset($_POST['submit'])) {
                                        session_destroy();
                                    }
                                } else {
                                echo "<p>Vous n'avez pas l'accès</p>";
                                // lien pour retourner sur l'index.php
                                echo "<a href='index.php'>Retour</a>";
                                }
                            }
                        }
                    }
                }
            }
            
        ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Admin</title>
</head>
<body>
    <!-- // afficher le formulaire seulement si l'admin n'est pas connecté -->
    <?php if(!isset($_SESSION['admin'])) { ?>
    <form action="#" method="post" class="connexion">
        <input type="text" name="pseudo" id="pseudo" placeholder="pseudo" required>
        <input type="password" name="password" id="password" placeholder="Mot de passe" required>
        <input type="submit" value="Se connecter" name="submit">
        <?php
            // on affiche le message d'erreur en passant à la ligne
            echo $errorMessage;
        ?>
    </form>
    <?php } ?>

        <!-- // $password = "fghfgh";
        // $hash = password_hash($password, PASSWORD_DEFAULT);
        // affichage du mot de passe hashé
        // echo $hash; -->
        
</body>
</html>