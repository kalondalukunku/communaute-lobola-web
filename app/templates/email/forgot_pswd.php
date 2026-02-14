<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de votre mot de passe</title>
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
            max-width: 90px;
            border: 2px solid #cfbb30;
            border-radius: 50%;
            padding: 5px;
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
            <!-- Header -->
            <tr>
                <td class="header">
                    <img src="https://communaute-lobola.ankhing.com/assets/images/logo.jpg" alt="Logo">
                    <h1>Mot de passe oublié</h1>
                </td>
            </tr>

            <!-- Contenu Principal -->
            <tr>
                <td class="content">                    
                    <div class="status-badge">Sécurité du compte</div>
                    <h2>Réinitialisation demandée</h2>
                    
                    <p>Bonjour, nous avons reçu une demande de réinitialisation de mot de passe pour votre compte sur notre plateforme.</p>
                    
                     <div class="instruction-box">
                        <strong>Note importante :</strong>
                        <p style="margin-top: 8px; margin-bottom: 0;">Ce lien de réinitialisation est valable pour une durée limitée. Si vous n'êtes pas à l'origine de cette demande, vous pouvez ignorer cet e-mail en toute sécurité.</p>
                    </div>

                    <div class="button-container">
                        <a href="<?= $lien_reset ?>" class="button-primary">Réinitialiser mon mot de passe</a>
                    </div>

                    <p style="font-size: 14px; margin-top: 20px; color: #888888;">
                        Si vous n'avez pas demandé cette réinitialisation, veuillez ignorer cet e-mail ou contacter notre support client si vous avez des préoccupations concernant la sécurité de votre compte.
                    </p>
                </td>
            </tr>

            <!-- Pied de page -->
            <tr>
                <td class="footer">
                    <p>Ceci est un message automatique de sécurité, merci de ne pas y répondre directement.</p>
                    <p>&copy; <?= date('Y') ?> <?= SITE_NAME ?>. Tous droits réservés.</p>
                </td>
            </tr>
        </table>
    </center>
</body>
</html>