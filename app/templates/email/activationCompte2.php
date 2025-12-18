<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
    .primary-btn {
        background: #0dcaf0;
        color: #000;
        border: none;
        padding: 12px 26px;
        border-radius: 25px;
        cursor: pointer;
        font-size: 14px;
        transition: 0.25s ease;
    }
    .container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    .header {
        text-align: center;
        margin-bottom: 30px;
    }
    .content {
        font-size: 14px;
        line-height: 1.6;
        color: #fff;
    }
    .footer {
        text-align: center;
        font-size: 12px;
        color: #0dcaf0;
        margin-top: 30px;
    }
</style>
</head>
<body style="font-family: sans-serif; background-color: #0DCAF02b; margin: 0;">

    <div class="container">

        <div class="header">
            <h2>Compte activé avec succès</h2>
        </div>

        <div class="content">

            <p>Bonjour cher <strong><?= $user->role . ' ' . $user->nom; ?></strong>,</p>

            <p>
                Nous vous confirmons que votre compte sur la plateforme de gestion des documents administratifs <?= SITE_NAME; ?> a été activé avec succès.
            </p>

            <p>
                Vous pouvez désormais vous connecter et accéder à votre espace personnel pour gérer, envoyer et consulter vos documents administratifs selon votre rôle.
            </p>

            <p><strong>Vos informations de connexion :</strong></p>
            <ul>
                <li><strong>Identifiant :</strong> <?= $user->email; ?></li>
                <li><strong>Rôle :</strong> <?= $user->role; ?></li>
            </ul>

            <div style="text-align:center; margin: 40px 0;">
                <a href="<?= $lien_connexion; ?>" class="primary-btn">Accéder à mon espace</a>
            </div>

            <p>
                Pour votre sécurité, veillez à ne jamais partager vos identifiants avec un tiers.
            </p>

            <p>
                Si vous rencontrez un problème d’accès, veuillez contacter l’administration.
            </p>

            <p style="margin-top:30px;">
                Cordialement,<br><br>
                <strong>L’Administration</strong><br>
                Plateforme de Gestion des Documents Administratifs<br>
                📧 <?= ADMIN_EMAIL; ?>
            </p>

        </div>

        <div class="footer">
            © <?= date('Y') ." - ". SITE_NAME ?> - Plateforme Administrative | Tous droits réservés
        </div>

    </div>

</body>
</html>