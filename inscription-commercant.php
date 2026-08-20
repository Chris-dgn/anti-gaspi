<?php
require "connexion.php";

if (isset($_POST["nom_boutique"]) && isset($_POST["email"]) && isset($_POST["mot_de_passe"])) {

    if ($_POST["mot_de_passe"] !== $_POST["confirmation_mot_de_passe"]) {
        $erreurInscription = "Les mots de passe ne correspondent pas.";
    } else {
        $nomBoutique = $_POST["nom_boutique"];
        $email = $_POST["email"];
        $motDePasseHache = password_hash($_POST["mot_de_passe"], PASSWORD_DEFAULT);
        $adresse = $_POST["adresse"];
        $telephone = $_POST["telephone"];
        $categorie = $_POST["categorie"];
        
        $requete = $pdo->prepare("INSERT INTO commercants (nom_boutique, email, mot_de_passe, adresse, telephone, categorie, statut_validation) VALUES (:nom_boutique, :email, :mot_de_passe, :adresse, :telephone, :categorie, 'en_attente')");
        $requete->execute([
            "nom_boutique" => $nomBoutique,
            "email" => $email,
            "mot_de_passe" => $motDePasseHache,
            "adresse" => $adresse,
            "telephone" => $telephone,
            "categorie" => $categorie
        ]);
        
        $inscriptionReussie = true;
    }
}

$titrePage = "Inscription commerçant";
include "header.php";
?>

<div class="min-h-[80vh] flex items-center justify-center bg-gradient-to-br from-gray-50 to-emerald-50 px-4 py-10">
    <div class="w-full max-w-md">

        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-14 h-14 bg-emerald-700 rounded-2xl mb-3">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Devenez partenaire</h1>
            <p class="text-gray-500 text-sm mt-1">Réduisez vos pertes, gagnez une nouvelle clientèle</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-7">

            <?php if (isset($inscriptionReussie)) { ?>
                <div class="text-center py-6">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-orange-100 rounded-full mb-3">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-gray-800 font-semibold mb-1">Inscription reçue !</p>
                    <p class="text-gray-500 text-sm mb-4">Votre compte est en cours de validation par notre équipe. Vous recevrez un accès sous peu.</p>
                    <a href="index.php" class="inline-block bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-medium transition">Retour à l'accueil</a>
                </div>
            <?php } else { ?>

                <?php if (isset($erreurInscription)) { ?>
                    <p class="bg-red-50 text-red-600 text-sm text-center p-3 rounded-lg mb-4"><?php echo $erreurInscription; ?></p>
                <?php } ?>

                <form action="" method="POST" class="space-y-3">
                    <div>
                        <label class="text-xs font-medium text-gray-500 mb-1 block">Nom de la boutique</label>
                        <input type="text" name="nom_boutique" placeholder="Ex : Boulangerie du Coin" class="border border-gray-200 rounded-lg p-3 w-full text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <div>
                        <label class="text-xs font-medium text-gray-500 mb-1 block">Catégorie</label>
                        <select name="categorie" class="border border-gray-200 rounded-lg p-3 w-full text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white">
                            <option value="">-- Choisir --</option>
                            <option value="Boulangerie">🥖 Boulangerie</option>
                            <option value="Restaurant">🍽️ Restaurant</option>
                            <option value="Supermarche">🛒 Supermarché</option>
                            <option value="Traiteur">🍱 Traiteur</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-medium text-gray-500 mb-1 block">Email</label>
                        <input type="email" name="email" placeholder="vous@boutique.com" class="border border-gray-200 rounded-lg p-3 w-full text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <div>
                        <label class="text-xs font-medium text-gray-500 mb-1 block">Mot de passe</label>
                        <input type="password" name="mot_de_passe" placeholder="••••••••" class="border border-gray-200 rounded-lg p-3 w-full text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <div>
                        <label class="text-xs font-medium text-gray-500 mb-1 block">Confirmer le mot de passe</label>
                        <input type="password" name="confirmation_mot_de_passe" placeholder="••••••••" class="border border-gray-200 rounded-lg p-3 w-full text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <div>
                        <label class="text-xs font-medium text-gray-500 mb-1 block">Adresse</label>
                        <input type="text" name="adresse" placeholder="Quartier, ville" class="border border-gray-200 rounded-lg p-3 w-full text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <div>
                        <label class="text-xs font-medium text-gray-500 mb-1 block">Téléphone</label>
                        <input type="text" name="telephone" placeholder="+229 XX XX XX XX" class="border border-gray-200 rounded-lg p-3 w-full text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <button type="submit" class="bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg p-3 w-full font-semibold transition mt-2">Créer mon compte</button>
                </form>

            <?php } ?>
        </div>

        <p class="text-center text-sm text-gray-500 mt-5">
            Déjà partenaire ? <a href="espace-commercant.php" class="text-emerald-700 font-medium underline">Se connecter</a>
        </p>

    </div>
</div>

<?php include "footer.php"; ?>