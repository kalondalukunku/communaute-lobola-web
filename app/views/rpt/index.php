<?php 
    $title = "Tableau de Bord Analytique RH";
    include APP_PATH . 'views/layouts/header.php'; 
?>

<section class="flex flex-col mt-[3.5rem]">
    
    <?php 
        include APP_PATH . 'views/layouts/navbar.php';
    ?>
    
    <!-- Zone de Contenu Principale (Plein Écran) -->
     <div class="flex-1 p-4 sm:p-8">
        
        <!-- Header de la Page -->
        <header class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <h1 class="text-3xl font-extrabold text-[var(--color-secondary)] flex items-center mb-4 sm:mb-0">
                 <i class="fas fa-chart-bar mr-3 text-[var(--color-primary)]"></i> 
                Tableau de Bord Analytique RH
            </h1>
            <div class="flex space-x-3">
                <button class="flex items-center space-x-2 border border-gray-300 text-sm text-gray-700 font-semibold py-2 px-4 rounded-xl hover:bg-gray-100 transition duration-150">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Période: Année 2025</span>
                </button>
                <button class="flex items-center space-x-2 bg-[var(--color-primary)] text-sm text-white font-semibold py-2 px-4 rounded-xl shadow-md hover:bg-orange-800 transition duration-150">
                    <i class="fas fa-file-export"></i>
                    <span>Exporter (PDF)</span>
                </button>
            </div>
        </header>
        
        <!-- Section des Indicateurs Clés (4 KPIs) -->
        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            
            <!-- KPI 1: Conformité Documentaire -->
            <div class="card-container p-6 border-l-4 border-green-500 hover:shadow-lg transition duration-300">
                <div class="flex justify-between items-center">
                    <p class="text-sm font-medium text-gray-500 uppercase">Effectif Total Personnel</p>
                    <i class="fas fa-users text-2xl text-green-500"></i>
                </div>
                <div class="mt-1 flex items-baseline justify-between">
                    <p class="text-4xl font-extrabold text-gray-900"><?= count($allPsn) ?><span class="text-2xl"></span></p>
                </div>
                <div class="text-xs mt-1 text-green-600">
                    <i class="fas fa-arrow-up"></i> +2.1% (cible 95%)
                </div>
            </div>
            
            <!-- KPI 2: Taux de Rotation (Turnover) -->
            <div class="card-container p-6 border-l-4 border-red-500 hover:shadow-lg transition duration-300">
                <div class="flex justify-between items-center">
                    <p class="text-sm font-medium text-gray-500 uppercase">Effectif Total document</p>
                    <i class="fas fa-file text-2xl text-red-500"></i>
                </div>
                <div class="mt-1">
                    <p class="text-4xl font-extrabold text-gray-900"><?= count($allDcs) ?><span class="text-2xl"></span></p>
                </div>
                 <div class="text-xs mt-1 text-red-600">
                    <i class="fas fa-arrow-up"></i> Hausse de 1.1% ce trimestre
                </div>
            </div>
            
            <!-- KPI 3: Taux d'Absentéisme -->
            <div class="card-container p-6 border-l-4 border-blue-500 hover:shadow-lg transition duration-300">
                <div class="flex justify-between items-center">
                    <p class="text-sm font-medium text-gray-500 uppercase">Taux d'Absentéisme</p>
                    <i class="fas fa-user-times text-2xl text-blue-500"></i>
                </div>
                <div class="mt-1">
                    <p class="text-4xl font-extrabold text-gray-900">3.4<span class="text-2xl">%</span></p>
                </div>
                <div class="text-xs mt-1 text-green-600">
                    <i class="fas fa-arrow-down"></i> Baisse de 0.5% (objectif atteint)
                </div>
            </div>

             <!-- KPI 4: Masse Salariale / Coût Total RH -->
            <div class="card-container p-6 border-l-4 border-[var(--color-primary)] hover:shadow-lg transition duration-300">
                <div class="flex justify-between items-center">
                    <p class="text-sm font-medium text-gray-500 uppercase">Coût Salarial Mensuel</p>
                    <i class="fas fa-dollar-sign text-2xl text-[var(--color-primary)]"></i>
                </div>
                <div class="mt-1">
                    <p class="text-4xl font-extrabold text-gray-900"><?= Helper::formatNumberShort($salaireTotal) ?><span class="text-2xl"></span></p>
                </div>
                <div class="text-xs mt-1 text-gray-500">
                    Stable par rapport au mois précédent.
                </div>
            </div>
            
        </section>
        
        <!-- Section des Graphiques Détaillés (Grille 3 colonnes) -->
        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- Carte 1: Taux de Conformité par Service (Barres) -->
            <div class="card-container p-6 lg:col-span-2">
                <h2 class="text-xl font-semibold text-[var(--color-secondary)] mb-2 border-b pb-2">
                    Conformité Documentaire par Service
                </h2>
                <p class="text-sm text-gray-500 mb-4">Pourcentage de dossiers RH complets et à jour.</p>
                
                <div class="chart-bar-container">
                    <!-- Les barres simulent un graphique -->
                    <div class="chart-bar-item">
                        <span class="bar-label text-green-600">98%</span>
                        <div class="chart-bar bg-green-500" style="height: 98%;"></div>
                        <span class="text-xs mt-1 text-gray-600">Finances</span>
                    </div>
                    <div class="chart-bar-item">
                        <span class="bar-label text-green-600">92%</span>
                        <div class="chart-bar bg-green-500" style="height: 92%;"></div>
                        <span class="text-xs mt-1 text-gray-600">Ressources Humaines</span>
                    </div>
                    <div class="chart-bar-item">
                        <span class="bar-label text-[var(--color-primary)]">78%</span>
                        <div class="chart-bar bg-[var(--color-primary)]" style="height: 78%;"></div>
                        <span class="text-xs mt-1 text-gray-600">Opérations</span>
                    </div>
                    <div class="chart-bar-item">
                        <span class="bar-label text-red-600">65%</span>
                        <div class="chart-bar bg-red-500" style="height: 65%;"></div>
                        <span class="text-xs mt-1 text-gray-600">Technologie (IT)</span>
                    </div>
                    <div class="chart-bar-item">
                        <span class="bar-label text-green-600">89%</span>
                        <div class="chart-bar bg-green-500" style="height: 89%;"></div>
                        <span class="text-xs mt-1 text-gray-600">Logistique</span>
                    </div>
                </div>
                 <p class="mt-4 text-sm text-gray-600">
                    <span class="font-semibold text-red-600">Action Requis :</span> Le service IT nécessite une campagne de mise à jour des certifications et formations.
                </p>
            </div>
            
            <!-- Carte 2: Taux d'Absentéisme (Pondération) -->
            <div class="card-container p-6">
                 <h2 class="text-xl font-semibold text-[var(--color-secondary)] mb-2 border-b pb-2">
                    Répartition de l'Absentéisme
                </h2>
                <p class="text-sm text-gray-500 mb-4">Répartition des causes pour les 6 derniers mois.</p>
                
                <ul class="space-y-3 pt-2">
                    <li class="flex justify-between items-center text-gray-700">
                        <span class="flex items-center"><i class="fas fa-briefcase-medical text-red-400 mr-3"></i> Arrêts Maladie (Longs/Courts)</span>
                        <span class="font-semibold text-lg">45%</span>
                    </li>
                     <li class="flex justify-between items-center text-gray-700">
                        <span class="flex items-center"><i class="fas fa-baby text-blue-400 mr-3"></i> Congés Maternité/Parental</span>
                        <span class="font-semibold text-lg">30%</span>
                    </li>
                     <li class="flex justify-between items-center text-gray-700">
                        <span class="flex items-center"><i class="fas fa-ambulance text-yellow-500 mr-3"></i> Accidents du Travail</span>
                        <span class="font-semibold text-lg">15%</span>
                    </li>
                    <li class="flex justify-between items-center text-gray-700">
                        <span class="flex items-center"><i class="fas fa-user-slash text-gray-400 mr-3"></i> Absentéisme Non-Justifié</span>
                        <span class="font-semibold text-lg">10%</span>
                    </li>
                </ul>
                
                <!-- Barre de progression simulée pour la visualisation rapide -->
                <div class="mt-6">
                    <p class="text-sm font-medium text-gray-700 mb-1">Impact des Arrêts Maladie sur le total</p>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="h-2.5 rounded-full bg-red-400" style="width: 45%"></div>
                    </div>
                </div>
            </div>
            
            <!-- Carte 3: Tableau des Documents Expirants (Action Prioritaire) -->
            <div class="card-container p-6 lg:col-span-3">
                <h2 class="text-xl font-semibold text-red-600 mb-2 border-b pb-2">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Documents RH à Expiration Imminente (30 Jours)
                </h2>
                <p class="text-sm text-gray-500 mb-4">Liste des documents nécessitant une action immédiate pour maintenir la conformité.</p>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employé</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type de Document</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date d'Expiration</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jours Restants</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 text-sm">
                            <!-- Ligne 1: Urgence Rouge -->
                            <tr class="hover:bg-red-50/50">
                                <td class="px-4 py-4 whitespace-nowrap font-medium text-gray-900">M. Léger, A.</td>
                                <td class="px-4 py-4 whitespace-nowrap text-red-600 font-medium">Certificat Médical Annuel</td>
                                <td class="px-4 py-4 whitespace-nowrap text-red-600">2025-12-15</td>
                                <td class="px-4 py-4 whitespace-nowrap"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Moins de 10</span></td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <button class="text-[var(--color-primary)] hover:underline text-xs font-medium">Notifier</button>
                                </td>
                            </tr>
                            <!-- Ligne 2: Urgence Jaune -->
                            <tr class="hover:bg-yellow-50/50">
                                <td class="px-4 py-4 whitespace-nowrap font-medium text-gray-900">Mme Dubois, C.</td>
                                <td class="px-4 py-4 whitespace-nowrap">Habilitation Sécurité (Niv. 2)</td>
                                <td class="px-4 py-4 whitespace-nowrap text-yellow-700">2026-01-05</td>
                                <td class="px-4 py-4 whitespace-nowrap"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">27 Jours</span></td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <button class="text-[var(--color-primary)] hover:underline text-xs font-medium">Planifier Formation</button>
                                </td>
                            </tr>
                             <!-- Ligne 3: Urgence Basse -->
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4 whitespace-nowrap font-medium text-gray-900">M. Gérard, P.</td>
                                <td class="px-4 py-4 whitespace-nowrap">Permis de Conduire Pro (Validité)</td>
                                <td class="px-4 py-4 whitespace-nowrap">2026-01-20</td>
                                <td class="px-4 py-4 whitespace-nowrap"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">42 Jours</span></td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <button class="text-[var(--color-primary)] hover:underline text-xs font-medium">Mettre à Jour</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 text-right">
                    <a href="gestion_documents.html?filter=expiring" class="text-sm text-[var(--color-secondary)] hover:text-[var(--color-primary)] font-medium underline">
                        Afficher les 50 expirations suivantes <i class="fas fa-external-link-alt ml-1"></i>
                    </a>
                </div>
            </div>
            
        </section>
        
        <!-- Footer -->
        <footer class="mt-12 text-center text-xs text-gray-500 border-t border-gray-200 pt-4">
            &copy; 2025 Organisation Publique. Données au 09-12-2025.
        </footer>
    </div>
    
</section>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>