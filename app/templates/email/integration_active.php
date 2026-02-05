<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approbation de votre intégration</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            color: #000f0e;
        }
        table {
            border-spacing: 0;
            width: 100%;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f4f4f4;
            padding: 40px 0;
        }
        .main {
            background-color: #ffffff;
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,15,14,0.08);
        }
        .header {
            background-color: #000f0e;
            padding: 40px 20px;
            text-align: center;
        }
        .header img {
            max-width: 120px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #cfbb30;
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .content {
            padding: 40px;
            text-align: center;
            line-height: 1.6;
        }
        .status-badge {
            display: inline-block;
            background-color: rgba(207, 187, 48, 0.1);
            color: #cfbb30;
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 25px;
            border: 1px solid #cfbb30;
        }
        .content h2 {
            font-size: 22px;
            margin-bottom: 15px;
            color: #000f0e;
        }
        .content p {
            font-size: 16px;
            color: #444444;
            margin-bottom: 30px;
        }
        .button-container {
            margin: 35px 0;
        }
        .button-primary {
            background-color: #cfbb30;
            color: #000f0e !important;
            text-decoration: none;
            padding: 18px 40px;
            border-radius: 8px;
            font-weight: bold;
            display: inline-block;
            font-size: 16px;
            box-shadow: 0 4px 12px rgba(207, 187, 48, 0.3);
        }
        .instruction-box {
            background-color: #cfba301f;
            border-left: 4px solid #cfbb30;
            padding: 20px;
            margin: 30px 0;
            text-align: left;
            border-radius: 4px;
        }
        .footer {
            padding: 30px;
            text-align: center;
            font-size: 12px;
            color: #888888;
            background-color: #fafafa;
            border-top: 1px solid #eeeeee;
        }
        @media screen and (max-width: 600px) {
            .content {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <center class="wrapper">
        <table class="main">
            <!-- Header avec Image d'en-tête -->
            <tr>
                <td class="header">
                    <!-- Image représentative de l'intégration -->
                    <img src="https://communaute-lobola.ankhing.com/assets/images/logo.jpg" alt="[Image de l'Intégration]">
                    <h1>Félicitations</h1>
                </td>
            </tr>

            <!-- Contenu Principal -->
            <tr>
                <td class="content">
                    <div class="status-badge">INTÉGRATION APPROUVÉE</div>
                    
                    <h2>Votre demande est validée</h2>
                    
                    <p>Nous avons le plaisir de vous informer que votre processus d'intégration a été examiné et <strong>approuvé avec succès</strong> par notre équipe technique.</p>
                    
                    <!-- <p>Vous pouvez désormais accéder à l'ensemble des fonctionnalités et services liés à cette intégration en vous connectant à votre tableau de bord.</p> -->
                     <div class="instruction-box">
                        <strong>Étape obligatoire :</strong>
                        <p style="margin-top: 8px; margin-bottom: 0;">Pour sécuriser votre progression et accéder aux contenus exclusifs, vous devez définir votre mot de passe personnel en cliquant sur le bouton ci-dessous.</p>
                    </div>

                    <div class="button-container">
                        <a href="<?= $lien_activation ?>" class="button-primary">Définir mon mot de passe</a>
                    </div>

                    <p style="font-size: 14px; margin-top: 20px;">
                        Besoin d'aide ? Notre support technique est à votre disposition pour vous accompagner dans vos premiers pas.
                    </p>
                </td>
            </tr>

            <!-- Pied de page -->
            <tr>
                <td class="footer">
                    <p>Ceci est un message automatique, merci de ne pas y répondre directement.</p>
                    <p>&copy; <?= date('Y') ?> <?= SITE_NAME ?>. Tous droits réservés.</p>
                </td>
            </tr>
        </table>
    </center>
</body>
</html>