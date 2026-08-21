<?php
session_start();
require "connexion.php";

if (!isset($_SESSION["commercant_id"])) {
    
    if (isset($_POST["email"]) && isset($_POST["mot_de_passe"])) {
        $email = $_POST["email"];
        $motDePasseSaisi = $_POST["mot_de_passe"];

        $requete = $pdo->prepare("SELECT * FROM commercants WHERE email = :email");
        $requete->execute(["email" => $email]);
        $commercant = $requete->fetch();

        if ($commercant && password_verify($motDePasseSaisi, $commercant["mot_de_passe"])) {

            if ($commercant["statut_validation"] !== "valide") {
                $erreurConnexion = "Votre compte est en attente de validation par notre équipe.";
            } else {
                $_SESSION["commercant_id"] = $commercant["id"];
                $_SESSION["nom_boutique"] = $commercant["nom_boutique"];
                header("Location: espace-commercant.php");
                exit;
            }

        } else {
            $erreurConnexion = "Email ou mot de passe incorrect";
        }
    }
    
    $titrePage = "Espace commerçant";
    include "header.php";
    ?>

    <style>
        /* --- Animations formulaire connexion --- */
        @keyframes fade-scale-in {
            from { opacity: 0; transform: scale(0.94); }
            to { opacity: 1; transform: scale(1); }
        }
        .icone-connexion {
            animation: fade-scale-in 0.5s ease both;
        }
        @keyframes slide-up-in {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .titre-connexion {
            animation: slide-up-in 0.45s ease both;
            animation-delay: 0.1s;
        }
        .carte-connexion {
            animation: slide-up-in 0.5s ease both;
            animation-delay: 0.2s;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20% { transform: translateX(-6px); }
            40% { transform: translateX(6px); }
            60% { transform: translateX(-4px); }
            80% { transform: translateX(4px); }
        }
        .erreur-connexion {
            animation: shake 0.4s ease;
        }
        .champ-connexion {
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .champ-connexion:focus {
            transform: scale(1.015);
        }
        .btn-connexion {
            transition: transform 0.15s ease, box-shadow 0.2s ease;
        }
        .btn-connexion:active {
            transform: scale(0.97);
        }
        .btn-connexion:hover {
            box-shadow: 0 8px 20px -6px rgba(4, 120, 87, 0.5);
        }
    </style>

    <div class="min-h-[70vh] flex items-center justify-center bg-gradient-to-br from-gray-50 to-emerald-50 px-4">
        <div class="w-full max-w-md">
            <div class="text-center mb-6">
                <div class="icone-connexion inline-flex items-center justify-center w-14 h-14 bg-emerald-700 rounded-2xl mb-3">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M5 21V7l8-4v18M13 21V7l6 4v10M9 9v.01M9 12v.01M9 15v.01"/>
                    </svg>
                </div>
                <h1 class="titre-connexion text-2xl font-bold text-gray-900">Espace commerçant</h1>
                <p class="titre-connexion text-gray-500 text-sm mt-1">Gérez vos paniers et vos ventes</p>
            </div>

            <div class="carte-connexion bg-white rounded-2xl shadow-lg border border-gray-100 p-7">
                <?php if (isset($erreurConnexion)) { ?>
                    <p class="erreur-connexion bg-red-50 text-red-600 text-sm text-center p-3 rounded-lg mb-4"><?php echo $erreurConnexion; ?></p>
                <?php } ?>

                <form action="" method="POST" class="space-y-3">
                    <div>
                        <label class="text-xs font-medium text-gray-500 mb-1 block">Email</label>
                        <input type="email" name="email" placeholder="vous@boutique.com" class="champ-connexion border border-gray-200 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 mb-1 block">Mot de passe</label>
                        <input type="password" name="mot_de_passe" placeholder="••••••••" class="champ-connexion border border-gray-200 rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <button type="submit" class="btn-connexion bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg p-3 w-full font-semibold transition">Se connecter</button>
                </form>
            </div>

            <p class="text-center text-sm text-gray-500 mt-5">
                Pas encore de compte ? <a href="inscription-commercant.php" class="text-emerald-700 font-medium underline">Devenir partenaire</a>
            </p>
        </div>
    </div>
    <?php
    include "footer.php";
    exit;
}

$commercantId = $_SESSION["commercant_id"];

if (isset($_POST["titre"]) && isset($_POST["prix_normal"])) {
    $requete = $pdo->prepare("INSERT INTO paniers (commercant_id, titre, description, prix_normal, prix_reduit, quantite_disponible, creneau_debut, creneau_fin, statut) VALUES (:commercant_id, :titre, :description, :prix_normal, :prix_reduit, :quantite, :creneau_debut, :creneau_fin, 'disponible')");
    $requete->execute([
        "commercant_id" => $commercantId,
        "titre" => $_POST["titre"],
        "description" => $_POST["description"],
        "prix_normal" => $_POST["prix_normal"],
        "prix_reduit" => $_POST["prix_reduit"],
        "quantite" => $_POST["quantite"],
        "creneau_debut" => $_POST["creneau_debut"],
        "creneau_fin" => $_POST["creneau_fin"]
    ]);
    $messagePublication = "Panier publié avec succès !";
}

if (isset($_POST["marquer_recupere"])) {
    $reservationId = $_POST["marquer_recupere"];
    
    $requete = $pdo->prepare("UPDATE reservations 
                               SET statut = 'recupere' 
                               WHERE id = :id 
                               AND panier_id IN (SELECT id FROM paniers WHERE commercant_id = :commercant_id)");
    $requete->execute(["id" => $reservationId, "commercant_id" => $commercantId]);
}

if (isset($_POST["supprimer_panier"])) {
    $panierId = $_POST["supprimer_panier"];
    
    $requete = $pdo->prepare("DELETE FROM paniers WHERE id = :id AND commercant_id = :commercant_id");
    $requete->execute(["id" => $panierId, "commercant_id" => $commercantId]);
}

$requete = $pdo->prepare("SELECT * FROM paniers WHERE commercant_id = :commercant_id ORDER BY id DESC");
$requete->execute(["commercant_id" => $commercantId]);
$mesPaniers = $requete->fetchAll();

$requete = $pdo->prepare("SELECT reservations.*, paniers.titre, clients.nom AS nom_client, clients.telephone
                           FROM reservations
                           JOIN paniers ON reservations.panier_id = paniers.id
                           JOIN clients ON reservations.client_id = clients.id
                           WHERE paniers.commercant_id = :commercant_id AND reservations.statut = 'en_attente'
                           ORDER BY reservations.date_reservation DESC");
$requete->execute(["commercant_id" => $commercantId]);
$reservationsAPreparer = $requete->fetchAll();

$requete = $pdo->prepare("SELECT reservations.*, paniers.titre, paniers.prix_reduit, clients.nom AS nom_client
                           FROM reservations
                           JOIN paniers ON reservations.panier_id = paniers.id
                           JOIN clients ON reservations.client_id = clients.id
                           WHERE paniers.commercant_id = :commercant_id AND reservations.statut = 'recupere'
                           ORDER BY reservations.date_reservation DESC
                           LIMIT 10");
$requete->execute(["commercant_id" => $commercantId]);
$historiqueVentes = $requete->fetchAll();

$requete = $pdo->prepare("SELECT SUM(paniers.prix_reduit) AS total
                           FROM reservations
                           JOIN paniers ON reservations.panier_id = paniers.id
                           WHERE paniers.commercant_id = :commercant_id AND reservations.statut = 'recupere'");
$requete->execute(["commercant_id" => $commercantId]);
$chiffreAffaires = $requete->fetch()["total"];
if ($chiffreAffaires === null) {
    $chiffreAffaires = 0;
}

$totalPaniersDispo = 0;
foreach ($mesPaniers as $p) { if ($p["quantite_disponible"] > 0) $totalPaniersDispo++; }

$titrePage = "Tableau de bord - " . $_SESSION["nom_boutique"];
include "header.php";
?>

<style>
    /* --- Animations tableau de bord --- */
    @keyframes slide-up-in {
        from { opacity: 0; transform: translateY(16px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes count-pop {
        0% { transform: scale(0.7); opacity: 0; }
        60% { transform: scale(1.08); opacity: 1; }
        100% { transform: scale(1); }
    }
    .stat-carte {
        opacity: 0;
        animation: slide-up-in 0.45s ease forwards;
    }
    .stat-carte:nth-child(1) { animation-delay: 0.05s; }
    .stat-carte:nth-child(2) { animation-delay: 0.12s; }
    .stat-carte:nth-child(3) { animation-delay: 0.19s; }
    .stat-carte:nth-child(4) { animation-delay: 0.26s; }
    .stat-carte:hover {
        transform: translateY(-2px);
        transition: transform 0.2s ease;
    }
    .stat-chiffre {
        display: inline-block;
        animation: count-pop 0.5s ease both;
        animation-delay: 0.35s;
    }
    .message-succes {
        animation: slide-up-in 0.35s ease both;
    }
    .ligne-liste {
        opacity: 0;
        transform: translateX(-10px);
        animation: fade-slide-x 0.4s ease forwards;
    }
    @keyframes fade-slide-x {
        to { opacity: 1; transform: translateX(0); }
    }
    .btn-supprimer {
        transition: transform 0.15s ease;
    }
    .btn-supprimer:hover {
        transform: scale(1.15) rotate(-6deg);
    }
    .btn-recupere {
        transition: transform 0.15s ease, box-shadow 0.2s ease;
    }
    .btn-recupere:active {
        transform: scale(0.95);
    }
    .form-publier {
        transition: box-shadow 0.2s ease;
    }
    .form-publier input:focus,
    .form-publier textarea:focus {
        transform: scale(1.01);
    }
    .form-publier input,
    .form-publier textarea {
        transition: transform 0.15s ease;
    }
    .btn-publier {
        transition: transform 0.15s ease, box-shadow 0.2s ease;
    }
    .btn-publier:active {
        transform: scale(0.97);
    }
    .btn-publier:hover {
        box-shadow: 0 8px 20px -6px rgba(4, 120, 87, 0.5);
    }
</style>

<div class="bg-white border-b border-gray-100">
    <div class="max-w-5xl mx-auto px-4 py-6 flex items-center justify-between">
        <div>
            <p class="text-xs text-emerald-700 font-semibold uppercase tracking-wide mb-1">Tableau de bord</p>
            <h1 class="text-2xl font-bold text-gray-900"><?php echo $_SESSION["nom_boutique"]; ?></h1>
        </div>
        <a href="deconnexion.php" class="text-sm text-gray-400 hover:text-red-500 transition">Déconnexion</a>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 py-8">

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="stat-carte bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="stat-chiffre text-2xl font-extrabold text-gray-900"><?php echo count($mesPaniers); ?></p>
            <p class="text-xs text-gray-500 mt-1">Paniers publiés</p>
        </div>
        <div class="stat-carte bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="stat-chiffre text-2xl font-extrabold text-emerald-700"><?php echo $totalPaniersDispo; ?></p>
            <p class="text-xs text-gray-500 mt-1">Disponibles</p>
        </div>
        <div class="stat-carte bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="stat-chiffre text-2xl font-extrabold text-orange-500"><?php echo count($reservationsAPreparer); ?></p>
            <p class="text-xs text-gray-500 mt-1">À préparer</p>
        </div>
        <div class="stat-carte bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="stat-chiffre text-2xl font-extrabold text-gray-900"><?php echo $chiffreAffaires; ?> F</p>
            <p class="text-xs text-gray-500 mt-1">Chiffre d'affaires</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

        <div class="lg:col-span-2">
            <h2 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                <span class="w-1.5 h-5 bg-emerald-700 rounded-full"></span>
                Publier un panier
            </h2>

            <?php if (isset($messagePublication)) { ?>
                <p class="message-succes bg-emerald-50 text-emerald-700 text-sm p-3 rounded-lg mb-3 font-medium">✓ <?php echo $messagePublication; ?></p>
            <?php } ?>

            <form action="" method="POST" class="form-publier bg-white p-5 rounded-2xl shadow-sm border border-gray-100 space-y-3">
                <input type="text" name="titre" placeholder="Titre du panier" class="border border-gray-200 rounded-lg p-3 w-full text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <textarea name="description" placeholder="Description" rows="2" class="border border-gray-200 rounded-lg p-3 w-full text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                <div class="grid grid-cols-2 gap-2">
                    <input type="number" name="prix_normal" placeholder="Prix normal" class="border border-gray-200 rounded-lg p-3 w-full text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <input type="number" name="prix_reduit" placeholder="Prix réduit" class="border border-gray-200 rounded-lg p-3 w-full text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <input type="number" name="quantite" placeholder="Quantité disponible" class="border border-gray-200 rounded-lg p-3 w-full text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-xs text-gray-400">Début</label>
                        <input type="time" name="creneau_debut" class="border border-gray-200 rounded-lg p-2.5 w-full text-sm">
                    </div>
                    <div>
                        <label class="text-xs text-gray-400">Fin</label>
                        <input type="time" name="creneau_fin" class="border border-gray-200 rounded-lg p-2.5 w-full text-sm">
                    </div>
                </div>
                <button type="submit" class="btn-publier bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg p-3 w-full font-semibold text-sm transition">Publier le panier</button>
            </form>
        </div>

        <div class="lg:col-span-3 space-y-8">

            <div>
                <h2 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <span class="w-1.5 h-5 bg-gray-300 rounded-full"></span>
                    Mes paniers
                </h2>
                <div class="space-y-2">
                    <?php if (count($mesPaniers) === 0) { ?>
                        <p class="text-gray-400 text-sm">Vous n'avez encore rien publié.</p>
                    <?php } ?>
                    <?php foreach ($mesPaniers as $index => $panier) { ?>
                        <div class="ligne-liste bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex justify-between items-center" style="animation-delay: <?php echo 0.05 * $index; ?>s;">
                            <div>
                                <p class="font-semibold text-gray-900 text-sm"><?php echo $panier["titre"]; ?></p>
                                <p class="text-xs text-gray-500"><?php echo $panier["prix_reduit"]; ?> FCFA — Reste <?php echo $panier["quantite_disponible"]; ?></p>
                            </div>
                            <div class="flex items-center gap-2">
                                <?php if ($panier["quantite_disponible"] > 0) { ?>
                                    <span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-3 py-1 rounded-full">Disponible</span>
                                <?php } else { ?>
                                    <span class="bg-gray-100 text-gray-500 text-xs font-bold px-3 py-1 rounded-full">Épuisé</span>
                                <?php } ?>
                                <form action="" method="POST" onsubmit="return confirm('Supprimer ce panier définitivement ?');">
                                    <input type="hidden" name="supprimer_panier" value="<?php echo $panier['id']; ?>">
                                    <button type="submit" class="btn-supprimer text-red-400 hover:text-red-600 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div>
                <h2 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <span class="w-1.5 h-5 bg-orange-400 rounded-full"></span>
                    Réservations à préparer
                </h2>
                <div class="space-y-2">
                    <?php if (count($reservationsAPreparer) === 0) { ?>
                        <p class="text-gray-400 text-sm">Aucune réservation en attente.</p>
                    <?php } ?>
                    <?php foreach ($reservationsAPreparer as $index => $reservation) { ?>
                        <div class="ligne-liste bg-white rounded-xl shadow-sm border border-orange-100 p-4" style="animation-delay: <?php echo 0.05 * $index; ?>s;">
                            <p class="font-semibold text-gray-900 text-sm"><?php echo $reservation["titre"]; ?></p>
                            <p class="text-xs text-gray-500 mt-1">👤 <?php echo $reservation["nom_client"]; ?> — 📞 <?php echo $reservation["telephone"]; ?></p>
                            <form action="" method="POST" class="mt-2">
                                <input type="hidden" name="marquer_recupere" value="<?php echo $reservation['id']; ?>">
                                <button type="submit" class="btn-recupere bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition">
                                    ✓ Marquer comme récupéré
                                </button>
                            </form>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div>
                <h2 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                    <span class="w-1.5 h-5 bg-blue-400 rounded-full"></span>
                    Historique des ventes
                </h2>
                <div class="space-y-2">
                    <?php if (count($historiqueVentes) === 0) { ?>
                        <p class="text-gray-400 text-sm">Aucune vente terminée pour l'instant.</p>
                    <?php } ?>
                    <?php foreach ($historiqueVentes as $index => $vente) { ?>
                        <div class="ligne-liste bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex justify-between items-center" style="animation-delay: <?php echo 0.05 * $index; ?>s;">
                            <div>
                                <p class="font-semibold text-gray-900 text-sm"><?php echo $vente["titre"]; ?></p>
                                <p class="text-xs text-gray-500">👤 <?php echo $vente["nom_client"]; ?></p>
                            </div>
                            <p class="text-emerald-700 font-bold text-sm"><?php echo $vente["prix_reduit"]; ?> F</p>
                        </div>
                    <?php } ?>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include "footer.php"; ?>