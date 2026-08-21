<?php
$titrePage = "Mentions légales - AntiGaspi";
include "header.php";
?>

<div class="max-w-3xl mx-auto px-4 py-12">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Mentions légales</h1>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6 text-sm text-gray-600 leading-relaxed">

        <div>
            <h2 class="font-bold text-gray-900 mb-2">Éditeur du site</h2>
            <p>
                Le site AntiGaspi est édité par : <strong>[Nom de l'entreprise / du porteur de projet]</strong><br>
                Forme juridique : [à compléter]<br>
                Siège social : [Adresse, Cotonou, Bénin]<br>
                Numéro d'immatriculation : [à compléter]<br>
                Email de contact : [à compléter]<br>
                Téléphone : [à compléter]
            </p>
        </div>

        <div>
            <h2 class="font-bold text-gray-900 mb-2">Directeur de la publication</h2>
            <p>[Nom du responsable de la publication]</p>
        </div>

        <div>
            <h2 class="font-bold text-gray-900 mb-2">Hébergement</h2>
            <p>
                Le site est hébergé par : <strong>[Nom de l'hébergeur]</strong><br>
                Adresse : [Adresse de l'hébergeur]<br>
                Site web : [URL de l'hébergeur]
            </p>
        </div>

        <div>
            <h2 class="font-bold text-gray-900 mb-2">Propriété intellectuelle</h2>
            <p>L'ensemble des contenus présents sur le site AntiGaspi (textes, logo, images, mise en page) est protégé par le droit d'auteur. Toute reproduction, même partielle, est interdite sans autorisation préalable.</p>
        </div>

        <div>
            <h2 class="font-bold text-gray-900 mb-2">Responsabilité</h2>
            <p>AntiGaspi met en relation des commerçants et des clients autour d'invendus alimentaires. AntiGaspi ne saurait être tenu responsable de la qualité des produits proposés par les commerçants partenaires, ceux-ci restant seuls responsables des paniers qu'ils publient.</p>
        </div>

        <div>
            <h2 class="font-bold text-gray-900 mb-2">Contact</h2>
            <p>Pour toute question relative à ces mentions légales, vous pouvez nous contacter à l'adresse : [email de contact].</p>
        </div>

    </div>

    <p class="text-xs text-gray-400 mt-6">Dernière mise à jour : <?php echo date("d/m/Y"); ?></p>
</div>

<?php include "footer.php"; ?>