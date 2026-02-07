<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Action requise : Votre intégration</title>
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
            color: #ffffff;
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
            background-color: rgba(231, 76, 60, 0.1);
            color: #e74c3c;
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 25px;
            border: 1px solid #e74c3c;
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
        .rejection-box {
            background-color: #fdf2f2;
            border-left: 4px solid #e74c3c;
            padding: 20px;
            margin: 30px 0;
            text-align: left;
            border-radius: 4px;
        }
        .rejection-box strong {
            color: #e74c3c;
            display: block;
            margin-bottom: 5px;
        }
        .button-container {
            margin: 35px 0;
        }
        .button-primary {
            background-color: #000f0e;
            color: #cfbb30 !important;
            text-decoration: none;
            padding: 18px 40px;
            border-radius: 8px;
            font-weight: bold;
            display: inline-block;
            font-size: 16px;
            box-shadow: 0 4px 12px rgba(0, 15, 14, 0.2);
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
            <!-- Header -->
            <tr>
                <td class="header">
                    <img src="https://communaute-lobola.ankhing.com/assets/images/logo.jpg" alt="[Logo de l'Organisation]">
                    <h1>Information</h1>
                </td>
            </tr>

            <!-- Contenu Principal -->
            <tr>
                <td class="content">
                    <div class="status-badge">INTÉGRATION À CORRIGER</div>
                    
                    <h2>Demande de mise à jour</h2>
                    
                    <p>Nous avons examiné votre demande d'intégration. Malheureusement, certains éléments ne nous permettent pas de la valider en l'état.</p>
                    
                    <!-- Bloc Motif du Rejet -->
                    <div class="rejection-box">
                        <strong>Motif du refus :</strong>
                        <p style="margin: 0; color: #333;">
                            <?= $motif ?>
                        </p>
                    </div>

                    <p style="font-size: 15px;">Rassurez-vous, il vous suffit de corriger les informations mentionnées ci-dessus pour soumettre à nouveau votre demande.</p>

                    <div class="button-container">
                        <a href="<?= $lien_correction ?>" class="button-primary">Modifier ma demande</a>
                    </div>

                    <p style="font-size: 14px; margin-top: 20px; color: #888;">
                        Si vous avez des questions concernant ce retour, n'hésitez pas à contacter notre support technique.
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