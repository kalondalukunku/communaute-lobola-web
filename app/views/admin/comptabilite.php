<?php 
    $title = "Admin - Comptabilité";
    include APP_PATH . 'views/layouts/header.php'; 
    include APP_PATH . 'views/layouts/navbar_admin.php';
    include APP_PATH . 'templates/alertView.php'; 

    $paymentsList = $allPayment ?? ($totalPayment_list ?? []);
    $totalPaymentsCount = count($paymentsList);
    
    $depensesList = $allDepense ?? [];
    $totalDepensesCount = count($depensesList);
    
    // Nouvelles variables pour Kpay et Retraits (à alimenter par ton backend)
    $totalKpay = $totalKpay ?? 0; 
    $retraitsList = $allRetraits ?? [];
    $totalRetraitsCount = count($retraitsList);

    $maxretraitAmount = $SoldeKpay[1]['balance'] - ($SoldeKpay[1]['balance'] * 0.04); // Montant maximum pour le retrait
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
            <div class="p-4 md:p-8 max-w-[1400px] w-full mx-auto" 
                x-data="{ 
                    showWithdrawModal: false, 
                    showExpenseModal: false,
                    activeTab: 'transactions' 
                }">

                <!-- En-tête de page -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-extrabold text-white tracking-tight">Tableau de bord financier</h1>
                        <p class="text-sm text-slate-400 mt-1">Gérez vos revenus, dépenses et retraits mobile money.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button id="openModalBtn" class="px-4 py-2 bg-secondary border border-slate-700 hover:border-slate-500 text-white text-sm font-semibold rounded-xl shadow-sm transition-all duration-200 flex items-center gap-2">
                            <i class="fas fa-plus text-amber-500"></i> Nouvelle Dépense
                        </button>
                        <!-- <button id="openModalBtn2" class="px-4 py-2 bg-fuchsia-600 hover:bg-fuchsia-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-fuchsia-900/20 transition-all duration-200 flex items-center gap-2">
                            <i class="fas fa-money-bill-transfer"></i> Faire un retrait
                        </button> -->
                    </div>
                </div>

                <!-- Section Kpay & KPIs Principaux -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">
                    
                    <!-- Carte Portefeuille Kpay -->
                     <div class="lg:col-span-4 bg-gradient-to-br from-[#cfbb30] via-[#2d064c] to-[#130121] rounded-2xl p-6 shadow-xl relative overflow-hidden group">
                        <!-- Icône décorative -->
                        <div class="absolute top-0 right-0 p-8 opacity-10 transform translate-x-4 -translate-y-4 group-hover:scale-110 transition-transform duration-500">
                            <i class="fas fa-wallet text-9xl"></i>
                        </div>
                        
                        <div class="relative z-10">
                            <div class="flex items-center gap-2 mb-4">
                                <span class="h-2 w-2 rounded-full bg-fuchsia-400 animate-pulse"></span>
                                <span class="text-fuchsia-200 text-xs font-bold uppercase tracking-widest">Solde Mobile Kpay</span>
                            </div>
                            
                            <!-- Montant -->
                            <div class="mb-6">
                                <div class="flex justify-between">
                                    <div class="flex items-baseline gap-1 mb-1">
                                        <span class="text-2xl md:text-3xl font-black text-white tracking-tighter">
                                            <?= number_format($SoldeKpay[1]['balance'], 0, ',', ' ') ?>
                                        </span>
                                        <span class="text-sm font-bold text-fuchsia-300">FC</span>
                                    </div>
                                    <div class="flex items-baseline gap-1 mb-1">
                                        <span class="text-2xl md:text-3xl font-black text-white tracking-tighter">
                                            <?= number_format($SoldeKpay[0]['balance'], 0, ',', ' ') ?>
                                        </span>
                                        <span class="text-sm font-bold text-fuchsia-300">XAF</span>
                                    </div>                                    
                                </div>
                                <!-- Équivalent USD -->
                                <!-- <div class="text-sm text-fuchsia-200/70 font-medium">
                                    ≈ <?= number_format($SoldeKpay[1]['balance'] / ARRAT_TAUX_CHANGE['CDF'], 2) ?> USD
                                </div> -->
                            </div>

                            <button id="openModalBtn2" class="w-full bg-white/10 hover:bg-white/20 border border-white/20 backdrop-blur-md text-white px-4 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 flex items-center justify-center gap-2">
                                Retirer vers numéro mobile <i class="fas fa-arrow-right text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Résumé Financier Global (Compacté) -->
                    <div class="lg:col-span-8 bg-paper rounded-2xl color-border p-6 shadow-xl backdrop-blur-sm border border-slate-800/80">
                        <div class="flex items-center gap-2 mb-6">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            <h2 class="text-xs font-bold uppercase tracking-widest text-slate-400">Synthèse Globale</h2>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Revenus -->
                            <div class="bg-secondary/50 rounded-xl p-5 border border-slate-800/50 hover:border-emerald-500/30 transition-colors">
                                <span class="text-xs font-semibold text-slate-400 uppercase block mb-2">Revenus Totaux</span>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-2xl font-extrabold text-white"><?= number_format($totalPayment ?? 0, 2) ?></span>
                                    <span class="text-emerald-500 font-semibold">$</span>
                                </div>
                                <p class="text-xs text-emerald-400/80 mt-2 font-medium">+<?= number_format($totalPaymentMonth ?? 0, 2) ?>$ ce mois</p>
                            </div>

                            <!-- Dépenses -->
                            <div class="bg-secondary/50 rounded-xl p-5 border border-slate-800/50 hover:border-amber-500/30 transition-colors">
                                <span class="text-xs font-semibold text-slate-400 uppercase block mb-2">Dépenses</span>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-2xl font-extrabold text-white"><?= number_format($totalDepense ?? 0, 2) ?></span>
                                    <span class="text-amber-500 font-semibold">$</span>
                                </div>
                                <p class="text-xs text-amber-400/80 mt-2 font-medium"><?= number_format($totalDepenseMonth ?? 0, 2) ?>$ ce mois</p>
                            </div>

                            <!-- Caisse -->
                            <div class="bg-secondary/50 rounded-xl p-5 border border-slate-800/50 hover:border-indigo-500/30 transition-colors relative overflow-hidden">
                                <div class="absolute right-0 bottom-0 w-16 h-16 bg-indigo-500/10 rounded-tl-full"></div>
                                <span class="text-xs font-semibold text-slate-400 uppercase block mb-2">Caisse Réelle</span>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-2xl font-extrabold text-white"><?= number_format(($totalPayment ?? 0) - ($totalDepense ?? 0), 2) ?></span>
                                    <span class="text-indigo-400 font-semibold">$</span>
                                </div>
                                <p class="text-xs text-indigo-400/80 mt-2 font-medium">Bénéfice net actuel</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Tableaux avec Onglets (Tabs) -->
                <div class="bg-paper rounded-2xl color-border shadow-xl border border-slate-800/80 overflow-hidden">
                    
                    <!-- Navigation des onglets -->
                    <div class="flex overflow-x-auto border-b border-slate-800 bg-secondary/30">
                        <button @click="activeTab = 'transactions'" 
                                :class="activeTab === 'transactions' ? 'border-emerald-500 text-white bg-secondary/50' : 'border-transparent text-slate-400 hover:text-slate-300 hover:bg-secondary/20'"
                                class="flex items-center gap-2 py-4 px-6 border-b-2 font-medium text-sm whitespace-nowrap transition-all">
                            <i class="fas fa-arrow-down-to-line text-emerald-500"></i> Paiements Entrants
                            <span class="bg-slate-800 text-xs py-0.5 px-2 rounded-full"><?= $totalPaymentsCount ?></span>
                        </button>
                        <button @click="activeTab = 'depenses'" 
                                :class="activeTab === 'depenses' ? 'border-amber-500 text-white bg-secondary/50' : 'border-transparent text-slate-400 hover:text-slate-300 hover:bg-secondary/20'"
                                class="flex items-center gap-2 py-4 px-6 border-b-2 font-medium text-sm whitespace-nowrap transition-all">
                            <i class="fas fa-arrow-up-right-from-square text-amber-500"></i> Dépenses
                            <span class="bg-slate-800 text-xs py-0.5 px-2 rounded-full"><?= $totalDepensesCount ?></span>
                        </button>
                        <button @click="activeTab = 'retraits'" 
                                :class="activeTab === 'retraits' ? 'border-fuchsia-500 text-white bg-secondary/50' : 'border-transparent text-slate-400 hover:text-slate-300 hover:bg-secondary/20'"
                                class="flex items-center gap-2 py-4 px-6 border-b-2 font-medium text-sm whitespace-nowrap transition-all">
                            <i class="fas fa-money-bill-transfer text-fuchsia-500"></i> Retraits Kpay
                            <span class="bg-slate-800 text-xs py-0.5 px-2 rounded-full"><?= $totalRetraitsCount ?></span>
                        </button>
                    </div>

                    <!-- Contenu des onglets -->
                    <div class="p-6 md:p-8">

                        <!-- TAB 1 : TRANSACTIONS -->
                        <div x-show="activeTab === 'transactions'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                            <div x-data="{ currentPage: 1, itemsPerPage: 1, totalItems: <?= $totalPaymentsCount ?> }">
                                <?php if ($totalPaymentsCount > 0): ?>
                                    <div class="overflow-x-auto -mx-6 md:-mx-8 px-6 md:px-8">
                                        <table class="w-full table-auto border-collapse">
                                            <thead>
                                                <tr class="border-b border-slate-800">
                                                    <th class="pb-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">ID / Nom</th>
                                                    <th class="pb-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Modalité</th>
                                                    <th class="pb-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Montant</th>
                                                    <th class="pb-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">Date</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-800/50">
                                                <?php $index = 0; foreach ($paymentsList as $transaction): ?>
                                                    <tr class="hover:bg-secondary/30 transition-colors" x-show="Math.ceil((<?= $index + 1 ?>) / itemsPerPage) === currentPage">
                                                        <td class="py-4 text-sm font-medium text-white"><?= $transaction->nom_postnom ?? 'N/A' ?></td>
                                                        <td class="py-4">
                                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                                                <?= ucfirst($transaction->modalite_engagement ?? 'N/A') ?>
                                                            </span>
                                                        </td>
                                                        <td class="py-4 text-sm font-bold text-white">+ <?= $transaction->amount ?? 0 ?>$</td>
                                                        <td class="py-4 text-right text-sm text-slate-400"><?= isset($transaction->payment_date) ? date('d/m/Y H:i', strtotime($transaction->payment_date)) : '--/--/----' ?></td>
                                                    </tr>
                                                <?php $index++; endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Pagination component here (Réutilisée de ton code) -->
                                    <div class="flex items-center justify-between pt-5 mt-4 border-t border-slate-800/50">
                                        <span class="text-xs text-slate-400">Affichage de <span x-text="(currentPage-1)*itemsPerPage + 1"></span> à <span x-text="Math.min(currentPage*itemsPerPage, totalItems)"></span> sur <span x-text="totalItems"></span></span>
                                        <div class="flex gap-2">
                                            <button @click="currentPage--" :disabled="currentPage === 1" class="px-3 py-1 bg-secondary border border-slate-700 rounded-lg text-xs disabled:opacity-50 text-white">Précédent</button>
                                            <button @click="currentPage++" :disabled="currentPage === Math.ceil(totalItems/itemsPerPage)" class="px-3 py-1 bg-secondary border border-slate-700 rounded-lg text-xs disabled:opacity-50 text-white">Suivant</button>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="py-12 text-center">
                                        <div class="w-12 h-12 rounded-full bg-secondary flex items-center justify-center mx-auto mb-3 text-slate-500"><i class="fas fa-inbox text-lg"></i></div>
                                        <p class="text-sm text-slate-400">Aucun paiement trouvé.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- TAB 2 : DÉPENSES -->
                        <div x-show="activeTab === 'depenses'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                            <div x-data="{ currentPage: 1, itemsPerPage: 10, totalItems: <?= $totalDepensesCount ?> }">
                                <?php if ($totalDepensesCount > 0): ?>
                                    <div class="overflow-x-auto -mx-6 md:-mx-8 px-6 md:px-8">
                                        <table class="w-full table-auto border-collapse">
                                            <thead>
                                                <tr class="border-b border-slate-800">
                                                    <th class="pb-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Titre de la dépense</th>
                                                    <th class="pb-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Montant</th>
                                                    <th class="pb-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">Date</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-800/50">
                                                <?php $index = 0; foreach ($depensesList as $depense): ?>
                                                    <tr class="hover:bg-secondary/30 transition-colors" x-show="Math.ceil((<?= $index + 1 ?>) / itemsPerPage) === currentPage">
                                                        <td class="py-4 text-sm font-medium text-white"><?= $depense->titre ?? 'N/A' ?></td>
                                                        <td class="py-4 text-sm font-bold text-amber-400">- <?= $depense->montant ?? 0 ?>$</td>
                                                        <td class="py-4 text-right text-sm text-slate-400"><?= isset($depense->date_depense) ? date('d/m/Y H:i', strtotime($depense->date_depense)) : '--/--/----' ?></td>
                                                    </tr>
                                                <?php $index++; endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="flex items-center justify-between pt-5 mt-4 border-t border-slate-800/50">
                                        <span class="text-xs text-slate-400">Affichage de <span x-text="(currentPage-1)*itemsPerPage + 1"></span> à <span x-text="Math.min(currentPage*itemsPerPage, totalItems)"></span> sur <span x-text="totalItems"></span></span>
                                        <div class="flex gap-2">
                                            <button @click="currentPage--" :disabled="currentPage === 1" class="px-3 py-1 bg-secondary border border-slate-700 rounded-lg text-xs disabled:opacity-50 text-white">Précédent</button>
                                            <button @click="currentPage++" :disabled="currentPage === Math.ceil(totalItems/itemsPerPage)" class="px-3 py-1 bg-secondary border border-slate-700 rounded-lg text-xs disabled:opacity-50 text-white">Suivant</button>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="py-12 text-center">
                                        <div class="w-12 h-12 rounded-full bg-secondary flex items-center justify-center mx-auto mb-3 text-slate-500"><i class="fas fa-receipt text-lg"></i></div>
                                        <p class="text-sm text-slate-400">Aucune dépense enregistrée.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- TAB 3 : RETRAITS KPAY -->
                        <div x-show="activeTab === 'retraits'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                            <div x-data="{ currentPage: 1, itemsPerPage: 10, totalItems: <?= $totalRetraitsCount ?> }">
                                <?php if ($totalRetraitsCount > 0): ?>
                                    <div class="overflow-x-auto -mx-6 md:-mx-8 px-6 md:px-8">
                                        <table class="w-full table-auto border-collapse">
                                            <thead>
                                                <tr class="border-b border-slate-800">
                                                    <th class="pb-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">N° Téléphone</th>
                                                    <th class="pb-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Montant</th>
                                                    <th class="pb-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Statut</th>
                                                    <th class="pb-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">Date</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-800/50">
                                                <?php $index = 0; foreach ($retraitsList as $retrait): ?>
                                                    <tr class="hover:bg-secondary/30 transition-colors" x-show="Math.ceil((<?= $index + 1 ?>) / itemsPerPage) === currentPage">
                                                        <td class="py-4 text-sm font-medium text-white flex items-center gap-2">
                                                            <i class="fas fa-mobile-screen text-slate-500"></i> <?= $retrait->numero ?? 'N/A' ?>
                                                        </td>
                                                        <td class="py-4 text-sm font-bold text-fuchsia-400">- <?= $retrait->montant ?? 0 ?>$</td>
                                                        <td class="py-4">
                                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-fuchsia-500/10 text-fuchsia-400 border border-fuchsia-500/20">
                                                                Terminé
                                                            </span>
                                                        </td>
                                                        <td class="py-4 text-right text-sm text-slate-400"><?= isset($retrait->date_retrait) ? date('d/m/Y H:i', strtotime($retrait->date_retrait)) : '--/--/----' ?></td>
                                                    </tr>
                                                <?php $index++; endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="flex items-center justify-between pt-5 mt-4 border-t border-slate-800/50">
                                        <span class="text-xs text-slate-400">Affichage de <span x-text="(currentPage-1)*itemsPerPage + 1"></span> à <span x-text="Math.min(currentPage*itemsPerPage, totalItems)"></span> sur <span x-text="totalItems"></span></span>
                                        <div class="flex gap-2">
                                            <button @click="currentPage--" :disabled="currentPage === 1" class="px-3 py-1 bg-secondary border border-slate-700 rounded-lg text-xs disabled:opacity-50 text-white">Précédent</button>
                                            <button @click="currentPage++" :disabled="currentPage === Math.ceil(totalItems/itemsPerPage)" class="px-3 py-1 bg-secondary border border-slate-700 rounded-lg text-xs disabled:opacity-50 text-white">Suivant</button>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="py-12 text-center">
                                        <div class="w-12 h-12 rounded-full bg-secondary flex items-center justify-center mx-auto mb-3 text-slate-500"><i class="fas fa-money-bill-transfer text-lg"></i></div>
                                        <p class="text-sm text-slate-400">Aucun retrait effectué.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </main>
