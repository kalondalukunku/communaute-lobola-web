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
                 <i class="fas fa-user-lock mr-3 text-[var(--color-primary)]"></i> 
                Gestion des Utilisateurs et Attribution de Rôles
            </h1>
            <p class="text-gray-500 mt-1">Consultez et modifiez les rôles et le statut de connexion de tous les employés.</p>
        </header>
        
        <!-- Conteneur Principal -->
        <div class="card-container p-6">
            
            <!-- Barre de Recherche et Filtrage -->
            <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 mb-6">
                
                <!-- Recherche par Nom/ID -->
                <div class="relative flex-1">
                    <input type="text" placeholder="Rechercher par Nom, Prénom ou ID..." 
                           class="w-full p-3 pl-10 border border-gray-300 rounded-xl focus:ring-[var(--color-primary)] focus:border-[var(--color-primary)] transition duration-150">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
                
                <!-- Filtrage par Rôle -->
                <select class="w-full sm:w-1/4 p-3 border border-gray-300 rounded-xl focus:ring-[var(--color-primary)] focus:border-[var(--color-primary)]">
                    <option value="">Filtrer par Rôle</option>
                    <option value="Admin">Administrateur RH</option>
                    <option value="Manager">Manager</option>
                    <option value="Employee">Employé</option>
                </select>

                <!-- Bouton Ajouter Utilisateur -->
                <button class="w-full sm:w-auto bg-[var(--color-primary)] text-white font-semibold py-3 px-6 rounded-xl hover:bg-orange-800 transition duration-150 whitespace-nowrap">
                    <i class="fas fa-user-plus mr-2"></i> Ajouter Utilisateur
                </button>
            </div>

            <!-- Tableau des Utilisateurs -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom Prénom</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Employé</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle Actuel</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut du Compte</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 text-sm">
                        
                        <!-- Utilisateur 1: Administrateur -->
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4 whitespace-nowrap font-semibold text-gray-900">
                                <i class="fas fa-user-circle mr-2 text-xl text-gray-400"></i> Alain Dupont
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-gray-500">EMP-001</td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="role-tag bg-orange-100 text-[var(--color-primary)] border border-[var(--color-primary)]/50">
                                    <i class="fas fa-crown mr-1"></i> Admin RH
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="role-tag bg-green-100 text-green-700">
                                    <i class="fas fa-check-circle mr-1"></i> Actif
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <button onclick="openRoleModal('EMP-001', 'Alain Dupont')" class="text-[var(--color-secondary)] hover:underline font-medium text-xs mr-3">
                                    Modifier Rôle
                                </button>
                                <button class="text-red-500 hover:underline font-medium text-xs">
                                    Désactiver
                                </button>
                            </td>
                        </tr>

                        <!-- Utilisateur 2: Manager -->
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4 whitespace-nowrap font-semibold text-gray-900">
                                <i class="fas fa-user-circle mr-2 text-xl text-gray-400"></i> Sophie Lemaire
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-gray-500">EMP-105</td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="role-tag bg-blue-100 text-[var(--color-secondary)] border border-[var(--color-secondary)]/50">
                                    <i class="fas fa-briefcase mr-1"></i> Manager
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="role-tag bg-green-100 text-green-700">
                                    <i class="fas fa-check-circle mr-1"></i> Actif
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <button onclick="openRoleModal('EMP-105', 'Sophie Lemaire')" class="text-[var(--color-secondary)] hover:underline font-medium text-xs mr-3">
                                    Modifier Rôle
                                </button>
                                <button class="text-red-500 hover:underline font-medium text-xs">
                                    Désactiver
                                </button>
                            </td>
                        </tr>

                        <!-- Utilisateur 3: Employé (Inactif) -->
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4 whitespace-nowrap font-semibold text-gray-900">
                                <i class="fas fa-user-circle mr-2 text-xl text-gray-400"></i> Marc Dubois
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-gray-500">EMP-230</td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="role-tag bg-gray-100 text-gray-600 border border-gray-400/50">
                                    <i class="fas fa-user mr-1"></i> Employé
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="role-tag bg-red-100 text-red-700">
                                    <i class="fas fa-times-circle mr-1"></i> Inactif
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <button onclick="openRoleModal('EMP-230', 'Marc Dubois')" class="text-[var(--color-secondary)] hover:underline font-medium text-xs mr-3">
                                    Modifier Rôle
                                </button>
                                <button class="text-green-500 hover:underline font-medium text-xs">
                                    Activer
                                </button>
                            </td>
                        </tr>

                         <!-- Utilisateur 4: Employé -->
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4 whitespace-nowrap font-semibold text-gray-900">
                                <i class="fas fa-user-circle mr-2 text-xl text-gray-400"></i> Fatou Diallo
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-gray-500">EMP-412</td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="role-tag bg-gray-100 text-gray-600 border border-gray-400/50">
                                    <i class="fas fa-user mr-1"></i> Employé
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="role-tag bg-green-100 text-green-700">
                                    <i class="fas fa-check-circle mr-1"></i> Actif
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <button onclick="openRoleModal('EMP-412', 'Fatou Diallo')" class="text-[var(--color-secondary)] hover:underline font-medium text-xs mr-3">
                                    Modifier Rôle
                                </button>
                                <button class="text-red-500 hover:underline font-medium text-xs">
                                    Désactiver
                                </button>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <!-- Pagination (simulation) -->
            <div class="mt-6 flex justify-end items-center space-x-2 text-sm">
                <button class="p-2 border rounded-full hover:bg-gray-100 text-gray-500"><i class="fas fa-chevron-left"></i></button>
                <span class="px-3 py-1 bg-[var(--color-secondary)] text-white rounded-full">1</span>
                <button class="px-3 py-1 hover:bg-gray-200 rounded-full">2</button>
                <button class="px-3 py-1 hover:bg-gray-200 rounded-full">3</button>
                <button class="p-2 border rounded-full hover:bg-gray-100 text-gray-500"><i class="fas fa-chevron-right"></i></button>
            </div>
            
        </div>
        
        <!-- Footer -->
        <footer class="mt-12 text-center text-xs text-gray-500 border-t border-gray-200 pt-4">
            &copy; 2025 Organisation Publique. Tous droits réservés.
        </footer>
    </div>

    <!-- Modal pour la Modification de Rôle (simulé) -->
    <div id="roleModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center hidden z-50 transition-opacity duration-300" onclick="closeRoleModal()">
        <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-md mx-4 transform transition-all duration-300 scale-95" onclick="event.stopPropagation()">
            <h3 class="text-xl font-bold text-[var(--color-secondary)] mb-4 flex items-center">
                <i class="fas fa-user-edit mr-2 text-[var(--color-primary)]"></i> Modifier le Rôle
            </h3>
            
            <p class="text-gray-600 mb-4">
                Vous modifiez le rôle pour l'utilisateur : 
                <span id="modalUserName" class="font-semibold text-gray-900"></span> 
                (ID: <span id="modalUserId" class="font-mono text-xs bg-gray-100 px-2 py-0.5 rounded"></span>).
            </p>

            <label for="newRole" class="block text-sm font-medium text-gray-700 mb-2">Nouveau Rôle</label>
            <select id="newRole" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-[var(--color-primary)] focus:border-[var(--color-primary)] mb-6">
                <option value="Admin">Administrateur RH</option>
                <option value="Manager">Manager</option>
                <option value="Employee" selected>Employé</option>
            </select>

            <div class="flex justify-end space-x-3">
                <button onclick="closeRoleModal()" class="px-4 py-2 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-100 transition duration-150">
                    Annuler
                </button>
                <button onclick="saveRoleChange()" class="px-4 py-2 bg-[var(--color-primary)] text-white font-semibold rounded-xl hover:bg-orange-800 transition duration-150">
                    <i class="fas fa-save mr-2"></i> Enregistrer
                </button>
            </div>
        </div>
    </div>
    
</section>

<?php include APP_PATH . 'views/layouts/footer.php'; ?>