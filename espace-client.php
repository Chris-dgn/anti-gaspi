<?php
session_start();
require "connexion.php";

if (!isset($_SESSION["client_id"])) {

    if (isset($_POST["action"]) && $_POST["action"] === "connexion") {
        $email = $_POST["email"];
        $motDePasseSaisi = $_POST["mot_de_passe"];

        $requete = $pdo->prepare("SELECT * FROM clients WHERE email = :email");
        $requete->execute(["email" => $email]);
        $client = $requete->fetch();

        if ($client && password_verify($motDePasseSaisi, $client["mot_de_passe"])) {
            $_SESSION["client_id"] = $client["id"];
            $_SESSION["client_nom"] = $client["nom"];

            if (isset($_GET["redirect"])) {
                header("Location: " . $_GET["redirect"]);
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $erreurConnexion = "Email ou mot de passe incorrect";
        }
    }

    if (isset($_POST["action"]) && $_POST["action"] === "inscription") {

        if ($_POST["mot_de_passe"] !== $_POST["confirmation_mot_de_passe"]) {
            $erreurInscription = "Les mots de passe ne correspondent pas.";
        } else {
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

            $inscriptionReussie = true;
        }
    }

    $titrePage = "Espace client";
    include "header.php";
    ?>

    <style>
        /* --- Animations connexion / inscription client --- */
        @keyframes fade-scale-in {
            from { opacity: 0; transform: scale(0.94); }
            to { opacity: 1; transform: scale(1); }
        }
        .icone-client {
            animation: fade-scale-in 0.5s ease both;
        }
        @keyframes slide-up-in {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .titre-client {
            animation: slide-up-in 0.45s ease both;
            animation-delay: 0.1s;
        }
        .carte-client {
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
        .erreur-shake {
            animation: shake 0.4s ease;
        }
        @keyframes check-pop {
            0% { transform: scale(0); opacity: 0; }
            60% { transform: scale(1.15); opacity: 1; }
            100% { transform: scale(1); }
        }
        .icone-succes {
            animation: check-pop 0.5s ease both;
        }
        .champ-client {
            transition: transform 0.15s ease;
        }
        .champ-client:focus {
            transform: scale(1.015);
        }
        .btn-client {
            transition: transform 0.15s ease, box-shadow 0.2s ease;
        }
        .btn-client:active {
            transform: scale(0.97);
        }
        .btn-client:hover {
            box-shadow: 0 8px 20px -6px rgba(4, 120, 87, 0.5);
        }
        details[open] .formulaire-inscription {
            animation: slide-up-in 0.35s ease both;
        }
        summary::-webkit-details-marker {
            display: none;
        }
        .fleche-inscription {
            display: inline-block;
            transition: transform 0.25s ease;
        }
        details[open] .fleche-inscription {
            transform: rotate(90deg);
        }
    </style>

    <div class="min-h-[80vh] flex items-center justify-center bg-gradient-to-br from-gray-50 to-emerald-50 px-4 py-10">
        <div class="w-full max-w-md">

            <div class="text-center mb-6">
                <div class="icone-client inline-flex items-center justify-center w-14 h-14 bg-emerald-700 rounded-2xl mb-3">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h1 class="titre-client text-2xl font-bold text-gray-900">Espace client</h1>
                <p class="titre-client text-gray-500 text-sm mt-1">Connectez-vous pour réserver vos paniers</p>
            </div>

            <div class="carte-client bg-white rounded-2xl shadow-lg border border-gray-100 p-7">

                <?php if (isset($inscriptionReussie)) { ?>
                    <div class="text-center py-4">
                        <div class="icone-succes inline-flex items-center justify-center w-12 h-12 bg-emerald-100 rounded-full mb-3">
                            <svg class="w-6 h-6 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="text-gray-800 font-semibold mb-4">Inscription réussie ! Connectez-vous ci-dessous.</p>
                    </div>
                <?php } ?>

                <?php if (isset($erreurConnexion)) { ?>
                    <p class="erreur-shake bg-red-50 text-red-600 text-sm text-center p-3 rounded-lg mb-4"><?php echo $erreurConnexion; ?></p>
                <?php } ?>

                <form action="" method="POST" class="space-y-3">
                    <input type="hidden" name="action" value="connexion">
                    <div>
                        <label class="text-xs font-medium text-gray-500 mb-1 block">Email</label>
                        <input type="email" name="email" placeholder="vous@email.com" class="champ-client border border-gray-200 rounded-lg p-3 w-full text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 mb-1 block">Mot de passe</label>
                        <input type="password" name="mot_de_passe" placeholder="••••••••" class="champ-client border border-gray-200 rounded-lg p-3 w-full text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <button type="submit" class="btn-client bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg p-3 w-full font-semibold transition">Se connecter</button>
                </form>

                <div class="flex items-center gap-3 my-5">
                    <div class="flex-1 h-px bg-gray-100"></div>
                    <span class="text-xs text-gray-400">ou</span>
                    <div class="flex-1 h-px bg-gray-100"></div>
                </div>

                <details class="group">
                    <summary class="text-center text-sm text-emerald-700 font-medium cursor-pointer list-none">
                        Créer un compte <span class="fleche-inscription">→</span>
                    </summary>

                    <div class="formulaire-inscription">
                        <?php if (isset($erreurInscription)) { ?>
                            <p class="erreur-shake bg-red-50 text-red-600 text-xs text-center p-2 rounded-lg mt-3"><?php echo $erreurInscription; ?></p>
                        <?php } ?>

                        <form action="" method="POST" class="space-y-3 mt-4">
                            <input type="hidden" name="action" value="inscription">
                            <input type="text" name="nom" placeholder="Votre nom" class="champ-client border border-gray-200 rounded-lg p-3 w-full text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <input type="email" name="email" placeholder="Email" class="champ-client border border-gray-200 rounded-lg p-3 w-full text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <input type="password" name="mot_de_passe" placeholder="Mot de passe" class="champ-client border border-gray-200 rounded-lg p-3 w-full text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <input type="password" name="confirmation_mot_de_passe" placeholder="Confirmer le mot de passe" class="champ-client border border-gray-200 rounded-lg p-3 w-full text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <input type="text" name="telephone" placeholder="Téléphone" class="champ-client border border-gray-200 rounded-lg p-3 w-full text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <button type="submit" class="btn-client bg-gray-800 hover:bg-gray-900 text-white rounded-lg p-3 w-full font-semibold transition text-sm">S'inscrire</button>
                        </form>
                    </div>
                </details>

            </div>
        </div>
    </div>
    <?php
    include "footer.php";
    exit;
}
$titrePage = "Mon profil";

$clientId = $_SESSION["client_id"];

$requete = $pdo->prepare("SELECT COUNT(*) AS total FROM reservations WHERE client_id = :client_id");
$requete->execute(["client_id" => $clientId]);
$nombreReservations = $requete->fetch()["total"];

$requete = $pdo->prepare("SELECT SUM(paniers.prix_normal - paniers.prix_reduit) AS total
                           FROM reservations
                           JOIN paniers ON reservations.panier_id = paniers.id
                           WHERE reservations.client_id = :client_id AND reservations.statut = 'recupere'");
$requete->execute(["client_id" => $clientId]);
$economies = $requete->fetch()["total"];
if ($economies === null) {
    $economies = 0;
}

$requete = $pdo->prepare("SELECT reservations.*, paniers.titre, commercants.nom_boutique
                           FROM reservations
                           JOIN paniers ON reservations.panier_id = paniers.id
                           JOIN commercants ON paniers.commercant_id = commercants.id
                           WHERE reservations.client_id = :client_id
                           ORDER BY reservations.date_reservation DESC
                           LIMIT 3");
$requete->execute(["client_id" => $clientId]);
$dernieresReservations = $requete->fetchAll();

include "header.php";
?>

<style>
    /* --- Animations profil client --- */
    @keyframes fade-scale-in {
        from { opacity: 0; transform: scale(0.8); }
        to { opacity: 1; transform: scale(1); }
    }
    .avatar-client {
        animation: fade-scale-in 0.5s ease both;
    }
    @keyframes slide-up-in {
        from { opacity: 0; transform: translateY(18px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .nom-client {
        animation: slide-up-in 0.4s ease both;
        animation-delay: 0.15s;
    }
    @keyframes count-pop {
        0% { transform: scale(0.7); opacity: 0; }
        60% { transform: scale(1.08); opacity: 1; }
        100% { transform: scale(1); }
    }
    .stat-profil {
        opacity: 0;
        animation: slide-up-in 0.45s ease forwards;
    }
    .stat-profil:nth-child(1) { animation-delay: 0.2s; }
    .stat-profil:nth-child(2) { animation-delay: 0.27s; }
    .stat-chiffre-profil {
        display: inline-block;
        animation: count-pop 0.5s ease both;
        animation-delay: 0.4s;
    }
    .menu-profil a {
        transition: background-color 0.15s ease, padding-left 0.15s ease;
    }
    .menu-profil a:hover {
        padding-left: 6px;
    }
    .menu-profil a svg {
        transition: transform 0.15s ease;
    }
    .menu-profil a:hover svg {
        transform: translateX(3px);
    }
    .ligne-recente {
        opacity: 0;
        animation: slide-up-in 0.4s ease forwards;
    }
    .btn-deconnexion {
        transition: transform 0.15s ease;
    }
    .btn-deconnexion:active {
        transform: scale(0.96);
    }
</style>

<div class="bg-gradient-to-br from-emerald-700 to-emerald-600 pb-16 pt-10 px-4">
    <div class="max-w-md mx-auto text-center">
        <div class="avatar-client inline-flex items-center justify-center w-16 h-16 bg-white/20 backdrop-blur rounded-full mb-3 border-2 border-white/30">
            <span class="text-2xl font-bold text-white"><?php echo strtoupper(substr($_SESSION["client_nom"], 0, 1)); ?></span>
        </div>
        <h1 class="nom-client text-xl font-bold text-white"><?php echo $_SESSION["client_nom"]; ?></h1>
        <p class="nom-client text-emerald-100 text-sm">Membre AntiGaspi</p>
    </div>
</div>

<div class="max-w-md mx-auto px-4 -mt-10">

    <div class="grid grid-cols-2 gap-3 mb-4">
        <div class="stat-profil bg-white rounded-2xl shadow-lg p-4 text-center">
            <p class="stat-chiffre-profil text-2xl font-extrabold text-gray-900"><?php echo $nombreReservations; ?></p>
            <p class="text-xs text-gray-500 mt-1">🥡 Paniers sauvés</p>
        </div>
        <div class="stat-profil bg-white rounded-2xl shadow-lg p-4 text-center">
            <p class="stat-chiffre-profil text-2xl font-extrabold text-emerald-700"><?php echo $economies; ?> F</p>
            <p class="text-xs text-gray-500 mt-1">💰 Économisés</p>
        </div>
    </div>

    <div class="menu-profil bg-white rounded-2xl shadow-sm border border-gray-100 divide-y divide-gray-100 mb-4">
        <a href="mes-reservations.php" class="flex items-center justify-between p-4 hover:bg-gray-50 transition">
            <span class="flex items-center gap-3 text-sm font-medium text-gray-800">
                <span class="w-9 h-9 bg-emerald-50 rounded-lg flex items-center justify-center">📋</span>
                Mes réservations
            </span>
            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
        <a href="index.php" class="flex items-center justify-between p-4 hover:bg-gray-50 transition">
            <span class="flex items-center gap-3 text-sm font-medium text-gray-800">
                <span class="w-9 h-9 bg-orange-50 rounded-lg flex items-center justify-center">🛍️</span>
                Découvrir des paniers
            </span>
            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    <?php if (count($dernieresReservations) > 0) { ?>
        <h2 class="font-bold text-gray-900 text-sm mb-2 px-1">Récemment</h2>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 divide-y divide-gray-100 mb-4">
            <?php foreach ($dernieresReservations as $index => $r) { ?>
                <div class="ligne-recente p-4 flex items-center gap-3" style="animation-delay: <?php echo 0.1 * $index; ?>s;">
                    <span class="w-9 h-9 bg-gray-50 rounded-lg flex items-center justify-center text-sm flex-shrink-0">🥡</span>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-800 truncate"><?php echo $r["titre"]; ?></p>
                        <p class="text-xs text-gray-400"><?php echo $r["nom_boutique"]; ?></p>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } ?>

    <a href="deconnexion.php" class="btn-deconnexion block text-center text-red-500 text-sm font-medium py-3">Se déconnecter</a>
</div>
<?php include "footer.php"; ?>