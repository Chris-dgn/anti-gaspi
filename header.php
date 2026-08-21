<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.kkiapay.me/k.js"></script>
    <title><?php echo isset($titrePage) ? $titrePage : "Anti-Gaspi"; ?></title>

    <style>
        /* --- Effets au survol navigation --- */
        .logo-nav svg {
            transition: transform 0.3s ease;
        }
        .logo-nav:hover svg {
            transform: rotate(-8deg) scale(1.05);
        }
        .lien-nav {
            position: relative;
        }
        .lien-nav::after {
            content: "";
            position: absolute;
            left: 12px;
            right: 12px;
            bottom: 2px;
            height: 2px;
            background-color: #047857;
            border-radius: 999px;
            transform: scaleX(0);
            transform-origin: center;
            transition: transform 0.2s ease;
        }
        .lien-nav:hover::after,
        .lien-nav.actif::after {
            transform: scaleX(1);
        }
        .lien-nav.actif {
            color: #047857;
            font-weight: 600;
        }
        .btn-nav-commercant {
            transition: transform 0.15s ease, box-shadow 0.2s ease;
        }
        .btn-nav-commercant:hover {
            box-shadow: 0 6px 16px -4px rgba(4, 120, 87, 0.5);
        }
        .btn-nav-commercant:active {
            transform: scale(0.96);
        }

        /* --- Menu hamburger mobile --- */
        .btn-burger {
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 5px;
            width: 32px;
            height: 32px;
        }
        .btn-burger span {
            display: block;
            height: 2px;
            width: 100%;
            background-color: #374151;
            border-radius: 999px;
            transition: transform 0.25s ease, opacity 0.2s ease;
        }
        .btn-burger.ouvert span:nth-child(1) {
            transform: translateY(7px) rotate(45deg);
        }
        .btn-burger.ouvert span:nth-child(2) {
            opacity: 0;
        }
        .btn-burger.ouvert span:nth-child(3) {
            transform: translateY(-7px) rotate(-45deg);
        }
        #menu-mobile {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        #menu-mobile.ouvert {
            max-height: 300px;
        }
        .lien-nav-mobile {
            transition: background-color 0.15s ease, padding-left 0.15s ease;
        }
        .lien-nav-mobile:hover {
            padding-left: 20px;
        }
        .lien-nav-mobile.actif {
            color: #047857;
            font-weight: 600;
            background-color: #ecfdf5;
        }
    </style>
</head>
<body class="bg-gray-50">

    <nav class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-10">
        <div class="max-w-5xl mx-auto flex justify-between items-center px-4 py-3">

            <a href="index.php" class="logo-nav flex items-center gap-2">
                <svg class="w-8 h-8" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="20" cy="20" r="20" fill="#047857"/>
                    <path d="M13 22C13 16 17 12 24 12C24 19 20 23 13 22Z" fill="white"/>
                    <path d="M13 22C13 26 16 28 20 27" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
                <span class="text-xl font-bold text-gray-900">Anti<span class="text-emerald-700">Gaspi</span></span>
            </a>

            <?php $pageActuelle = basename($_SERVER["PHP_SELF"]); ?>

            <div class="hidden md:flex items-center gap-1 text-sm font-medium">
                <a href="index.php" class="lien-nav <?php echo $pageActuelle === 'index.php' ? 'actif' : ''; ?> px-3 py-2 rounded-lg text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 transition">Accueil</a>
                <a href="mes-reservations.php" class="lien-nav <?php echo $pageActuelle === 'mes-reservations.php' ? 'actif' : ''; ?> px-3 py-2 rounded-lg text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 transition">Mes réservations</a>
                <a href="espace-client.php" class="lien-nav <?php echo $pageActuelle === 'espace-client.php' ? 'actif' : ''; ?> px-3 py-2 rounded-lg text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 transition">Profil</a>
                <a href="espace-commercant.php" class="btn-nav-commercant ml-2 px-4 py-2 rounded-lg bg-emerald-700 text-white hover:bg-emerald-800 transition">Espace commerçant</a>
            </div>

            <button id="btn-menu-mobile" class="btn-burger md:hidden" aria-label="Ouvrir le menu" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>

        </div>

        <!-- Menu déroulant mobile -->
        <div id="menu-mobile" class="md:hidden border-t border-gray-100">
            <div class="flex flex-col text-sm font-medium px-4 py-2">
                <a href="index.php" class="lien-nav-mobile <?php echo $pageActuelle === 'index.php' ? 'actif' : ''; ?> px-3 py-3 rounded-lg text-gray-600">Accueil</a>
                <a href="mes-reservations.php" class="lien-nav-mobile <?php echo $pageActuelle === 'mes-reservations.php' ? 'actif' : ''; ?> px-3 py-3 rounded-lg text-gray-600">Mes réservations</a>
                <a href="espace-client.php" class="lien-nav-mobile <?php echo $pageActuelle === 'espace-client.php' ? 'actif' : ''; ?> px-3 py-3 rounded-lg text-gray-600">Profil</a>
                <a href="espace-commercant.php" class="lien-nav-mobile <?php echo $pageActuelle === 'espace-commercant.php' ? 'actif' : ''; ?> px-3 py-3 rounded-lg text-emerald-700 font-semibold">Espace commerçant</a>
            </div>
        </div>
    </nav>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const boutonBurger = document.getElementById("btn-menu-mobile");
            const menuMobile = document.getElementById("menu-mobile");

            boutonBurger.addEventListener("click", function () {
                const estOuvert = menuMobile.classList.toggle("ouvert");
                boutonBurger.classList.toggle("ouvert", estOuvert);
                boutonBurger.setAttribute("aria-expanded", estOuvert ? "true" : "false");
            });

            // Ferme le menu si on clique sur un lien
            menuMobile.querySelectorAll("a").forEach(function (lien) {
                lien.addEventListener("click", function () {
                    menuMobile.classList.remove("ouvert");
                    boutonBurger.classList.remove("ouvert");
                    boutonBurger.setAttribute("aria-expanded", "false");
                });
            });
        });
    </script>