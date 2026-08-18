<?php
require "connexion.php";

$requete = $pdo->query("SELECT paniers.*, commercants.nom_boutique FROM paniers JOIN commercants ON paniers.commercant_id = commercants.id WHERE paniers.statut = 'disponible'");
$paniers = $requete->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Paniers disponibles</title>
</head>
<body class="bg-gray-100 p-6">
    <h1 class="text-2xl font-bold text-center mb-6">Paniers disponibles près de vous</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-4xl mx-auto">
        <?php foreach ($paniers as $panier) { ?>
            <div class="bg-white rounded-lg shadow p-4">
                <h2 class="text-lg font-bold"><?php echo $panier["titre"]; ?></h2>
                <p class="text-gray-600 text-sm mb-2"><?php echo $panier["nom_boutique"]; ?></p>
                <p class="text-gray-500 text-sm mb-2"><?php echo $panier["description"]; ?></p>
                <p class="text-red-500 font-bold">
                    <?php echo $panier["prix_reduit"]; ?> FCFA
                    <span class="text-gray-400 line-through text-sm"><?php echo $panier["prix_normal"]; ?> FCFA</span>
                </p>
                <p class="text-sm text-gray-500">Retrait : <?php echo $panier["creneau_debut"]; ?> - <?php echo $panier["creneau_fin"]; ?></p>
                <p class="text-sm text-gray-500">Quantité restante : <?php echo $panier["quantite_disponible"]; ?></p>
<form action="reserver.php" method="POST">
    <input type="hidden" name="panier_id" value="<?php echo $panier['id']; ?>">
    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white rounded p-2 w-full mt-3">Réserver</button>
</form>
    </div>
        <?php } ?>
    </div>
</body>
</html>