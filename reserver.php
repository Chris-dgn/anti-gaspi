<?php
session_start();
require "connexion.php";

if (!isset($_SESSION["client_id"])) {
    echo "<p class='text-center text-red-600'>Vous devez être connecté pour réserver. <a href='connexion-client.php' class='underline'>Se connecter</a></p>";
    exit;
}

if (isset($_POST["panier_id"])) {
    $panierId = $_POST["panier_id"];
    $clientId = $_SESSION["client_id"];
    
    // Vérifier que le panier existe encore et a du stock
    $requete = $pdo->prepare("SELECT * FROM paniers WHERE id = :id AND quantite_disponible > 0");
    $requete->execute(["id" => $panierId]);
    $panier = $requete->fetch();
    
    if ($panier) {
        // Créer la réservation
        $requete = $pdo->prepare("INSERT INTO reservations (panier_id, client_id, quantite, statut) VALUES (:panier_id, :client_id, 1, 'en_attente')");
        $requete->execute(["panier_id" => $panierId, "client_id" => $clientId]);
        
        // Diminuer le stock du panier
        $requete = $pdo->prepare("UPDATE paniers SET quantite_disponible = quantite_disponible - 1 WHERE id = :id");
        $requete->execute(["id" => $panierId]);
        
        echo "<p class='text-center text-green-600 font-bold'>Réservation confirmée ! <a href='liste-paniers.php' class='underline'>Retour à la liste</a></p>";
    } else {
        echo "<p class='text-center text-red-600 font-bold'>Ce panier n'est plus disponible.</p>";
    }
}
?>