<?php
require "connexion.php";

if (isset($_POST["nom_boutique"]) && isset($_POST["email"]) && isset($_POST["mot_de_passe"])) {
    $nomBoutique = $_POST["nom_boutique"];
    $email = $_POST["email"];
    $motDePasseHache = password_hash($_POST["mot_de_passe"], PASSWORD_DEFAULT);
    $adresse = $_POST["adresse"];
    $telephone = $_POST["telephone"];
    
    $requete = $pdo->prepare("INSERT INTO commercants (nom_boutique, email, mot_de_passe, adresse, telephone) VALUES (:nom_boutique, :email, :mot_de_passe, :adresse, :telephone)");
    $requete->execute([
        "nom_boutique" => $nomBoutique,
        "email" => $email,
        "mot_de_passe" => $motDePasseHache,
        "adresse" => $adresse,
        "telephone" => $telephone
    ]);
    
    echo "<p class='text-center text-green-600 font-bold mb-4'>Inscription réussie !</p>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Inscription commerçant</title>
</head>
<body class="bg-gray-100 p-6">
    <h1 class="text-2xl font-bold text-center mb-6">Inscription commerçant</h1>

    <form action="" method="POST" class="max-w-md mx-auto bg-white p-6 rounded-lg shadow">
        <input type="text" name="nom_boutique" placeholder="Nom de la boutique" class="border border-gray-300 rounded p-2 w-full mb-3">
        <input type="email" name="email" placeholder="Email" class="border border-gray-300 rounded p-2 w-full mb-3">
        <input type="password" name="mot_de_passe" placeholder="Mot de passe" class="border border-gray-300 rounded p-2 w-full mb-3">
        <input type="text" name="adresse" placeholder="Adresse" class="border border-gray-300 rounded p-2 w-full mb-3">
        <input type="text" name="telephone" placeholder="Téléphone" class="border border-gray-300 rounded p-2 w-full mb-3">
        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white rounded p-2 w-full">S'inscrire</button>
    </form>
</body>
</html>