<style>
    /* --- Animations footer + navigation mobile --- */
    .footer-colonne {
        opacity: 0;
        transform: translateY(16px);
        animation: footer-slide-up 0.5s ease forwards;
    }
    .footer-colonne:nth-child(1) { animation-delay: 0.05s; }
    .footer-colonne:nth-child(2) { animation-delay: 0.12s; }
    .footer-colonne:nth-child(3) { animation-delay: 0.19s; }
    .footer-colonne:nth-child(4) { animation-delay: 0.26s; }
    @keyframes footer-slide-up {
        to { opacity: 1; transform: translateY(0); }
    }
    .footer-lien {
        position: relative;
    }
    .footer-lien::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: -2px;
        width: 0;
        height: 1px;
        background-color: #34d399;
        transition: width 0.2s ease;
    }
    .footer-lien:hover::after {
        width: 100%;
    }
    .badge-paiement {
        transition: transform 0.15s ease;
    }
    .badge-paiement:hover {
        transform: translateY(-2px) scale(1.05);
    }
    .logo-footer svg {
        transition: transform 0.3s ease;
    }
    .logo-footer:hover svg {
        transform: rotate(-8deg) scale(1.05);
    }

</style>

<footer class="bg-gray-900 text-gray-300 mt-12 pt-10 pb-10">
        <div class="max-w-5xl mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-8">

            <div class="footer-colonne logo-footer">
                <h3 class="text-white font-bold mb-3 flex items-center gap-2">
                    <svg class="w-6 h-6" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="20" cy="20" r="20" fill="#047857"/>
                        <path d="M13 22C13 16 17 12 24 12C24 19 20 23 13 22Z" fill="white"/>
                        <path d="M13 22C13 26 16 28 20 27" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                    AntiGaspi
                </h3>
                <p class="text-sm text-gray-400">Luttons ensemble contre le gaspillage alimentaire à Cotonou, un panier à la fois.</p>
            </div>

            <div class="footer-colonne">
                <h3 class="text-white font-semibold mb-3 text-sm">Découvrir</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="index.php" class="footer-lien hover:text-emerald-400 transition">Accueil</a></li>
                    <li><a href="comment-ça-marche.php" class="footer-lien hover:text-emerald-400 transition">Comment ça marche</a></li>
                    <li><a href="espace-commercant.php" class="footer-lien hover:text-emerald-400 transition">Vous êtes commerçant ?</a></li>
                </ul>
            </div>

            <div class="footer-colonne">
                <h3 class="text-white font-semibold mb-3 text-sm">Légal</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="mention-legale.php" class="footer-lien hover:text-emerald-400 transition">Mentions légales</a></li>
                    <li><a href="condition-utilisateur.php" class="footer-lien hover:text-emerald-400 transition">Conditions d'utilisation</a></li>
                    <li><a href="confidentialite.php" class="footer-lien hover:text-emerald-400 transition">Confidentialité</a></li>
                </ul>
            </div>

            <div class="footer-colonne">
                <h3 class="text-white font-semibold mb-3 text-sm">Paiement sécurisé</h3>
                <div class="flex gap-2">
                    <span class="badge-paiement bg-yellow-500 text-black text-xs font-bold px-2 py-1 rounded-md">MTN MoMo</span>
                    <span class="badge-paiement bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded-md">Moov Money</span>
                </div>
            </div>

        </div>

        <div class="text-center text-xs text-gray-500 mt-8 border-t border-gray-800 pt-4">
            © <?php echo date("Y"); ?> AntiGaspi — Fait par Chris DANGNON au Bénin
        </div>
    </footer>

</body>
</html>