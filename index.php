<!-- Vous avez été embauché par la startup Oceanic Clean System. Après plus de 5 ans de recherche et 
développement, cette startup jeune et innovante a mis au point un système permettant de se 
débarrasser des amas de plastiques dans l’océan. Ce système est composé d’un agent liant naturel 
qui, couplé à l’utilisation de vers de cire, permet de se débarrasser de 100% du plastique flottant à la 
surface des océans.
Oceanic Clean System a aujourd’hui besoin d’un site internet afin de vendre leur système de 
nettoyage 100% naturel.
Pour le MVP du site, celui-ci devra contenir :
- Un formulaire pour renseigner nom, prénom, adresse, email et numéro de téléphone du 
client
Le site sera un « One Page », vous ne devrez donc vous concentrer que sur une page pour le client.
Les données de formulaire ainsi que la quantité de produits nécessaire et la taille de la zone à traiter 
renseignées par le client devront être enregistrées en base de données.
Vous devez également créer une seconde page, qui permettra de se connecter en tant 
qu’administrateur afin d’avoir la liste des formulaires renseignés par les clients.
Par exemple, si le client a besoin de 1.8 litre, le programme devra afficher 2 litres. De même que si le 
client a besoin de 1.1 litre, le programme doit arrondir toujours au supérieur. Il doit donc afficher 2 
litres. -->
<!-- // admin -->
<!-- // Mot de passe : AdminOceanic1234 -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oceanic Clean System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css" integrity="sha512-NhSC1YmyruXifcj/KFRWoC561YpHpc5Jtzgvbuzx5VozKpWvQ+4nXhPdFgmx8xqexRcpAglTj9sIBWINXa8x5w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <a href="#">Oceanic Clean System</a>
        <nav>
            <ul>
                <li><a href="#">Le produit</a></li>
                <li><a href="#">Calculateur</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </nav>
    </header>
    <main>
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
                    $requete = "SELECT password_user as password, pseudo_user as pseudo, niveau_user as level FROM users WHERE pseudo_user = :pseudo";
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
                            if($resultat['level'] == 1){
                                $_SESSION['admin'] = $resultat['level'];
                                header('Location: admin.php');
                            } else {
                                echo "<p>Bienvenue $pseudo !</p>";
                                header('Location: index.php');
                            }
                        }
                    }
                }
            }
            
        ?>
        <form action="#" method="post" class="connexion">
            <input type="text" name="pseudo" id="pseudo" placeholder="pseudo" required>
            <input type="password" name="password" id="password" placeholder="Mot de passe" required>
            <input type="submit" value="Se connecter" name="submit">
            <?php
                // on affiche le message d'erreur en passant à la ligne
                echo $errorMessage;
            ?>
        </form>
        <section id="produit">
            <img src="image-gauche.png" alt="Image gauche">
            <div>
                <div>
                    <h2>Le produit</h2>
                    <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Commodi, amet, nihil sapiente numquam magnam distinctio pariatur quam molestias maiores vitae necessitatibus qui ducimus dolorum beatae voluptatem iusto libero rerum similique?</p>
                    <a href="#">Calculateur de quantité</a>
                </div>
            </div>
        </section>
        <section id="calculateur">
            <h2>Calculateur de quantité</h2>
            <p>Veuillez renseigner la taille de la surface à nettoyer afin d'obtenir la quantité de litres nécessaires pour le nettoyage.</p>
            <!-- un outil permettant de calculer automatiquement la quantité de produit nécessaire en 
                fonction de la taille de la zone à traiter.
                Pour le calcul de quantité produit : 1 litre de produit permet de nettoyer 42m² d’océan.
                Le produit n’est vendu que par bidon d’un litre minimum. Dans votre calcul vous devez donc toujours 
                arrondir au chiffre supérieur.-->
            <?php
                if(isset($_GET['submit'])) {
                    // on définit la valeur de la variable quantite
                    $quantite = $_GET['quantite'];
                    // on définit la valeur de la variable resultat en précisant que c'est un nombre décimal et on arrondit le résultat au chiffre supérieur
                    $resultat = ceil((float) $quantite / 42);
                    // on affiche le résultat
                    echo "<p>Vous avez besoin de $resultat litres de produit pour nettoyer $quantite m².</p>";
                }
            ?>
            <form action="#calculateur" method="get">
                <input type="number" name="quantite" placeholder="Quantité en m²">
                <input type="submit" value="Calculer" name="submit">
            </form>
        </section>
        <section id="contact">
            <div>
                <div>
                    <h2>Réservez vos quantités</h2>
                    <p>Veuillez utiliser le formulaire ci-dessous afin de réserver vos quantité. N'oubliez pas de renseigner tous les champs afin que nous puissions prendre contact avec vous rapidement.</p>

                    <?php
                        $errorMessage = "";
                        $isEverythingOk = true;

                        // On vérifie si les champs ne sont pas vides
                        if(isset($_POST['submit'])) {
                            // on récupère les données du formulaire dans des variables
                            $nom = $_POST['nom'];
                            $prenom = $_POST['prenom'];
                            $adresse = $_POST['adresse'];
                            $email = $_POST['email'];
                            $telephone = $_POST['telephone'];
                            $zone = $_POST['zone'];
                            $quantite = $_POST['quantite'];
                            // on vérifie que l'email est valide
                            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                                $errorMessage = "<p>Adresse mail invalide</p>";
                                $isEverythingOk = false;
                            }
                            // on vérifie que le numéro de téléphone est valide
                            if(!preg_match("#^0[1-68]([-. ]?[0-9]{2}){4}$#", $telephone)){
                                $errorMessage = "<p>Numéro de téléphone invalide</p>";
                                $isEverythingOk = false;
                            }
                            // on vérifie que la quantité est supérieure à 0
                            if($quantite <= 0){
                                $errorMessage = "<p>La quantité doit être supérieure à 0</p>";
                                $isEverythingOk = false;
                            }
                            // on vérifie que la zone est supérieure à 0
                            if($zone <= 0){
                                $errorMessage = "<p>La zone doit être supérieure à 0</p>";
                                $isEverythingOk = false;
                            }
                            // si les champs ne sont pas vides, que l'email est valide, que le numéro de téléphone est valide, que la quantité est supérieure à 0 et que la zone est supérieure à 0 alors on peut insérer les données dans la base de données
                            if($isEverythingOk){
                                require_once('connect.php');
                                // On écrit la requête qui permet d'insérer les données dans la base de données
                                $requete = "INSERT INTO reservations(name_reservation, firstname_reservation, adress_reservation, mail_reservation, phone_reservation, zone_reservation, quantite_reservation) VALUES(:nom, :prenom, :adresse, :email, :telephone, :zone, :quantite)";
                                // On prépare la requête
                                $requete = $db->prepare($requete);
                                // On exécute la requête avec les bonnes données
                                $requete->execute(array(
                                    "nom" => $nom,
                                    "prenom" => $prenom,
                                    "adresse" => $adresse,
                                    "email" => $email,
                                    "telephone" => $telephone,
                                    "zone" => $zone,
                                    "quantite" => $quantite
                                ));
                                // on félicite l'utilisateur pour sa commande
                                $errorMessage = "<p>Votre réservation a bien été prise en compte !</p>";
                            }
                        }
                    ?>

                    <form action="#contact" method="post">
                        <input type="text" name="nom" value="<?= $nom ?>" placeholder="Nom" required>
                        <input type="text" name="prenom" value="<?= $prenom ?>" placeholder="Prénom" required>
                        <input type="text" name="adresse" value="<?= $adresse ?>" placeholder="Adresse" required>
                        <input type="email" name="email" value="<?= $email ?>" placeholder="Email" required>
                        <input type="tel" name="telephone" value="<?= $telephone ?>" placeholder="Téléphone" required>
                        <div>
                            <input type="number" name="zone" id="zone" value="<?= $zone ?>" placeholder="Taille de la zone à traiter" required>
                            <input type="number" name="quantite" id="quantite" value="<?= $quantite ?>" placeholder="Quantité de produit" required>
                        </div>
                        <input type="submit" value="Réserver le produit" name="submit">
                        <?php
                            // on affiche le message d'erreur en passant à la ligne
                            echo $errorMessage;
                        ?>
                    </form>
                </div>
            </div>
            <img src="image-droite.png" alt="Image droite">
        </section>
    </main>
    <script src="script.js"></script>
</body>
</html>