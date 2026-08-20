<?php
session_start();
require "connexion.php";

if (!isset($_SESSION["client_id"])) {
    $titrePage = "Connexion requise";
    include "header.php";
    ?>
    <div class="min-h-[60vh] flex items-center justify-center px-4">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-2xl mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <p class="text-gray-600 mb-5">Connectez-vous pour voir vos réservations</p>
            <a href="espace-client.php" class="bg-emerald-700 hover:bg-emerald-800 text-white px-6 py-3 rounded-lg font-medium transition">Se connecter</a>
        </div>
    </div>
    <?php
    include "footer.php";
    exit;
}

$clientId = $_SESSION["client_id"];

$requete = $pdo->prepare("SELECT reservations.*, paniers.titre, paniers.prix_reduit, paniers.creneau_debut, paniers.creneau_fin, commercants.nom_boutique, commercants.adresse
                           FROM reservations
                           JOIN paniers ON reservations.panier_id = paniers.id
                           JOIN commercants ON paniers.commercant_id = commercants.id
                           WHERE reservations.client_id = :client_id
                           ORDER BY reservations.date_reservation DESC");
$requete->execute(["client_id" => $clientId]);
$reservations = $requete->fetchAll();

$titrePage = "Mes réservations";
include "header.php";
?>

<div class="bg-white border-b border-gray-100">
    <div class="max-w-3xl mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold text-gray-900">Mes réservations</h1>
        <p class="text-gray-500 text-sm mt-1"><?php echo count($reservations); ?> réservation(s) au total</p>
    </div>
</div>

<div class="max-w-3xl mx-auto px-4 py-8">

    <?php if (count($reservations) === 0) { ?>
        <div class="text-center py-16">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-50 rounded-2xl mb-4">
                <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <p class="text-gray-500 mb-2">Vous n'avez pas encore de réservation</p>
            <a href="index.php" class="text-emerald-700 font-semibold text-sm">Découvrir les paniers disponibles →</a>
        </div>
    <?php } ?>

    <div class="space-y-3">
        <?php foreach ($reservations as $reservation) { ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="flex">

                    <!-- Bandeau latéral coloré selon le statut -->
                    <div class="w-1.5 <?php echo $reservation["statut"] === "en_attente" ? "bg-orange-400" : ($reservation["statut"] === "recupere" ? "bg-emerald-600" : "bg-gray-300"); ?>"></div>

                    <div class="flex-1 p-4 flex justify-between items-center gap-4">
                        <div class="flex gap-3 items-center min-w-0">
                            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-xl flex-shrink-0">🥡</div>
                            <div class="min-w-0">
                                <h2 class="font-bold text-gray-900 truncate"><?php echo $reservation["titre"]; ?></h2>
                                <p class="text-sm text-gray-500 truncate"><?php echo $reservation["nom_boutique"]; ?></p>
                                <p class="text-xs text-gray-400 mt-0.5">🕒 <?php echo $reservation["creneau_debut"]; ?> – <?php echo $reservation["creneau_fin"]; ?></p>
                            </div>
                        </div>

                        <div class="text-right flex-shrink-0">
                            <p class="text-emerald-700 font-extrabold"><?php echo $reservation["prix_reduit"]; ?> F</p>
                            <?php if ($reservation["statut"] === "en_attente") { ?>
                                <span class="inline-block mt-1 bg-orange-100 text-orange-700 text-xs font-bold px-2.5 py-1 rounded-full">En attente</span>
                            <?php } elseif ($reservation["statut"] === "recupere") { ?>
                                <span class="inline-block mt-1 bg-emerald-100 text-emerald-700 text-xs font-bold px-2.5 py-1 rounded-full">✓ Récupéré</span>
                            <?php } else { ?>
                                <span class="inline-block mt-1 bg-gray-100 text-gray-600 text-xs font-bold px-2.5 py-1 rounded-full"><?php echo $reservation["statut"]; ?></span>
                            <?php } ?>
                        </div>
                    </div>

                </div>
            </div>
        <?php } ?>
    </div>
</div>

<?php include "footer.php"; ?>