<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvel Enseignement Sacré</title>
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
            <!-- Header avec Image d'en-tête -->
            <tr>
                <td class="header">
                    <!-- Image représentative de la sagesse -->
                    <img src="https://communaute-lobola.ankhing.com/assets/images/logo.jpg" alt="[Logo de la Sagesse]">
                    <h1>Éveil & Sagesse</h1>
                </td>
            </tr>

            <!-- Contenu Principal -->
            <tr>
                <td class="content">
                    <div class="status-badge">NOUVEL ENSEIGNEMENT DISPONIBLE</div>
                    
                    <h2>La Voie des Ancêtres</h2>
                    
                    <p>Nous avons la joie de vous annoncer qu'une nouvelle transmission sacrée vient d'être ajoutée à votre parcours spirituel. Cet enseignement est une invitation à renouer avec l'équilibre de la <strong>MAAT</strong>.</p>
                    
                    <div class="instruction-box">
                        <strong>Thématique de la sagesse :</strong>
                        <p style="margin-top: 8px; margin-bottom: 0;"><?= $Enseignement->title ?></p>
                    </div>

                    <div class="button-container">
                        <a href="<?= $lien_enseignement ?>" class="button-primary">Ecouter l'Enseignement</a>
                    </div>

                    <p style="font-size: 14px; margin-top: 20px;">
                        Que la paix et la lumière vous accompagnent dans cette nouvelle étape de votre cheminement.
                    </p>
                </td>
            </tr>

            <!-- Pied de page -->
            <tr>
                <td class="footer">
                    <p>Ce message vous est envoyé pour accompagner votre évolution spirituelle.</p>
                    <p>&copy; <?= date('Y') ?> <?= SITE_NAME ?>. Tous droits réservés.</p>
                </td>
            </tr>
        </table>
    </center>
</body>
</html>