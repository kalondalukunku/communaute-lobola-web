<div id="notification-container" class="fixed top-6 right-6 z-[9999] flex flex-col gap-4 w-full max-w-sm">
    <?php if (!empty($_SESSION['flash'])): ?>
        <?php foreach ($_SESSION['flash'] as $type => $message): ?>
            <?php 
                $icon = '';
                $title = '';
                $bgClass = '';
                $textClass = '';
                $borderClass = '';
                $iconBgClass = '';
                $progress = false;

                // Détermine le style en fonction de la clé ($type)
                switch ($type) {
                    case 'success':
                        $icon = 'fas fa-check';
                        $title = 'Succès';
                        $bgClass = 'bg-[#16302B]';
                        $textClass = 'text-white';
                        $borderClass = 'border-[#CFBB30]';
                        $iconBgClass = 'bg-[#CFBB30] text-[#16302B]';
                        $progress = true; // Barre de progression pour le succès
                        break;
                    case 'error':
                        $icon = 'fas fa-exclamation-triangle';
                        $title = 'Erreur';
                        $bgClass = 'bg-white';
                        $textClass = 'text-[#16302B]';
                        $borderClass = 'border-red-500';
                        $iconBgClass = 'bg-red-100 text-red-600';
                        break;
                    case 'warning':
                        $icon = 'fas fa-exclamation-circle';
                        $title = 'Attention';
                        $bgClass = 'bg-[#CFBB30]';
                        $textClass = 'text-[#16302B]';
                        $borderClass = 'border-[#16302B]';
                        $iconBgClass = 'bg-[#16302B] text-[#CFBB30]';
                        break;
                    default:
                        $icon = 'fas fa-info-circle';
                        $title = 'Information';
                        $bgClass = 'bg-gray-100';
                        $textClass = 'text-gray-800';
                        $borderClass = 'border-gray-400';
                        $iconBgClass = 'bg-gray-200 text-gray-600';
                        break;
                }
            ?>
            
            <!-- Bloc de notification dynamique -->
            <div class="notification-slide relative overflow-hidden <?= $bgClass; ?> <?= $textClass; ?> p-5 rounded-2xl shadow-2xl border-l-4 <?= $borderClass; ?> <?= $bgClass === 'bg-white' ? 'border' : ''; ?> border-gray-100">
                <div class="flex items-start gap-4">
                    <div class="<?= $iconBgClass; ?> w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="<?= htmlspecialchars($icon); ?> text-sm"></i>
                    </div>
                    <div class="flex-grow">
                        <h4 class="<?= $type === 'success' || $type === 'warning' ? 'font-serif text-lg' : 'font-bold'; ?> leading-none mb-1">
                            <?= htmlspecialchars($title); ?>
                        </h4>
                        <p class="text-sm <?= $bgClass === 'bg-[#16302B]' ? 'text-white/80' : 'text-gray-500'; ?>">
                            <?= htmlspecialchars($message); ?>
                        </p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="<?= $bgClass === 'bg-[#16302B]' ? 'text-white/40 hover:text-white' : 'text-gray-300 hover:text-gray-500'; ?> transition">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <?php if ($progress): ?>
                    <div class="progress-line"></div>
                <?php endif; ?>
            </div>

            <?php unset($_SESSION['flash'][$type]); ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>