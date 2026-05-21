<?php 
    $title = "Admin - Comptabilité";
    include APP_PATH . 'views/layouts/header.php'; 
    include APP_PATH . 'views/layouts/navbar_admin.php';
    include APP_PATH . 'templates/alertView.php'; 
?>

        <main class="flex-grow flex flex-col min-w-0 bg-paper min-h-screen">
            <!-- Header Mobile Dédié -->
            <div class="lg:hidden p-4 bg-paper border-b border-slate-100 flex items-center justify-between sticky top-0 z-30 shadow-sm backdrop-blur-md bg-opacity-95">
                <button @click="sidebarOpen = true" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-600 hover:bg-slate-100 transition active:scale-95">
                    <i class="fas fa-bars-staggered text-sm"></i>
                </button>
                <div class="flex items-center gap-2.5">
                    <img class="w-8 h-8 rounded-lg shadow-sm object-cover" src="<?= ASSETS ?>images/logo.jpg" alt="Logo">
                    <span class="font-bold text-sm text-slate-800 tracking-tight"><?= SITE_NAME ?></span>
                </div>
                
                <div class="flex items-center">
                    <button class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition relative active:scale-95">
                        <i class="far fa-bell text-base"></i>
                        <span class="absolute top-2.5 right-2.5 w-2 h-2 bg-primary rounded-full ring-2 ring-white"></span>
                    </button>
                </div>
            </div>

            <!-- Header Desktop -->
            <header class="h-20 bg-paper backdrop-blur-md border-b border-slate-100 px-8 flex justify-between items-center sticky top-0 z-40">
                <div class="flex flex-col">
                    <h1 class="font-serif text-xl md:text-2xl font-bold text-slate-900 tracking-tight">Comptabilité</h1>
                    <p class="text-xs text-slate-400 mt-0.5 font-medium italic">Gestion des finances et des transactions</p>
                </div>
                
                <!-- <div class="flex items-center gap-4">
                    <button class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition relative active:scale-95">
                        <i class="far fa-bell text-base"></i>
                        <span class="absolute top-2.5 right-2.5 w-2 h-2 bg-primary rounded-full ring-2 ring-white"></span>
                    </button>
                </div> -->
            </header>

            <!-- Contenu Principal -->
            <div class="p-4 md:p-8 max-w-7xl w-full mx-auto space-y-8">
                
                <!-- Section Résumé Financier -->
                <div class="bg-paper rounded-2xl color-border p-6 md:p-8 shadow-sm">
                    <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-400 mb-6">Résumé Financier</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Revenus Totaux -->
                        <div class="bg-paper border-l-4 border-white border-y border-r border-slate-100 rounded-xl p-5 shadow-xs flex flex-col justify-between hover:shadow-sm transition duration-200">
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Revenus Totaux</span>
                            <div class="mt-3 flex items-baseline gap-1">
                                <span class="text-2xl font-bold text-white"><?= $totalPayment ?></span>
                                <span class="text-sm font-medium text-white">$</span>
                            </div>
                        </div>

                        <!-- Revenus du mois -->
                        <div class="bg-paper border-l-4 border-[#cfbb30] border-y border-r rounded-xl p-5 shadow-xs flex flex-col justify-between hover:shadow-sm transition duration-200">
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Revenus de ce mois</span>
                            <div class="mt-3 flex items-baseline gap-1">
                                <span class="text-2xl font-bold text-white"><?= $totalPaymentMonth ?></span>
                                <span class="text-sm font-medium text-[#cfbb30]">$</span>
                            </div>
                        </div>

                        <!-- Revenus de l'année -->
                        <div class="bg-paper border-l-4 border-violet-500 border-y border-r border-slate-100 rounded-xl p-5 shadow-xs flex flex-col justify-between hover:shadow-sm transition duration-200">
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Revenus de cette année</span>
                            <div class="mt-3 flex items-baseline gap-1">
                                <span class="text-2xl font-bold text-white"><?= $totalPaymentYear ?></span>
                                <span class="text-sm font-medium text-violet-500">$</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Transactions Récentes -->
                <?php 
                    // Sécurité : Détection de la variable contenant les paiements ($allPayment ou $totalPayment)
                    $paymentsList = isset($allPayment) ? $allPayment : (isset($totalPayment) ? $totalPayment : []);
                    $totalPaymentsCount = count($paymentsList);
                ?>

                <div class="bg-paper rounded-2xl color-border p-6 md:p-8 shadow-sm" 
                    x-data="{ currentPage: 1, itemsPerPage: 10, totalItems: <?= $totalPaymentsCount ?> }">
                    
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-400">Transactions Récentes</h2>
                        <span class="text-xs px-2.5 py-1 bg-primary text-paper rounded-full font-bold">
                            <?= $totalPaymentsCount ?> paiement(s)
                        </span>
                    </div>

                    <?php if ($totalPaymentsCount > 0): ?>
                        <div class="overflow-x-auto -mx-6 md:-mx-8">
                            <div class="inline-block min-w-full align-middle px-6 md:px-8">
                                <table class="w-full table-auto border-collapse">
                                    <thead>
                                        <tr class="color-border-b">
                                            <th class="pb-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">ID / Nom</th>
                                            <th class="pb-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Modalité</th>
                                            <th class="pb-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Montant</th>
                                            <th class="pb-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-50">
                                        <?php $index = 0; foreach ($paymentsList as $transaction): ?>
                                            <tr class="group hover:bg-secondary transition-colors color-border-b" 
                                                x-show="Math.ceil((<?= $index + 1 ?>) / itemsPerPage) === currentPage"
                                                x-transition.opacity>
                                                <td class="py-4 text-sm font-medium text-white">
                                                    <?= $transaction->nom_postnom ?>
                                                </td>
                                                <td class="py-4">
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-primary text-paper color-border group-hover:bg-paper transition-colors">
                                                        <?= ucfirst($transaction->modalite_engagement) ?>
                                                    </span>
                                                </td>
                                                <td class="py-4 text-sm font-semibold text-white">
                                                    <?= $transaction->amount ?>$
                                                </td>
                                                <td class="py-4 text-right text-sm text-slate-400 font-medium">
                                                    <?= date('d/m/Y H:i', strtotime($transaction->payment_date)) ?>
                                                </td>
                                            </tr>
                                        <?php $index++; endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Section Pagination Moderne et Fluide (Toujours visible si au moins 1 transaction) -->
                        <div x-show="totalItems > 0" class="flex items-center justify-between border-t color-border-b pt-5 mt-4">
                            <!-- Version Mobile Simple -->
                            <div class="flex-1 flex justify-between sm:hidden">
                                <button @click="if(currentPage > 1) currentPage--" 
                                        :disabled="currentPage === 1" 
                                        class="relative inline-flex items-center px-4 py-2 border border-slate-700/50 text-xs font-medium rounded-xl text-slate-300 bg-secondary/20 hover:bg-secondary/40 disabled:opacity-30 transition">
                                    Précédent
                                </button>
                                <button @click="if(currentPage < Math.ceil(totalItems/itemsPerPage)) currentPage++" 
                                        :disabled="currentPage === Math.ceil(totalItems/itemsPerPage) || Math.ceil(totalItems/itemsPerPage) === 0" 
                                        class="ml-3 relative inline-flex items-center px-4 py-2 border border-slate-700/50 text-xs font-medium rounded-xl text-slate-300 bg-secondary/20 hover:bg-secondary/40 disabled:opacity-30 transition">
                                    Suivant
                                </button>
                            </div>

                            <!-- Version Desktop Détaillée -->
                            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-xs text-slate-400">
                                        Affichage de <span class="font-semibold text-white" x-text="totalItems === 0 ? 0 : ((currentPage - 1) * itemsPerPage) + 1"></span> à <span class="font-semibold text-white" x-text="Math.min(currentPage * itemsPerPage, totalItems)"></span> sur <span class="font-semibold text-white" x-text="totalItems"></span> paiements
                                    </p>
                                </div>
                                <div x-show="Math.ceil(totalItems / itemsPerPage) > 1">
                                    <nav class="relative z-0 inline-flex rounded-xl shadow-xs -space-x-px gap-1.5" aria-label="Pagination">
                                        <button @click="currentPage = 1" 
                                                :disabled="currentPage === 1" 
                                                class="relative inline-flex items-center p-2 rounded-xl border border-slate-700/50 text-slate-400 bg-secondary/10 hover:bg-secondary/30 disabled:opacity-30 transition">
                                            <i class="fas fa-angles-left text-[10px]"></i>
                                        </button>
                                        <button @click="if(currentPage > 1) currentPage--" 
                                                :disabled="currentPage === 1" 
                                                class="relative inline-flex items-center p-2 rounded-xl border border-slate-700/50 text-slate-400 bg-secondary/10 hover:bg-secondary/30 disabled:opacity-30 transition">
                                            <i class="fas fa-chevron-left text-[10px]"></i>
                                        </button>
                                        
                                        <template x-for="page in Math.ceil(totalItems / itemsPerPage)" :key="page">
                                            <button @click="currentPage = page" 
                                                    :class="currentPage === page ? 'bg-primary text-paper border-primary font-bold shadow-sm' : 'border-slate-800 text-slate-400 bg-secondary/10 hover:bg-secondary/30'" 
                                                    class="relative inline-flex items-center px-3 py-1.5 border rounded-xl text-xs font-medium transition"
                                                    x-text="page">
                                            </button>
                                        </template>

                                        <button @click="if(currentPage < Math.ceil(totalItems/itemsPerPage)) currentPage++" 
                                                :disabled="currentPage === Math.ceil(totalItems/itemsPerPage)" 
                                                class="relative inline-flex items-center p-2 rounded-xl border border-slate-700/50 text-slate-400 bg-secondary/10 hover:bg-secondary/30 disabled:opacity-30 transition">
                                            <i class="fas fa-chevron-right text-[10px]"></i>
                                        </button>
                                        <button @click="currentPage = Math.ceil(totalItems/itemsPerPage)" 
                                                :disabled="currentPage === Math.ceil(totalItems/itemsPerPage)" 
                                                class="relative inline-flex items-center p-2 rounded-xl border border-slate-700/50 text-slate-400 bg-secondary/10 hover:bg-secondary/30 disabled:opacity-30 transition">
                                            <i class="fas fa-angles-right text-[10px]"></i>
                                        </button>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="flex flex-col items-center justify-center py-12 text-center">
                            <div class="w-12 h-12 rounded-full bg-primary flex items-center justify-center text-paper mb-3">
                                <i class="far fa-folder-open text-lg"></i>
                            </div>
                            <p class="text-sm font-medium text-slate-500">Aucune transaction récente à afficher.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
</section>
    
<?php include APP_PATH . 'views/layouts/footer.php'; ?>