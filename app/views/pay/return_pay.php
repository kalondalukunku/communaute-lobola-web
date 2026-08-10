<?php
    include APP_PATH . 'views/layouts/header.php'; 
    // include APP_PATH . 'templates/alertView.php'; 

    // $status = strtoupper(trim((string) ($status)));
    $isSuccess = $status === 'COMPLETED';
    $badgeClass = $isSuccess ? 'bg-emerald-600' : 'bg-rose-600';
    $panelClass = $isSuccess ? 'border-emerald-200 bg-emerald-50' : 'border-rose-200 bg-rose-50';
    $title = $isSuccess ? 'Abonnement confirmé' : 'Paiement échoué';
    $message = $isSuccess
        ? 'Votre abonnement a été validé avec succès. Merci pour votre paiement.'
        : $transPayment['failureReason'] ?? 'Le paiement a échoué. Veuillez réessayer.';
    $buttonLabel = $isSuccess ? 'Accéder aux enseignements de BOLOKELE' : 'Réessayer';
    $buttonHref = defined('BASE_URL') ? BASE_URL . '/bolokele' : '/';
?>

<section class="min-h-screen bg-paper flex items-center justify-center px-4 py-10 animate-fade-in-up">
    <div class="w-full max-w-xl rounded-3xl border shadow-2xl shadow-slate-200/50 <?= $panelClass; ?> p-8 sm:p-10 text-center relative overflow-hidden">
        
        <!-- Effet décoratif subtil -->
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-current/20 to-transparent"></div>

        <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full <?= $badgeClass; ?> text-white shadow-lg animate-pulse">
            <?php if ($isSuccess): ?>
                <svg class="h-10 w-10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                </svg>
            <?php else: ?>
                <svg class="h-10 w-10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            <?php endif; ?>
        </div>

        <h1 class="text-3xl font-bold text-slate-900 tracking-tight mb-4">
            <?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>
        </h1>
        
        <p class="text-slate-600 leading-relaxed mb-8 max-w-md mx-auto">
            <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
        </p>

        <a href="<?= htmlspecialchars($buttonHref, ENT_QUOTES, 'UTF-8'); ?>" 
           class="group bg-primary hover:bg-opacity-90 text-paper font-bold py-4 px-8 rounded-2xl shadow-xl shadow-primary/20 transition-all active:scale-[0.98] hover-lift flex items-center justify-center w-full sm:w-auto mx-auto gap-2">
            <?= htmlspecialchars($buttonLabel, ENT_QUOTES, 'UTF-8'); ?>
            <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
            </svg>
        </a>
    </div>
</section>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>