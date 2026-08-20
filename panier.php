<?php
session_start();
require "connexion.php";

if (!isset($_GET["id"])) {
    header("Location: index.php");
    exit;
}

$panierId = $_GET["id"];

$requete = $pdo->prepare("SELECT paniers.*, commercants.nom_boutique, commercants.adresse, commercants.telephone 
                           FROM paniers 
                           JOIN commercants ON paniers.commercant_id = commercants.id 
                           WHERE paniers.id = :id");
$requete->execute(["id" => $panierId]);
$panier = $requete->fetch();

if (!$panier) {
    echo "Panier introuvable.";
    exit;
}

$reduction = round((1 - $panier["prix_reduit"] / $panier["prix_normal"]) * 100);

$titrePage = $panier["titre"];
include "header.php";
?>

<div class="max-w-2xl mx-auto">

    <!-- Visuel du panier -->
    <div class="relative h-56 bg-gradient-to-br from-emerald-100 to-emerald-50 flex items-center justify-center">
        <a href="index.php" class="absolute top-4 left-4 bg-white/90 backdrop-blur w-9 h-9 rounded-full flex items-center justify-center shadow-sm hover:bg-white transition">
            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <span class="text-7xl">🥡</span>
        <span class="absolute top-4 right-4 bg-orange-500 text-white text-sm font-bold px-3 py-1.5 rounded-full shadow">
            -<?php echo $reduction; ?>%
        </span>
    </div>

    <!-- Carte flottante -->
    <div class="bg-white rounded-t-3xl -mt-6 relative px-5 pt-6 pb-8 shadow-lg">

        <div class="flex items-start justify-between mb-1">
            <h1 class="text-2xl font-bold text-gray-900"><?php echo $panier["titre"]; ?></h1>
        </div>
        <p class="text-emerald-700 font-medium text-sm mb-4">🏪 <?php echo $panier["nom_boutique"]; ?></p>

        <?php if ($panier["description"]) { ?>
            <p class="text-gray-600 text-sm mb-5 leading-relaxed"><?php echo $panier["description"]; ?></p>
        <?php } ?>

        <!-- Infos pratiques -->
        <div class="grid grid-cols-2 gap-3 mb-5">
            <div class="bg-gray-50 rounded-xl p-3">
                <p class="text-xs text-gray-400 mb-1">📍 Adresse</p>
                <p class="text-sm font-medium text-gray-800"><?php echo $panier["adresse"]; ?></p>
            </div>
            <div class="bg-gray-50 rounded-xl p-3">
                <p class="text-xs text-gray-400 mb-1">🕒 Créneau retrait</p>
                <p class="text-sm font-medium text-gray-800"><?php echo $panier["creneau_debut"]; ?> – <?php echo $panier["creneau_fin"]; ?></p>
            </div>
        </div>

        <?php if ($panier["quantite_disponible"] <= 3 && $panier["quantite_disponible"] > 0) { ?>
            <p class="text-orange-600 text-sm font-semibold mb-4">⚡ Plus que <?php echo $panier["quantite_disponible"]; ?> panier(s) disponible(s) !</p>
        <?php } ?>

        <!-- Prix + action -->
        <div class="border-t border-gray-100 pt-5 flex items-center justify-between gap-4">
            <div>
                <p class="text-3xl font-extrabold text-emerald-700"><?php echo $panier["prix_reduit"]; ?> <span class="text-lg">FCFA</span></p>
                <p class="text-gray-400 line-through text-sm"><?php echo $panier["prix_normal"]; ?> FCFA</p>
            </div>

            <div class="flex-1">
                <?php if ($panier["quantite_disponible"] > 0) { ?>
                    <?php if (isset($_SESSION["client_id"])) { ?>
                        <form action="reserver.php" method="POST">
                            <input type="hidden" name="panier_id" value="<?php echo $panier['id']; ?>">
                            <button type="submit" class="bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl p-3.5 w-full font-bold shadow-sm transition">
                                Réserver
                            </button>
                        </form>
                    <?php } else { ?>
                        <a href="espace-client.php?redirect=panier.php?id=<?php echo $panier['id']; ?>" class="block text-center bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl p-3.5 w-full font-bold shadow-sm transition">
                            Se connecter
                        </a>
                    <?php } ?>
                <?php } else { ?>
                    <button disabled class="bg-gray-200 text-gray-500 rounded-xl p-3.5 w-full font-bold cursor-not-allowed">
                        Épuisé
                    </button>
                <?php } ?>
            </div>
        </div>

    </div>
</div>

<?php include "footer.php"; ?>