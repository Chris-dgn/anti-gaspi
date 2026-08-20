<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title><?php echo isset($titrePage) ? $titrePage : "Anti-Gaspi"; ?></title>
</head>
<body class="bg-gray-50 pb-20">

    <nav class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-10">
        <div class="max-w-5xl mx-auto flex justify-between items-center px-4 py-3">

            <a href="index.php" class="flex items-center gap-2">
                <svg class="w-8 h-8" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="20" cy="20" r="20" fill="#047857"/>
                    <path d="M13 22C13 16 17 12 24 12C24 19 20 23 13 22Z" fill="white"/>
                    <path d="M13 22C13 26 16 28 20 27" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
                <span class="text-xl font-bold text-gray-900">Anti<span class="text-emerald-700">Gaspi</span></span>
            </a>

            <div class="hidden md:flex items-center gap-1 text-sm font-medium">
                <a href="index.php" class="px-3 py-2 rounded-lg text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 transition">Accueil</a>
                <a href="mes-reservations.php" class="px-3 py-2 rounded-lg text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 transition">Mes réservations</a>
                <a href="espace-client.php" class="px-3 py-2 rounded-lg text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 transition">Profil</a>
                <a href="espace-commercant.php" class="ml-2 px-4 py-2 rounded-lg bg-emerald-700 text-white hover:bg-emerald-800 transition">Espace commerçant</a>
            </div>

        </div>
    </nav>