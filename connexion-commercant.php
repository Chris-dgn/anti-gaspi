<?php
session_start();
require "connexion.php";

if (isset($_POST["email"]) && isset($_POST["mot_de_passe"])) {
    $email = $_POST["email"];
    $motDePasseSaisi = $_POST["mot_de_passe"];

    $requete = $pdo->prepare("SELECT * FROM commercants WHERE email = :email");
    $requete->execute(["email" => $email]);
    $commercant = $requete->fetch();

    if ($commercant && password_verify($motDePasseSaisi, $commercant["mot_de_passe"])) {
        $_SESSION["commercant_id"] = $commercant["id"];
        $_SESSION["nom_boutique"] = $commercant["nom_boutique"];
        header("Location: publier-panier.php");
        exit;
    } else {
        echo "<p class='text-center text-red-600 font-bold mb-4'>Email ou mot de passe incorrect</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Connexion commerçant</title>
</head>
<body class="bg-gray-100 p-6">
    <h1 class="text-2xl font-bold text-center mb-6">Connexion commerçant</h1>

    <form action="" method="POST" class="max-w-md mx-auto bg-white p-6 rounded-lg shadow">
        <input type="email" name="email" placeholder="Email" class="border border-gray-300 rounded p-2 w-full mb-3">
        <input type="password" name="mot_de_passe" placeholder="Mot de passe" class="border border-gray-300 rounded p-2 w-full mb-3">
        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white rounded p-2 w-full">Se connecter</button>
    </form>
</body>
</html>