</section>

<div 
    id="modalOverlay" 
    class="hidden fixed inset-0 bg-transparent z-50 flex items-center justify-center p-4 backdrop-blur-sm flex flex-col">
    
    <!-- Conteneur du Modal -->
    <div 
        id="modalContent"
        class="bg-white rounded-xl shadow-2xl w-full max-w-md transform transition-all">
        
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <h3 class="text-md font-bold text-gray-800">Ajouter une dépense</h3>
            <button id="closeIcon" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form action="" method="post">
            <!-- Corps du Modal -->
            <div class="p-6 pt-0">
                    <div class="group mb-4">
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-400 mb-2 transition-colors group-focus-within:text-primary">Titre de la dépense</label>
                        <input type="text" name="titre" class="w-full border-b border-gray-200 focus:border-primary transition-all outline-none py-2 text-base bg-transparent" placeholder="Ex: Achat de ..." required value="<?= Helper::getData($_POST, 'titre') ?>">
                    </div>
                    <div class="group mb-4">
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-400 mb-2 transition-colors group-focus-within:text-primary">Montant de la dépense</label>
                        <input type="number" name="montant" step="0.01" class="w-full border-b border-gray-200 focus:border-primary transition-all outline-none py-2 text-base bg-transparent" placeholder="Ex: 50" required value="<?= Helper::getData($_POST, 'montant') ?>">
                    </div>
                    <select name="devise" class="w-full border-b border-gray-200 focus:border-primary transition-all outline-none py-2 text-base bg-transparent mb-4" required>
                        <option value="" disabled selected>Choisissez la devise</option>
                        <option value="USD" <?= Helper::getData($_POST, 'devise') === 'USD' ? 'selected' : '' ?>>Dollar ($)</option>
                        <option value="EUR" <?= Helper::getData($_POST, 'devise') === 'EUR' ? 'selected' : '' ?>>Euro (€)</option>
                    </select>
                    <div class="group">
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-400 mb-2 transition-colors group-focus-within:text-primary">Détails de la dépense</label>
                        <textarea name="description" rows="1" class="w-full border-b border-gray-200 focus:border-primary transition-all outline-none py-2 text-base bg-transparent resize-none" placeholder="Décrivez la dépense." required><?= Helper::getData($_POST, 'description') ?></textarea>
                    </div>
                </div>

                <!-- Footer / Boutons d'action -->
                <div class="flex flex-col sm:flex-row-reverse gap-3 p-6 bg-gray-50 rounded-b-xl">
                    <button name="cllil_ajouter_depense" class="bg-primary text-paper px-8 py-3 rounded-xl text-[11px] font-black tracking-widest shadow-xl shadow-primary/20 hover:scale-105 transition">
                        Ajouter la dépense
                    </button>
                    <button 
                        id="closeModalBtn"
                        class="w-full sm:w-auto px-6 py-2.5 bg-paper border border-gray-300 text-[12px] text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition">
                        Annuler
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div 
    id="modalOverlay2" 
    class="hidden fixed inset-0 bg-transparent z-50 flex items-center justify-center p-4 backdrop-blur-sm flex flex-col">
    
    <!-- Conteneur du Modal -->
    <div 
        id="modalContent2"
        class="bg-white rounded-xl shadow-2xl w-full max-w-md transform transition-all">
        
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <h3 class="text-md font-bold text-gray-800">
                <i class="fas fa-mobile-screen"></i> Effectuer un retrait Kpay
            </h3>
            <button id="closeIcon2" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form action="" method="post">
            <!-- Corps du Modal -->
            <div class="p-6 pt-0">
                    <div class="group mb-4">
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-400 mb-2 transition-colors group-focus-within:text-primary">Numéro de téléphone</label>
                        <input type="tel" name="numero" class="w-full border-b border-gray-200 focus:border-primary transition-all outline-none py-2 text-base bg-transparent" placeholder="Ex: 0890000000" required value="<?= Helper::getData($_POST, 'titre') ?>">
                    </div>
                    <div class="relative group mb-4">
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-400 mb-2 transition-colors group-focus-within:text-primary">Montant <span>(Max: <?= number_format($maxretraitAmount, 2) ?>FC)</span></label>
                        <input type="number" step="0.01" name="montant" id="montant" max="<?= $maxretraitAmount ?>" required class="w-full border-b border-gray-200 focus:border-primary transition-all outline-none py-2 text-base bg-transparent" placeholder="Ex: 100000" required value="<?= Helper::getData($_POST, 'montant') ?>">
                        <!-- <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-xs text-slate-500"></span>
                        </div> -->
                    </div>
                </div>

                <!-- Footer / Boutons d'action -->
                <div class="flex flex-col sm:flex-row-reverse gap-3 p-6 bg-gray-50 rounded-b-xl">
                    <button name="cllil_retrait" class="bg-primary text-paper px-8 py-3 rounded-xl text-[11px] font-black tracking-widest shadow-xl shadow-primary/20 hover:scale-105 transition">
                        Effectuer un retrait
                    </button>
                    <button 
                        id="closeModalBtn2"
                        class="w-full sm:w-auto px-6 py-2.5 bg-paper border border-gray-300 text-[12px] text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition">
                        Annuler
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="<?= ASSETS ?>js/modules/modal.js?v=<?= APP_VERSION ?>"></script>
    
<?php include APP_PATH . 'views/layouts/footer.php'; ?>