<?php
$titrePage = "Comment ça marche - AntiGaspi";
include "header.php";
?>

<style>
    @keyframes slide-up-in {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .etape {
        opacity: 0;
        animation: slide-up-in 0.5s ease forwards;
    }
    .etape:nth-child(1) { animation-delay: 0.05s; }
    .etape:nth-child(2) { animation-delay: 0.15s; }
    .etape:nth-child(3) { animation-delay: 0.25s; }
    .numero-etape {
        transition: transform 0.2s ease;
    }
    .etape:hover .numero-etape {
        transform: scale(1.1) rotate(-4deg);
    }
</style>

<div class="bg-gradient-to-br from-emerald-700 to-emerald-600 text-white">
    <div class="max-w-3xl mx-auto px-4 py-12 text-center">
        <h1 class="text-3xl md:text-4xl font-extrabold mb-2">Comment ça marche ?</h1>
        <p class="text-emerald-100 text-sm md:text-base">Trois étapes pour sauver de bons repas et faire des économies</p>
    </div>
</div>

<div class="max-w-3xl mx-auto px-4 py-12">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">

        <div class="etape bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
            <div class="numero-etape inline-flex items-center justify-center w-12 h-12 bg-emerald-700 text-white rounded-full font-bold text-lg mb-4">1</div>
            <h2 class="font-bold text-gray-900 mb-2">Parcourez les paniers</h2>
            <p class="text-sm text-gray-500">Découvrez les invendus disponibles près de chez vous, chez vos boulangeries, restaurants et supermarchés préférés.</p>
        </div>

        <div class="etape bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
            <div class="numero-etape inline-flex items-center justify-center w-12 h-12 bg-emerald-700 text-white rounded-full font-bold text-lg mb-4">2</div>
            <h2 class="font-bold text-gray-900 mb-2">Réservez et payez</h2>
            <p class="text-sm text-gray-500">Réservez votre panier surprise à prix réduit et payez en toute sécurité par MTN MoMo ou Moov Money.</p>
        </div>

        <div class="etape bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
            <div class="numero-etape inline-flex items-center justify-center w-12 h-12 bg-emerald-700 text-white rounded-full font-bold text-lg mb-4">3</div>
            <h2 class="font-bold text-gray-900 mb-2">Récupérez votre panier</h2>
            <p class="text-sm text-gray-500">Rendez-vous sur place pendant le créneau indiqué pour récupérer votre panier auprès du commerçant.</p>
        </div>

    </div>

    <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-6 text-center">
        <p class="text-emerald-800 font-medium"> Chaque panier récupéré, c'est un peu moins de gaspillage et un peu plus d'économies pour vous.</p>
    </div>
</div>

<?php include "footer.php"; ?>