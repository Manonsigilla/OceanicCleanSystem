<?php 
// Ici on devrait normalement tester le niveau de compte avant de faire la suppression
if(!empty($_GET['id'])){
    require_once "connect.php";
    $id = $_GET['id'];

    $requete = "DELETE FROM reservations WHERE id_reservation = :id";

    $requete = $db->prepare($requete);

    $requete->execute(array(
        "id" => $id
    ));

    header('location: admin.php');

}


?>