<?php
require "connexion.php";

$categorie = isset($_GET["categorie"]) ? $_GET["categorie"] : "";
$recherche = isset($_GET["recherche"]) ? $_GET["recherche"] : "";

$sql = "SELECT paniers.*, commercants.nom_boutique, commercants.categorie AS commercant_categorie 
        FROM paniers 
        JOIN commercants ON paniers.commercant_id = commercants.id 
        WHERE paniers.statut = 'disponible' AND paniers.quantite_disponible > 0";

$params = [];

if ($categorie !== "") {
    $sql .= " AND commercants.categorie = :categorie";
    $params["categorie"] = $categorie;
}

if ($recherche !== "") {
    $sql .= " AND (paniers.titre LIKE :recherche OR commercants.nom_boutique LIKE :recherche)";
    $params["recherche"] = "%$recherche%";
}

$requete = $pdo->prepare($sql);
$requete->execute($params);
$paniers = $requete->fetchAll();

$titrePage = "Anti-Gaspi — Accueil";
include "header.php";
?>

<style>
    /* --- Animations Anti-Gaspi --- */
    .panier-card {
        opacity: 0;
        transform: translateY(24px);
        transition: opacity 0.5s ease, transform 0.5s ease;
    }
    .panier-card.visible {
        opacity: 1;
        transform: translateY(0);
    }
    .filtre-btn {
        transition: transform 0.15s ease;
    }
    .filtre-btn:active {
        transform: scale(0.95);
    }
    @keyframes pulse-badge {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.08); }
    }
    .badge-urgent {
        animation: pulse-badge 1.8s ease-in-out infinite;
    }
    @keyframes fade-slide-down {
        from { opacity: 0; transform: translateY(-12px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .hero-anim > * {
        animation: fade-slide-down 0.6s ease both;
    }
    .hero-anim > *:nth-child(2) { animation-delay: 0.1s; }
    .hero-anim > *:nth-child(3) { animation-delay: 0.2s; }
    .search-wrapper {
        transition: transform 0.2s ease;
    }
</style>

    <!-- Hero -->
    <div class="bg-gradient-to-br from-emerald-700 to-emerald-600 text-white">
        <div class="max-w-5xl mx-auto px-4 py-10 text-center hero-anim">
            <p class="text-emerald-200 text-sm font-medium tracking-wide uppercase mb-2">📍 Cotonou, Bénin</p>
            <h1 class="text-3xl md:text-4xl font-extrabold leading-tight mb-2">Sauvez de bons repas,<br class="hidden md:block"> à petit prix</h1>
            <p class="text-emerald-100 text-sm md:text-base">Récupérez les invendus de vos commerces préférés avant qu'ils ne soient jetés</p>
        </div>
    </div>

   <!-- Barre de recherche (chevauche le hero) -->
<div class="max-w-xl mx-auto px-4 -mt-6 relative z-10">
    <form action="" method="GET" class="relative search-wrapper">
        <button type="submit" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-emerald-600 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/>
            </svg>
        </button>
        <input type="text" name="recherche" placeholder="Rechercher un commerce ou un plat..." 
               value="<?php echo htmlspecialchars($recherche); ?>"
               class="border border-gray-200 rounded-xl pl-11 pr-4 py-3 w-full shadow-lg bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
    </form>
</div>

    <!-- Filtres par catégorie -->
    <div class="flex gap-2 overflow-x-auto px-4 py-6 max-w-5xl mx-auto">
        <a href="index.php" class="filtre-btn px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition <?php echo $categorie === '' ? 'bg-emerald-700 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:border-emerald-300'; ?>">Tous</a>
        <a href="index.php?categorie=Boulangerie" class="filtre-btn px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition <?php echo $categorie === 'Boulangerie' ? 'bg-emerald-700 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:border-emerald-300'; ?>">🥖 Boulangerie</a>
        <a href="index.php?categorie=Restaurant" class="filtre-btn px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition <?php echo $categorie === 'Restaurant' ? 'bg-emerald-700 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:border-emerald-300'; ?>">🍽️ Restaurant</a>
        <a href="index.php?categorie=Supermarche" class="filtre-btn px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition <?php echo $categorie === 'Supermarche' ? 'bg-emerald-700 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:border-emerald-300'; ?>">🛒 Supermarché</a>
        <a href="index.php?categorie=Traiteur" class="filtre-btn px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition <?php echo $categorie === 'Traiteur' ? 'bg-emerald-700 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:border-emerald-300'; ?>">🍱 Traiteur</a>
    </div>

    <!-- Liste des paniers -->
    <div class="max-w-5xl mx-auto px-4 pb-16">
        <?php if (count($paniers) === 0) { ?>
            <div class="text-center py-16">
                <p class="text-gray-400 text-lg">Aucun panier disponible pour le moment</p>
                <p class="text-gray-400 text-sm mt-1">Revenez un peu plus tard 🌱</p>
            </div>
        <?php } ?>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <?php foreach ($paniers as $panier) { ?>
                <a href="panier.php?id=<?php echo $panier['id']; ?>" class="panier-card group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    
                    <div class="h-32 bg-gradient-to-br from-emerald-100 to-emerald-50 flex items-center justify-center relative">
                        <span class="text-4xl">🥡</span>
                        <?php if ($panier["quantite_disponible"] <= 3) { ?>
                            <span class="badge-urgent absolute top-3 right-3 bg-orange-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow">
                                Plus que <?php echo $panier["quantite_disponible"]; ?>
                            </span>
                        <?php } ?>
                    </div>

                    <div class="p-4">
                        <h2 class="font-bold text-gray-900 group-hover:text-emerald-700 transition"><?php echo $panier["titre"]; ?></h2>
                        <p class="text-gray-500 text-sm mb-3"><?php echo $panier["nom_boutique"]; ?></p>

                        <div class="flex items-end justify-between">
                            <div>
                                <span class="text-emerald-700 font-extrabold text-lg"><?php echo $panier["prix_reduit"]; ?> F</span>
                                <span class="text-gray-400 line-through text-sm ml-1"><?php echo $panier["prix_normal"]; ?> F</span>
                            </div>
                            <span class="text-xs bg-emerald-50 text-emerald-700 font-semibold px-2 py-1 rounded-full">
                                -<?php echo round((1 - $panier["prix_reduit"] / $panier["prix_normal"]) * 100); ?>%
                            </span>
                        </div>
                    </div>
                </a>
            <?php } ?>
        </div>
    </div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // Apparition des cartes au scroll, avec effet stagger
    const cartes = document.querySelectorAll(".panier-card");

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.classList.add("visible");
                }, index * 80); // décalage progressif
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    cartes.forEach((carte) => observer.observe(carte));

    // Petite animation au focus de la barre de recherche
    const inputRecherche = document.querySelector('input[name="recherche"]');
    if (inputRecherche) {
        const wrapper = inputRecherche.closest(".search-wrapper");
        inputRecherche.addEventListener("focus", function () {
            if (wrapper) wrapper.style.transform = "scale(1.02)";
        });
        inputRecherche.addEventListener("blur", function () {
            if (wrapper) wrapper.style.transform = "scale(1)";
        });
    }

});
</script>

<?php include "footer.php"; ?>