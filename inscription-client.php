<?php
require "connexion.php";

if (isset($_POST["nom"]) && isset($_POST["email"]) && isset($_POST["mot_de_passe"])) {
    $nom = $_POST["nom"];
    $email = $_POST["email"];
    $motDePasseHache = password_hash($_POST["mot_de_passe"], PASSWORD_DEFAULT);
    $telephone = $_POST["telephone"];
    
    $requete = $pdo->prepare("INSERT INTO clients (nom, email, mot_de_passe, telephone) VALUES (:nom, :email, :mot_de_passe, :telephone)");
    $requete->execute([
        "nom" => $nom,
        "email" => $email,
        "mot_de_passe" => $motDePasseHache,
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
    <title>Inscription client</title>
</head>
<body class="bg-gray-100 p-6">
    <h1 class="text-2xl font-bold text-center mb-6">Inscription client</h1>

    <form action="" method="POST" class="max-w-md mx-auto bg-white p-6 rounded-lg shadow">
        <input type="text" name="nom" placeholder="Votre nom" class="border border-gray-300 rounded p-2 w-full mb-3">
        <input type="email" name="email" placeholder="Email" class="border border-gray-300 rounded p-2 w-full mb-3">
        <input type="password" name="mot_de_passe" placeholder="Mot de passe" class="border border-gray-300 rounded p-2 w-full mb-3">
        <input type="text" name="telephone" placeholder="Téléphone" class="border border-gray-300 rounded p-2 w-full mb-3">
        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white rounded p-2 w-full">S'inscrire</button>
    </form>
</body>
</html>