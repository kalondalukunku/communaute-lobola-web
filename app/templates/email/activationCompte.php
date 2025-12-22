<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #0b0f1a; 
            color: #cbd5e1; 
            margin: 0;
            padding: 24px;
            -webkit-font-smoothing: antialiased;
        }

        /* Conteneur Principal */
        /* .card {
            max-width: 512px;
            margin: 0 auto;
            background-color: #131a2b;
            border: 1px solid #1e293b;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(1, 87, 155, 0.2);
        }

        /* Header
        .header {
            padding: 40px 40px 24px 40px;
            text-align: center;
        }

        .logo-box {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 56px;
            height: 56px;
            border-radius: 16px;
            background: linear-gradient(to bottom right, #01579B, #2563eb);
            margin-bottom: 24px;
            box-shadow: 0 10px 15px -3px rgba(1, 87, 155, 0.3);
            vertical-align: middle;
        }

        .logo-box svg {
            width: 32px;
            height: 32px;
            color: white;
            margin-top: 12px; /* Ajustement centrage manuel */
        /* }

        .title {
            font-size: 24px;
            font-weight: 700;
            color: #ffffff;
            margin: 0;
            letter-spacing: -0.025em;
        }

        .subtitle {
            color: #64748b;
            font-size: 14px;
            margin-top: 8px;
            font-weight: 500;
            letter-spacing: 0.025em;
            text-transform: uppercase;
        } */

        /* Corps */
        /* .content {
            padding: 16px 40px;
        }

        .greeting {
            color: #e2e8f0;
            margin-bottom: 24px;
            font-size: 16px;
        }

        .description {
            line-height: 1.625;
            margin-bottom: 24px;
            color: #94a3b8;
            font-size: 15px;
        } */

        /* Bouton CTA */
        /* .cta-container {
            text-align: center;
            margin: 40px 0;
        }

        .btn-primary {
            display: inline-block;
            padding: 16px 40px;
            background-color: #01579B;
            color: #ffffff;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            text-decoration: none;
            letter-spacing: 0.1em;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 0 20px rgba(1, 87, 155, 0.4);
        }

        .btn-primary:hover {
            background-color: #0277bd;
            transform: translateY(-1px);
        } */

        /* Info Box Glassmorphism */
        /* .info-box {
            background-color: rgba(30, 41, 59, 0.4);
            border: 1px solid rgba(51, 65, 85, 0.5);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 32px;
        }

        .info-title {
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 16px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 8px;
            margin-bottom: 8px;
        }

        .info-row.border-b {
            border-bottom: 1px solid rgba(51, 65, 85, 0.5);
        }

        .info-label {
            font-size: 12px;
            color: #94a3b8;
            font-weight: 500;
        }

        .info-value {
            font-size: 14px;
            color: #e2e8f0;
            font-family: monospace;
        }

        .role-badge {
            color: #01579B;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: -0.025em;
        }

        .expiry-text {
            font-size: 11px;
            color: #64748b;
            font-style: italic;
            line-height: 1.625;
            text-align: center;
            padding: 0 16px;
        } */

        /* Footer */
        /* .footer {
            padding: 16px 40px 40px 40px;
        }

        .footer-divider {
            border-top: 1px solid #1e293b;
            padding-top: 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-name {
            color: white;
            font-weight: 700;
            font-size: 14px;
            margin: 0;
        }

        .site-label {
            color: #64748b;
            font-size: 10px;
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 0;
        }

        .support-link {
            color: #01579B;
            font-size: 12px;
            font-weight: 500;
            text-decoration: none;
        }

        .support-link:hover {
            text-decoration: underline;
        }

        .copyright {
            margin-top: 32px;
            text-align: center;
            font-size: 10px;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.2em;
        }  */ 
    </style>
</head>
<body>

    <div class="card">
        <!-- Header -->
        <div class="header">
            <div class="logo-box">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h1 class="title">Activation de Compte</h1>
            <p class="subtitle"><?= SITE_NAME ?></p>
        </div>

        <!-- Corps -->
        <div class="content">
            <p class="greeting">Bonjour,</p>
            <p class="description">
                Vous avez été officiellement invité à rejoindre l'écosystème numérique de gestion du personnel. Votre accès sécurisé a été pré-configuré par les services de Ressources Humaines pour garantir la confidentialité de vos tâches.
            </p>

            <div class="cta-container">
                <a href="<?= $lien_activation ?>" class="btn-primary">
                    Activer mon compte
                </a>
            </div>

            <!-- Box d'infos -->
            <div class="info-box">
                <h3 class="info-title">Accès sécurisé</h3>
                <div class="info-row border-b">
                    <span class="info-label">Email</span>
                    <span class="info-value"><?= $email ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Rôle attribué</span>
                    <span class="role-badge"><?= $role ?></span>
                </div>
            </div>

            <p class="expiry-text">
                Ce lien d'invitation expirera sous 24h. Si vous n'attendiez pas cet email, merci de l'ignorer.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-divider">
                <div>
                    <p class="admin-name">L'Administration</p>
                    <p class="site-label"><?= SITE_NAME ?></p>
                </div>
                <div style="text-align: right;">
                    <a href="mailto:<?= ADMIN_EMAIL ?>" class="support-link">Support technique</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Copyright -->
    <div class="copyright">
        <p>© <?= date('Y') ?> • Système de Gestion Sécurisé</p>
    </div>

</body>
</html>