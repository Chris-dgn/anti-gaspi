<?php
session_start();
require "connexion.php";

if (!isset($_SESSION["admin_id"])) {

    if (isset($_POST["email"]) && isset($_POST["mot_de_passe"])) {
        $email = $_POST["email"];
        $motDePasseSaisi = $_POST["mot_de_passe"];

        $requete = $pdo->prepare("SELECT * FROM admins WHERE email = :email");
        $requete->execute(["email" => $email]);
        $admin = $requete->fetch();

        if ($admin && password_verify($motDePasseSaisi, $admin["mot_de_passe"])) {
            $_SESSION["admin_id"] = $admin["id"];
            $_SESSION["admin_nom"] = $admin["nom"];
            header("Location: admin-validation.php");
            exit;
        } else {
            $erreurConnexion = "Email ou mot de passe incorrect";
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.tailwindcss.com"></script>
        <title>Administration</title>
    </head>
    <body class="bg-gray-950 min-h-screen flex items-center justify-center px-4">
        <div class="w-full max-w-sm">
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-emerald-600 rounded-xl mb-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h1 class="text-white text-lg font-bold">Administration</h1>
                <p class="text-gray-500 text-xs mt-1">Accès réservé — AntiGaspi</p>
            </div>

            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 shadow-2xl">
                <?php if (isset($erreurConnexion)) { ?>
                    <p class="bg-red-500/10 text-red-400 text-sm text-center p-3 rounded-lg mb-4 border border-red-500/20"><?php echo $erreurConnexion; ?></p>
                <?php } ?>
                <form action="" method="POST" class="space-y-3">
                    <div>
                        <label class="text-xs font-medium text-gray-400 mb-1 block">Email</label>
                        <input type="email" name="email" placeholder="admin@antigaspi.com" class="bg-gray-800 border border-gray-700 text-white rounded-lg p-3 w-full text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-600">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-400 mb-1 block">Mot de passe</label>
                        <input type="password" name="mot_de_passe" placeholder="••••••••" class="bg-gray-800 border border-gray-700 text-white rounded-lg p-3 w-full text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-600">
                    </div>
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg p-3 w-full font-semibold text-sm transition">Se connecter</button>
                </form>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

if (isset($_POST["valider_commercant"])) {
    $id = $_POST["valider_commercant"];
    $requete = $pdo->prepare("UPDATE commercants SET statut_validation = 'valide' WHERE id = :id");
    $requete->execute(["id" => $id]);
}

if (isset($_POST["refuser_commercant"])) {
    $id = $_POST["refuser_commercant"];
    $requete = $pdo->prepare("UPDATE commercants SET statut_validation = 'refuse' WHERE id = :id");
    $requete->execute(["id" => $id]);
}

$requete = $pdo->query("SELECT * FROM commercants WHERE statut_validation = 'en_attente' ORDER BY id DESC");
$enAttente = $requete->fetchAll();

$requete = $pdo->query("SELECT COUNT(*) AS total FROM commercants WHERE statut_validation = 'valide'");
$totalValides = $requete->fetch()["total"];

$requete = $pdo->query("SELECT COUNT(*) AS total FROM commercants WHERE statut_validation = 'refuse'");
$totalRefuses = $requete->fetch()["total"];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Administration - AntiGaspi</title>
</head>
<body class="bg-gray-950 min-h-screen">

    <!-- Barre admin -->
    <nav class="bg-gray-900 border-b border-gray-800">
        <div class="max-w-4xl mx-auto flex items-center justify-between px-4 py-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-emerald-600 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <span class="text-white font-bold text-sm">Administration</span>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-gray-400 text-xs">👤 <?php echo $_SESSION["admin_nom"]; ?></span>
                <a href="admin-deconnexion.php" class="text-gray-500 hover:text-red-400 text-sm transition">Déconnexion</a>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 py-8">

        <h1 class="text-white text-xl font-bold mb-6">Validation des commerçants</h1>

        <!-- Stats -->
        <div class="grid grid-cols-3 gap-4 mb-8">
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
                <p class="text-2xl font-extrabold text-orange-400"><?php echo count($enAttente); ?></p>
                <p class="text-xs text-gray-500 mt-1">En attente</p>
            </div>
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
                <p class="text-2xl font-extrabold text-emerald-400"><?php echo $totalValides; ?></p>
                <p class="text-xs text-gray-500 mt-1">Validés</p>
            </div>
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
                <p class="text-2xl font-extrabold text-red-400"><?php echo $totalRefuses; ?></p>
                <p class="text-xs text-gray-500 mt-1">Refusés</p>
            </div>
        </div>

        <!-- Liste en attente -->
        <div class="space-y-3">
            <?php if (count($enAttente) === 0) { ?>
                <div class="text-center py-16 bg-gray-900 border border-gray-800 rounded-2xl">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-gray-800 rounded-full mb-3">
                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <p class="text-gray-400 text-sm">Aucun commerçant en attente de validation</p>
                </div>
            <?php } ?>

            <?php foreach ($enAttente as $c) { ?>
                <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex gap-3">
                            <div class="w-11 h-11 rounded-xl bg-orange-500/10 flex items-center justify-center text-lg flex-shrink-0">
                                🏪
                            </div>
                            <div>
                                <p class="font-bold text-white"><?php echo $c["nom_boutique"]; ?></p>
                                <span class="inline-block mt-1 bg-emerald-500/10 text-emerald-400 text-xs font-medium px-2 py-0.5 rounded-full"><?php echo $c["categorie"]; ?></span>
                            </div>
                        </div>
                        <span class="bg-orange-500/10 text-orange-400 text-xs font-bold px-2.5 py-1 rounded-full flex-shrink-0">En attente</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-2 mt-4 text-sm">
                        <p class="text-gray-400">✉️ <?php echo $c["email"]; ?></p>
                        <p class="text-gray-400">📞 <?php echo $c["telephone"]; ?></p>
                        <p class="text-gray-400">📍 <?php echo $c["adresse"]; ?></p>
                    </div>

                    <div class="flex gap-2 mt-4 pt-4 border-t border-gray-800">
                        <form action="" method="POST" class="flex-1">
                            <input type="hidden" name="valider_commercant" value="<?php echo $c['id']; ?>">
                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold px-4 py-2.5 rounded-lg w-full transition">
                                ✓ Valider
                            </button>
                        </form>
                        <form action="" method="POST" class="flex-1" onsubmit="return confirm('Refuser ce commerçant ?');">
                            <input type="hidden" name="refuser_commercant" value="<?php echo $c['id']; ?>">
                            <button type="submit" class="bg-gray-800 hover:bg-red-500/20 hover:text-red-400 text-gray-400 text-sm font-semibold px-4 py-2.5 rounded-lg w-full transition">
                                ✗ Refuser
                            </button>
                        </form>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>