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

                <div class="bg-paper rounded-2xl color-border p-6 md:p-8 shadow-xl backdrop-blur-sm">
    
                    <div class="flex items-center gap-2 mb-6">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <h2 class="text-xs font-bold uppercase tracking-widest text-slate-400">Résumé Financier Global</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                        <div class="group relative bg-secondary border border-slate-800/80 hover:border-emerald-500/30 rounded-2xl p-6 shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-emerald-950/20">
                            <div class="absolute top-0 left-6 right-6 h-[2px] bg-gradient-to-r from-transparent via-emerald-500/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-3">Revenus Totaux</span>
                            <div class="flex items-baseline gap-1">
                                <span class="text-2xl md:text-3xl font-extrabold text-white tracking-tight group-hover:text-emerald-400 transition-colors duration-300"><?= $totalPayment ?></span>
                                <span class="text-sm font-semibold text-emerald-500">$</span>
                            </div>
                        </div>

                        <div class="group relative bg-secondary border border-slate-800/80 hover:border-amber-500/30 rounded-2xl p-6 shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-amber-950/20">
                            <div class="absolute top-0 left-6 right-6 h-[2px] bg-gradient-to-r from-transparent via-amber-500/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-3">Dépenses</span>
                            <div class="flex items-baseline gap-1">
                                <span class="text-2xl md:text-3xl font-extrabold text-white tracking-tight group-hover:text-amber-400 transition-colors duration-300"><?= $totalDepense ?></span>
                                <span class="text-sm font-semibold text-amber-500">$</span>
                            </div>
                        </div>

                        <div class="group relative bg-secondary border border-slate-800/80 hover:border-indigo-500/30 rounded-2xl p-6 shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-indigo-950/20">
                            <div class="absolute top-0 left-6 right-6 h-[2px] bg-gradient-to-r from-transparent via-indigo-500/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-3">Caisse réelle</span>
                            <div class="flex items-baseline gap-1">
                                <span class="text-2xl md:text-3xl font-extrabold text-white tracking-tight group-hover:text-indigo-400 transition-colors duration-300"><?= $totalPayment - $totalDepense ?></span>
                                <span class="text-sm font-semibold text-indigo-400">$</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2 my-6 pt-4 border-t border-slate-800/40">
                        <span class="h-1.5 w-1.5 rounded-full bg-indigo-400"></span>
                        <h2 class="text-xs font-bold uppercase tracking-widest text-slate-400">Résumé Financier Mensuel</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                        <div class="group relative bg-secondary border border-slate-800/80 hover:border-emerald-500/30 rounded-2xl p-6 shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-emerald-950/20">
                            <div class="absolute top-0 left-6 right-6 h-[2px] bg-gradient-to-r from-transparent via-emerald-500/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-3">Revenus Totaux du mois</span>
                            <div class="flex items-baseline gap-1">
                                <span class="text-2xl md:text-3xl font-extrabold text-white tracking-tight group-hover:text-emerald-400 transition-colors duration-300"><?= $totalPaymentMonth ?></span>
                                <span class="text-sm font-semibold text-emerald-500">$</span>
                            </div>
                        </div>

                        <div class="group relative bg-secondary border border-slate-800/80 hover:border-amber-500/30 rounded-2xl p-6 shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-amber-950/20">
                            <div class="absolute top-0 left-6 right-6 h-[2px] bg-gradient-to-r from-transparent via-amber-500/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-3">Dépenses du mois</span>
                            <div class="flex items-baseline gap-1">
                                <span class="text-2xl md:text-3xl font-extrabold text-white tracking-tight group-hover:text-amber-400 transition-colors duration-300"><?= $totalDepenseMonth ?></span>
                                <span class="text-sm font-semibold text-amber-500">$</span>
                            </div>
                        </div>

                        <div class="group relative bg-secondary border border-slate-800/80 hover:border-indigo-500/30 rounded-2xl p-6 shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-indigo-950/20">
                            <div class="absolute top-0 left-6 right-6 h-[2px] bg-gradient-to-r from-transparent via-indigo-500/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-3">Caisse réelle du mois</span>
                            <div class="flex items-baseline gap-1">
                                <span class="text-2xl md:text-3xl font-extrabold text-white tracking-tight group-hover:text-indigo-400 transition-colors duration-300"><?= $totalPaymentMonth - $totalDepenseMonth ?></span>
                                <span class="text-sm font-semibold text-indigo-400">$</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 my-6 pt-4 border-t border-slate-800/40">
                        <span class="h-1.5 w-1.5 rounded-full bg-violet-400"></span>
                        <h2 class="text-xs font-bold uppercase tracking-widest text-slate-400">Résumé Financier Annuel</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="group relative bg-secondary border border-slate-800/80 hover:border-emerald-500/30 rounded-2xl p-6 shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-emerald-950/20">
                            <div class="absolute top-0 left-6 right-6 h-[2px] bg-gradient-to-r from-transparent via-emerald-500/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-3">Revenus Totaux Annuel</span>
                            <div class="flex items-baseline gap-1">
                                <span class="text-2xl md:text-3xl font-extrabold text-white tracking-tight group-hover:text-emerald-400 transition-colors duration-300"><?= $totalPaymentYear ?></span>
                                <span class="text-sm font-semibold text-emerald-500">$</span>
                            </div>
                        </div>

                        <div class="group relative bg-secondary border border-slate-800/80 hover:border-amber-500/30 rounded-2xl p-6 shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-amber-950/20">
                            <div class="absolute top-0 left-6 right-6 h-[2px] bg-gradient-to-r from-transparent via-amber-500/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-3">Dépenses de cette année</span>
                            <div class="flex items-baseline gap-1">
                                <span class="text-2xl md:text-3xl font-extrabold text-white tracking-tight group-hover:text-amber-400 transition-colors duration-300"><?= $totalDepenseYear ?></span>
                                <span class="text-sm font-semibold text-amber-500">$</span>
                            </div>
                        </div>

                        <div class="group relative bg-secondary border border-slate-800/80 hover:border-indigo-500/30 rounded-2xl p-6 shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-indigo-950/20">
                            <div class="absolute top-0 left-6 right-6 h-[2px] bg-gradient-to-r from-transparent via-indigo-500/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-3">Caisse réelle de cette année</span>
                            <div class="flex items-baseline gap-1">
                                <span class="text-2xl md:text-3xl font-extrabold text-white tracking-tight group-hover:text-indigo-400 transition-colors duration-300"><?= $totalPaymentYear - $totalDepenseYear ?></span>
                                <span class="text-sm font-semibold text-indigo-400">$</span>
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
                        <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-400">Transactions</h2>
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
                                                    <?= date('d/m/Y à H:i', strtotime($transaction->payment_date)) ?>
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

                <div class="bg-paper rounded-2xl color-border p-6 md:p-8 shadow-sm" 
                    x-data="{ currentPage: 1, itemsPerPage: 10, totalItems: <?= count($allDepense) ?> }">
                    
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-400">Dépenses</h2>
                        <div class="gap-3 flex items-center">
                            <button id="openModalBtn" class="px-3 py-1 text-xs font-medium rounded-full bg-secondary text-slate-400 hover:bg-secondary/50 transition">
                                <i class="fas fa-plus text-[10px]"></i> Ajouter une dépense
                            </button>
                            <span class="text-xs px-2.5 py-1 bg-primary text-paper rounded-full font-bold">
                                <?= count($allDepense) ?> dépense(s)
                            </span>
                        </div>
                    </div>

                    <?php if (count($allDepense) > 0): ?>
                        <div class="overflow-x-auto -mx-6 md:-mx-8">
                            <div class="inline-block min-w-full align-middle px-6 md:px-8">
                                <table class="w-full table-auto border-collapse">
                                    <thead>
                                        <tr class="color-border-b">
                                            <th class="pb-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Titre</th>
                                            <th class="pb-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Montant</th>
                                            <!-- <th class="pb-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Devise du paiement</th> -->
                                            <th class="pb-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-50">
                                        <?php $index = 0; foreach ($allDepense as $depense): ?>
                                            <tr class="group hover:bg-secondary transition-colors color-border-b" 
                                                x-show="Math.ceil((<?= $index + 1 ?>) / itemsPerPage) === currentPage"
                                                x-transition.opacity>
                                                <td class="py-4 text-sm font-medium text-white">
                                                    <?= $depense->titre ?>
                                                </td>
                                                <td class="py-4 text-sm font-semibold text-white">
                                                    <?= $depense->montant ?>$
                                                </td>
                                                <!-- <td class="py-4">
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-primary text-paper color-border group-hover:bg-paper transition-colors">
                                                        <?= $depense->devise ?>
                                                    </span>
                                                </td> -->
                                                <td class="py-4 text-right text-sm text-slate-400 font-medium">
                                                    <?= date('d/m/Y à H:i', strtotime($depense->date_depense)) ?>
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
                                <i class="far fa-file-alt text-lg"></i>
                            </div>
                            <p class="text-sm font-medium text-slate-500">Aucun dépense à afficher.</p>
                        </div>
                    <?php endif; ?>
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

<script src="<?= ASSETS ?>js/modules/modal.js?v=<?= APP_VERSION ?>"></script>
    
<?php include APP_PATH . 'views/layouts/footer.php'; ?>