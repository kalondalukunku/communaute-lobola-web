<?php 
  include APP_PATH . 'views/layouts/header.php'; 
?>

<section class="flex flex-col mt-[3.5rem]">
    
    <?php 
        include APP_PATH . 'views/layouts/navbar.php';
    ?>
    
    <!-- Zone de Contenu Principale (Plein Écran) -->
    <div class="flex-1 p-4 sm:p-8 max-w-7xl mx-auto w-full">
        
        <!-- Header de la Page -->
        <header class="mb-6">
            <h1 class="text-3xl font-extrabold text-[var(--color-secondary)] flex items-center">
                 <i class="fas fa-cogs mr-3 text-[var(--color-primary)]"></i> 
                Configuration de l'Intranet RH
            </h1>
            <p class="text-gray-500 mt-1">Gérez les accès, la conformité documentaire et les alertes du système.</p>
        </header>
        
        <!-- Conteneur des Paramètres -->
        <div class="card-container p-6">
            
            <!-- Menu des Onglets -->
            <div class="flex border-b border-gray-200 mb-6 space-x-6 overflow-x-auto">
                <button class="js-tab-button active pb-2 border-b-2 border-transparent text-gray-600 hover:text-[var(--color-secondary)] transition duration-150" 
                        data-tab="roles">
                    <i class="fas fa-users-cog mr-2"></i> Rôles & Permissions
                </button>
                <button class="js-tab-button pb-2 border-b-2 border-transparent text-gray-600 hover:text-[var(--color-secondary)] transition duration-150" 
                        data-tab="documents">
                    <i class="fas fa-clipboard-list mr-2"></i> Types de Documents & Validité
                </button>
                <button class="js-tab-button pb-2 border-b-2 border-transparent text-gray-600 hover:text-[var(--color-secondary)] transition duration-150" 
                        data-tab="notifications">
                    <i class="fas fa-bell mr-2"></i> Alertes & Notifications
                </button>
            </div>
            
            <!-- Contenu de l'Onglet 1: Rôles & Permissions -->
            <div id="roles" class="js-tab-content">
                <h2 class="text-2xl font-semibold text-[var(--color-secondary)] mb-4">Gestion des Rôles et Attribution</h2>
                <p class="mb-6 text-gray-600">Définissez les niveaux d'accès aux différentes sections de l'application et attribuez-les aux utilisateurs.</p>

                <!-- 1. Formulaire d'Attribution de Rôle (Nouvelle Section) -->
                <div class="mb-8 p-4 border border-[var(--color-primary)]/30 rounded-xl bg-orange-50/50">
                    <h3 class="text-xl font-semibold text-[var(--color-primary)] mb-3 flex items-center">
                         <i class="fas fa-user-tag mr-2"></i> Assigner un Rôle à un Utilisateur
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <input type="text" placeholder="Nom ou ID de l'utilisateur" class="col-span-2 p-2 border rounded-lg focus:ring-[var(--color-secondary)] focus:border-[var(--color-secondary)]">
                        <select class="p-2 border rounded-lg focus:ring-[var(--color-secondary)] focus:border-[var(--color-secondary)]">
                            <option>Choisir le Rôle...</option>
                            <option>Administrateur RH</option>
                            <option>Manager</option>
                            <option>Employé</option>
                        </select>
                        <button class="bg-[var(--color-secondary)] text-white font-semibold py-2 px-4 rounded-xl hover:bg-blue-800 transition duration-150">
                            <i class="fas fa-link mr-2"></i> Attribuer
                        </button>
                    </div>
                </div>

                <!-- 2. Tableau des Rôles (Définition des Permissions) -->
                <h3 class="text-xl font-semibold text-[var(--color-secondary)] mb-3 mt-6">Définition des Permissions par Rôle</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Accès Fiches Pers. (Lecture/Écriture)</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Accès Rapports RH</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gestion des Paramètres</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 text-sm">
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4 whitespace-nowrap font-semibold text-[var(--color-primary)]">Administrateur RH</td>
                                <td class="px-4 py-4 whitespace-nowrap text-green-600"><i class="fas fa-pencil-alt mr-1"></i> Lecture/Écriture</td>
                                <td class="px-4 py-4 whitespace-nowrap text-green-600"><i class="fas fa-check"></i> Complet</td>
                                <td class="px-4 py-4 whitespace-nowrap text-green-600"><i class="fas fa-check"></i> Oui</td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4 whitespace-nowrap font-medium text-gray-900">Manager</td>
                                <td class="px-4 py-4 whitespace-nowrap text-blue-600"><i class="fas fa-eye mr-1"></i> Lecture (pour son équipe)</td>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-500"><i class="fas fa-minus-circle"></i> Limité (Conformité)</td>
                                <td class="px-4 py-4 whitespace-nowrap text-red-600"><i class="fas fa-times"></i> Non</td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4 whitespace-nowrap font-medium text-gray-900">Employé</td>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-500"><i class="fas fa-user-circle mr-1"></i> Lecture (sa propre fiche)</td>
                                <td class="px-4 py-4 whitespace-nowrap text-red-600"><i class="fas fa-times"></i> Non</td>
                                <td class="px-4 py-4 whitespace-nowrap text-red-600"><i class="fas fa-times"></i> Non</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <button class="mt-6 bg-[var(--color-secondary)] text-white font-semibold py-2 px-4 rounded-xl hover:bg-blue-800 transition duration-150">
                    <i class="fas fa-plus mr-2"></i> Modifier ou Ajouter un Rôle
                </button>
            </div>
            
            <!-- Contenu de l'Onglet 2: Types de Documents & Validité (Masqué par défaut) -->
            <div id="documents" class="js-tab-content hidden">
                <h2 class="text-2xl font-semibold text-[var(--color-secondary)] mb-4">Gestion de la Conformité Documentaire</h2>
                <p class="mb-6 text-gray-600">Définissez la durée de validité et le caractère obligatoire de chaque pièce.</p>

                 <!-- Formulaire d'ajout rapide (simulation) -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8 p-4 border rounded-xl bg-blue-50/50">
                    <input type="text" placeholder="Nom du document (ex: Habilitation A2)" class="col-span-2 p-2 border rounded-lg focus:ring-[var(--color-primary)] focus:border-[var(--color-primary)]">
                    <select class="p-2 border rounded-lg focus:ring-[var(--color-primary)] focus:border-[var(--color-primary)]">
                        <option>Durée de validité...</option>
                        <option>1 An</option>
                        <option>2 Ans</option>
                        <option>Permanent</option>
                    </select>
                    <button class="bg-green-600 text-white font-semibold py-2 px-4 rounded-xl hover:bg-green-700 transition duration-150">
                        <i class="fas fa-save mr-2"></i> Ajouter
                    </button>
                </div>
                
                <!-- Liste des Documents -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Validité</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Obligatoire</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 text-sm">
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4 whitespace-nowrap font-medium text-gray-900">Contrat de Travail</td>
                                <td class="px-4 py-4 whitespace-nowrap">Permanent</td>
                                <td class="px-4 py-4 whitespace-nowrap text-green-600"><i class="fas fa-check"></i></td>
                                <td class="px-4 py-4 whitespace-nowrap"><button class="text-[var(--color-primary)] hover:underline text-xs">Modifier</button></td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4 whitespace-nowrap font-medium text-gray-900">Certificat Médical Annuel</td>
                                <td class="px-4 py-4 whitespace-nowrap text-red-600 font-medium">1 An</td>
                                <td class="px-4 py-4 whitespace-nowrap text-green-600"><i class="fas fa-check"></i></td>
                                <td class="px-4 py-4 whitespace-nowrap"><button class="text-[var(--color-primary)] hover:underline text-xs">Modifier</button></td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4 whitespace-nowrap font-medium text-gray-900">Attestation de Formation (Premiers Secours)</td>
                                <td class="px-4 py-4 whitespace-nowrap text-yellow-700 font-medium">3 Ans</td>
                                <td class="px-4 py-4 whitespace-nowrap text-green-600"><i class="fas fa-check"></i></td>
                                <td class="px-4 py-4 whitespace-nowrap"><button class="text-[var(--color-primary)] hover:underline text-xs">Modifier</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Contenu de l'Onglet 3: Alertes & Notifications (Masqué par défaut) -->
            <div id="notifications" class="js-tab-content hidden">
                <h2 class="text-2xl font-semibold text-[var(--color-secondary)] mb-4">Paramètres des Alertes</h2>
                <p class="mb-6 text-gray-600">Configurez quand et comment les utilisateurs sont notifiés des expirations et des actions requises.</p>

                <div class="space-y-6">
                    <!-- Paramètre 1: Délai d'Alerte Précoce -->
                    <div class="flex items-center justify-between p-4 border rounded-xl hover:bg-gray-50">
                        <label for="alerte-delai" class="font-medium text-gray-700 flex items-center">
                            <i class="fas fa-clock mr-3 text-blue-500"></i> Délai d'alerte pour l'expiration d'un document :
                        </label>
                        <div class="flex items-center space-x-2">
                             <input type="number" id="alerte-delai" value="60" min="7" max="180" class="w-20 p-2 border rounded-lg text-right">
                             <span class="text-gray-600">jours à l'avance</span>
                        </div>
                    </div>

                    <!-- Paramètre 2: Niveau d'Alerte Critique -->
                    <div class="flex items-center justify-between p-4 border rounded-xl hover:bg-gray-50">
                        <label for="alerte-critique" class="font-medium text-gray-700 flex items-center">
                            <i class="fas fa-exclamation-circle mr-3 text-red-500"></i> Seuil d'alerte critique (affichage rouge dans les rapports) :
                        </label>
                        <div class="flex items-center space-x-2">
                             <input type="number" id="alerte-critique" value="15" min="1" max="30" class="w-20 p-2 border rounded-lg text-right">
                             <span class="text-gray-600">jours restants</span>
                        </div>
                    </div>

                    <!-- Paramètre 3: Notification par Email aux Managers -->
                    <div class="flex items-center justify-between p-4 border rounded-xl hover:bg-gray-50">
                        <label for="email-manager" class="font-medium text-gray-700 flex items-center">
                            <i class="fas fa-envelope mr-3 text-green-500"></i> Envoyer une notification par email aux managers :
                        </label>
                        <div class="flex items-center">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="email-manager" value="" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[var(--color-primary)] rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[var(--color-secondary)]"></div>
                            </label>
                        </div>
                    </div>
                    
                    <button class="bg-[var(--color-primary)] text-white font-semibold py-2 px-4 rounded-xl hover:bg-orange-800 transition duration-150">
                        <i class="fas fa-save mr-2"></i> Enregistrer les Paramètres
                    </button>
                </div>
            </div>
            
        </div>
        
        <!-- Footer -->
        <footer class="mt-12 text-center text-xs text-gray-500 border-t border-gray-200 pt-4">
            &copy; 2025 Organisation Publique.
        </footer>
    </div>
    
</section>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>