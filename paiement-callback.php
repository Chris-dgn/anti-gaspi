<?php
session_start();
require "connexion.php";

$succes = false;
$messagePrincipal = "";
$messageSecondaire = "";

if (isset($_GET["transaction_id"])) {
    $transactionId = $_GET["transaction_id"];

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api-sandbox.kkiapay.me/api/v1/transactions/status",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode(["transactionId" => $transactionId]),
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "x-api-key: d6d5d6b09c6e11f19d32fb83490c9576",
            "x-private-key: tpk_d6d5fdc09c6e11f19d32fb83490c9576"
        ]
    ]);
    $reponse = curl_exec($curl);
    curl_close($curl);

    $donnees = json_decode($reponse, true);

    if (isset($donnees["status"]) && $donnees["status"] === "SUCCESS") {

        $panierId = $_SESSION["panier_en_attente_id"];
        $clientId = $_SESSION["client_id"];

        $requete = $pdo->prepare("SELECT paniers.*, commercants.nom_boutique FROM paniers JOIN commercants ON paniers.commercant_id = commercants.id WHERE paniers.id = :id AND paniers.quantite_disponible > 0");
        $requete->execute(["id" => $panierId]);
        $panier = $requete->fetch();

        if ($panier) {
            $requete = $pdo->prepare("INSERT INTO reservations (panier_id, client_id, quantite, statut) VALUES (:panier_id, :client_id, 1, 'en_attente')");
            $requete->execute(["panier_id" => $panierId, "client_id" => $clientId]);

            $requete = $pdo->prepare("UPDATE paniers SET quantite_disponible = quantite_disponible - 1 WHERE id = :id");
            $requete->execute(["id" => $panierId]);

            unset($_SESSION["panier_en_attente_id"]);

            $succes = true;
            $messagePrincipal = "Réservation confirmée !";
            $messageSecondaire = $panier["titre"] . " — " . $panier["nom_boutique"];
        } else {
            $messagePrincipal = "Panier épuisé entre-temps";
            $messageSecondaire = "Contactez le support pour un remboursement.";
        }

    } else {
        $messagePrincipal = "Paiement non confirmé";
        $messageSecondaire = "Aucun montant n'a été débité si le paiement a échoué.";
    }
} else {
    $messagePrincipal = "Transaction introuvable";
}

$titrePage = "Confirmation";
include "header.php";
?>

<div class="min-h-[75vh] flex items-center justify-center px-4">
    <div class="w-full max-w-md text-center">

        <?php if ($succes) { ?>
            <div class="inline-flex items-center justify-center w-20 h-20 bg-emerald-100 rounded-full mb-5">
                <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        <?php } else { ?>
            <div class="inline-flex items-center justify-center w-20 h-20 bg-orange-100 rounded-full mb-5">
                <svg class="w-10 h-10 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        <?php } ?>

        <h1 class="text-2xl font-bold text-gray-900 mb-2"><?php echo $messagePrincipal; ?></h1>
        <?php if ($messageSecondaire) { ?>
            <p class="text-gray-500 mb-8"><?php echo $messageSecondaire; ?></p>
        <?php } ?>

        <div class="flex flex-col gap-3">
            <?php if ($succes) { ?>
                <a href="mes-reservations.php" class="bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl p-3.5 font-bold transition">
                    Voir mes réservations
                </a>
            <?php } ?>
            <a href="index.php" class="bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl p-3.5 font-medium transition">
                Retour à l'accueil
            </a>
        </div>

    </div>
</div>

<?php include "footer.php"; ?>