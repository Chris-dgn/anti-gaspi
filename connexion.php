<?php
$hote = "localhost";
$nomBase = "anti_gaspi";
$utilisateurMySQL = "root";
$motDePasse = "";

try {
    $pdo = new PDO("mysql:host=$hote;dbname=$nomBase", $utilisateurMySQL, $motDePasse);
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>