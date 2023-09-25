<?php  
/*
    Avant d'afficher quoi que ce soit sur cette page :
        - Contrôler si l'utilisateur est connecté
        - Contrôler si l'utilisateur à les droits de modification (admin)
        - Vérifier si l'id de la commande existe
        - Vérifier si un id a bien été envoyé dans l'url
    
*/

// On traite le formulaire pour mettre à jour les données si le formulaire a été envoyé
if(isset($_POST['id']) && !empty($_POST['id'])){
    require_once "connect.php";

        // On prépare notre requête
        $requete = "UPDATE reservations SET name_reservation = :nom, firstname_reservation = :prenom, adress_reservation = :adresse, mail_reservation = :email, phone_reservation = :telephone, zone_reservation = :surface, quantite_reservation = :quantite WHERE id_reservation = :id";

        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $adresse = $_POST['adresse'];
        $email = $_POST['email'];
        $telephone = $_POST['telephone'];
        $zone = $_POST['zone'];
        $quantite = $_POST['quantite'];
        $id = $_POST['id'];

        try {
            $requete = $db->prepare($requete);

            $requete->execute(array(
                "nom" => $nom,
                "prenom" => $prenom,
                "adresse" => $adresse,
                "email" => $email,
                "telephone" => $telephone,
                "surface" => $zone,
                "quantite" => $quantite,
                "id" => $id
            ));

            // On redirige sur l'admin
            header('location: admin.php');
        } catch(Exception $e){
            die("Erreur d'ajout de données : " .$e->getMessage());
        }
}


// Connexion à la BDD et récupération des données de commande
require_once "connect.php";

$requete = "SELECT * FROM reservations WHERE id_reservation = :id";

$requete = $db->prepare($requete);

$requete->execute(array(
    "id" => $_GET['id']
));

$data = $requete->fetch();

?>
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
<section id="contact">
    <form action="#" method="post">
        <input type="text" name="nom" value="<?=$data['name_reservation'];?>" placeholder="Nom">
        <input type="text" name="prenom" value="<?=$data['firstname_reservation'];?>" placeholder="Prénom">
        <input type="text" name="adresse" value="<?=$data['adress_reservation'];?>" placeholder="Adresse">
        <input type="email" name="email" value="<?=$data['mail_reservation'];?>" placeholder="Email">
        <input type="tel" name="telephone" value="<?=$data['phone_reservation'];?>" placeholder="Téléphone">
        <div>
            <input type="number" name="zone" value="<?=$data['zone_reservation'];?>" id="zone" placeholder="Taille de la zone à traiter">
            <input type="number" name="quantite" value="<?=$data['quantite_reservation'];?>" id="quantite" placeholder="Quantité de produit">
        </div>
        <input type="hidden" name="id" value="<?=$data['id_reservation']?>">
        <input type="submit" value="Modifier la commande" name="submit">
    </form>
</section>
<script src="script.js"></script>
</body>
</html>