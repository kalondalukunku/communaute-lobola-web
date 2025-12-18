<?php if (!empty($_SESSION['flash'])): ?>
        <?php foreach ($_SESSION['flash'] as $type => $message): ?>
            <?php 
                $icon = '';
                $title = '';
                
                // Détermine l'icône et le titre en fonction de la clé ($type)
                switch ($type) {
                    case 'success':
                        $icon = 'fas fa-check-circle';
                        $title = 'Succès';
                        break;
                    case 'error':
                        $icon = 'fas fa-times-circle';
                        $title = 'Erreur';
                        break;
                    case 'warning':
                        $icon = 'fas fa-exclamation-triangle';
                        $title = 'Attention';
                        break;
                    default:
                        // Pour les types non reconnus, utilise l'information par défaut
                        $icon = 'fas fa-info-circle';
                        $title = 'Information';
                        break;
                }
            ?>
            <!-- Génère le message avec le type et la classe 'show' -->
            <div class="flash-message max-w-lg <?= htmlspecialchars($type); ?> mx-auto show mt-8">
                <i class="<?= htmlspecialchars($icon); ?> text-xl flex-shrink-0"></i>
                <div class="flex-grow">
                    <p class="flash-title"><?= htmlspecialchars($title); ?></p>
                    <!-- Utilise la variable $message de la boucle foreach -->
                    <p class="flash-text"><?= htmlspecialchars($message); ?></p>
                </div>
            </div>
            <!-- Supprime le message après l'affichage -->
            <?php unset($_SESSION['flash'][$type]); ?>
        <?php endforeach; ?>
    <?php endif; ?>