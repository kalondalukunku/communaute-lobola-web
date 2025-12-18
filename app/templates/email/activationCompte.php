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
        <h2>Plateforme de Gestion des Documents Administratifs</h2>
    </div>

    <div class="content">
        <p>Bonjour cher <strong><?= $role .' '. $nom ?></strong>,</p>

        <p>
            Nous vous informons que le Secrétaire Général vous a ajouté en tant qu’utilisateur sur la plateforme de gestion des documents administratifs de l’institution.
        </p>

        <p>
            Afin d’activer votre compte et commencer à utiliser le système, veuillez cliquer sur le bouton ci-dessous pour définir votre mot de passe personnel.
        </p>

        <div style="text-align:center; margin: 40px 0;">
            <a href="<?= $lien_activation ?>" class="primary-btn">Activer mon compte</a>
        </div>

        <p><strong>Informations de connexion :</strong></p>
        <ul>
            <li><strong>Identifiant :</strong> <?= $email ?></li>
            <li><strong>Rôle :</strong> <?= $role ?></li>
        </ul>

        <p style="color:#c0392b;">
            Ce lien est personnel et sécurisé.
        </p>

        <p>
            Si vous n’êtes pas à l’origine de cette demande, veuillez contacter l’administration.
        </p>

        <p>
            Cordialement,<br><br>
            <strong>L’Administration</strong><br>
            Plateforme de Gestion des Documents Administratifs<br>
            📧 <?= ADMIN_EMAIL ?>
        </p>
    </div>

    <div class="footer">
        © <?= date('Y') ." - ". SITE_NAME ?> - Plateforme Administrative | Tous droits réservés
    </div>

</div>

</body>
</html>