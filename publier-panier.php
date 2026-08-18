<?php
session_start();
require "connexion.php";

if (!isset($_SESSION["commercant_id"])) {
    echo "<p class='text-center text-red-600 font-bold mb-4'>Vous devez être connecté pour publier un panier. <a href='connexion-commercant.php' class='underline'>Se connecter</a></p>";
    exit;
}

if (isset($_POST["titre"]) && isset($_POST["prix_normal"])) {
    $titre = $_POST["titre"];
    $description = $_POST["description"];
    $prixNormal = $_POST["prix_normal"];
    $prixReduit = $_POST["prix_reduit"];
    $quantite = $_POST["quantite"];
    $creneauDebut = $_POST["creneau_debut"];
    $creneauFin = $_POST["creneau_fin"];
    
    $commercantId = $_SESSION["commercant_id"];
    
    $requete = $pdo->prepare("INSERT INTO paniers (commercant_id, titre, description, prix_normal, prix_reduit, quantite_disponible, creneau_debut, creneau_fin, statut) VALUES (:commercant_id, :titre, :description, :prix_normal, :prix_reduit, :quantite, :creneau_debut, :creneau_fin, 'disponible')");
    
    $requete->execute([
        "commercant_id" => $commercantId,
        "titre" => $titre,
        "description" => $description,
        "prix_normal" => $prixNormal,
        "prix_reduit" => $prixReduit,
        "quantite" => $quantite,
        "creneau_debut" => $creneauDebut,
        "creneau_fin" => $creneauFin
    ]);
    
    echo "<p class='text-center text-green-600 font-bold mb-4'>Panier publié avec succès !</p>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Publier un panier</title>
</head>
<body class="bg-gray-100 p-6">
    <h1 class="text-2xl font-bold text-center mb-2">Publier un panier surprise</h1>
    <p class="text-center text-gray-500 mb-6">Connecté en tant que : <?php echo $_SESSION["nom_boutique"]; ?></p>

    <form action="" method="POST" class="max-w-md mx-auto bg-white p-6 rounded-lg shadow">
        <input type="text" name="titre" placeholder="Titre du panier" class="border border-gray-300 rounded p-2 w-full mb-3">
        <input type="text" name="description" placeholder="Description" class="border border-gray-300 rounded p-2 w-full mb-3">
        <input type="number" name="prix_normal" placeholder="Prix normal (FCFA)" class="border border-gray-300 rounded p-2 w-full mb-3">
        <input type="number" name="prix_reduit" placeholder="Prix réduit (FCFA)" class="border border-gray-300 rounded p-2 w-full mb-3">
        <input type="number" name="quantite" placeholder="Quantité disponible" class="border border-gray-300 rounded p-2 w-full mb-3">
        <input type="time" name="creneau_debut" class="border border-gray-300 rounded p-2 w-full mb-3">
        <input type="time" name="creneau_fin" class="border border-gray-300 rounded p-2 w-full mb-3">
        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white rounded p-2 w-full">Publier</button>
    </form>
</body>
</html> 