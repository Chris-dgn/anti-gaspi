<?php
$titrePage = "Confidentialité - AntiGaspi";
include "header.php";
?>

<div class="max-w-3xl mx-auto px-4 py-12">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Politique de confidentialité</h1>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6 text-sm text-gray-600 leading-relaxed">

        <div>
            <h2 class="font-bold text-gray-900 mb-2">1. Données collectées</h2>
            <p>Dans le cadre de l'utilisation d'AntiGaspi, nous collectons les données suivantes : nom, adresse email, numéro de téléphone (clients et commerçants), ainsi que les informations liées aux réservations et transactions effectuées sur la plateforme.</p>
        </div>

        <div>
            <h2 class="font-bold text-gray-900 mb-2">2. Utilisation des données</h2>
            <p>Ces données sont utilisées pour :</p>
            <ul class="list-disc pl-5 mt-2 space-y-1">
                <li>Permettre la création et la gestion de votre compte</li>
                <li>Traiter vos réservations et paiements</li>
                <li>Vous contacter en cas de besoin concernant une réservation</li>
                <li>Améliorer le fonctionnement de la plateforme</li>
            </ul>
        </div>

        <div>
            <h2 class="font-bold text-gray-900 mb-2">3. Partage des données</h2>
            <p>Les informations nécessaires à la réservation (nom, téléphone) sont partagées avec le commerçant concerné afin de faciliter la remise du panier. AntiGaspi ne vend ni ne loue vos données personnelles à des tiers.</p>
        </div>

        <div>
            <h2 class="font-bold text-gray-900 mb-2">4. Sécurité</h2>
            <p>Les mots de passe sont stockés de manière chiffrée. Les paiements sont traités par des prestataires tiers sécurisés (MTN MoMo, Moov Money) ; AntiGaspi ne stocke aucune donnée bancaire.</p>
        </div>

        <div>
            <h2 class="font-bold text-gray-900 mb-2">5. Conservation des données</h2>
            <p>Vos données sont conservées le temps nécessaire à la gestion de votre compte et de vos réservations, conformément à la réglementation en vigueur.</p>
        </div>

        <div>
            <h2 class="font-bold text-gray-900 mb-2">6. Vos droits</h2>
            <p>Vous disposez d'un droit d'accès, de rectification et de suppression de vos données personnelles. Pour exercer ces droits, contactez-nous à l'adresse : [email de contact].</p>
        </div>

    </div>

    <p class="text-xs text-gray-400 mt-6">Dernière mise à jour : <?php echo date("d/m/Y"); ?></p>
</div>

<?php include "footer.php"; ?>